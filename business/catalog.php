<?php
// Business tier class for reading product catalog information

class Catalog
{
   public static function getCategoryList(){
	
	$sql = 'CALL catalog_get_category_list()';
	
	return DatabaseHandler::GetAll($sql);
	
   }
   
   public static function getProductOnCatalog($pageNo, &$rHowManyPages)
   {
	$sql = 'CALL catalog_count_products_on_catalog()';
	
	$rHowManyPages = Catalog::HowManyPages($sql,null);
	
	$start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;
	
	$sql = 'CALL catalog_get_products(
				:short_product_description_length,
				:products_per_page,
				:start_item)';
	$params = array(
				'short_product_description_length' => SHORT_PRODUCT_DESCRIPTION_LENGTH,
				'products_per_page' => PRODUCTS_PER_PAGE,
				'start_item' => $start_item);
				
	return DatabaseHandler::GetAll($sql,$params);
   }
   public static function getProductsInCatalog($categoryId,$pageNo, &$rHowManyPages)
   {
	$sql = 'CALL catalog_count_products_in_category(:categoryID)';
	
	$params = array(
				':categoryID' => $categoryId);
	
	$rHowManyPages = Catalog::HowManyPages($sql,$params);
	
	$start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

	$sql = 'CALL catalog_get_products_on_category(
				:categoryID,
				:short_product_description_length,
				:products_per_page,
				:start_item)';
	$params = array(
				':categoryID' => $categoryId,
				':short_product_description_length' => SHORT_PRODUCT_DESCRIPTION_LENGTH,
				':products_per_page' => PRODUCTS_PER_PAGE,
				':start_item' => $start_item
				);
		
	return DatabaseHandler::GetAll($sql,$params);
   }   
   public static function HowManyPages($countSql, $countSqlParams)
   {
	$queryHashCode = md5($countSql .var_export($countSqlParams,true));
	
	if(isset ($_SESSION['last_count_hash']) &&
	   isset ($_SESSION['how_many_pages']) &&
	   $_SESSION['last_count_hash'] === $queryHashCode)
	   {
		$how_many_pages = $_SESSION['how_many_pages'];
	   }
	else
	{
		$items_count = DatabaseHandler::GetOne($countSql,$countSqlParams);

		$how_many_pages = ceil($items_count / PRODUCTS_PER_PAGE);

		$_SESSION['last_count_pages'] = $queryHashCode;
		$_SESSION['how_many_pages'] = $how_many_pages;
	}
	return $how_many_pages;
   }
   
   public static function getProductDetails($productId)
   {
	$sql = 'CALL catalog_get_product_details(:productID)';
	
	$params = array(':productID' => $productId);
	
	return DatabaseHandler::GetAll($sql,$params);
   }
   
   public static function getCategoryName($categoryID)
   {
	$sql = 'CALL catalog_get_category_name(:categoryID)';
	
	$params = array (':categoryID' => $categoryID);
	
	return DatabaseHandler::GetOne($sql,$params);
   }
   
   public static function getProductName($productName)
   {
	$sql = 'CALL  catalog_get_product_name(:name)';
	
	$params = array (':name' => $productName);
	
	return DatabaseHandler::GetOne($sql,$params);
   }
	public static function Search($searchString, $allWords,
                                $pageNo, &$rHowManyPages)
	{
    //The search result will be an array of this form
		$search_result = array ('accepted_words' => array (),
								'ignored_words' => array (),
								'products' => array ());

		// Return void if the search string is void
		if (empty ($searchString))
		  return $search_result;

		// Search string delimiters
		$delimiters = ',.; ';

		/* On the first call to strtok you supply the whole
		   search string and the list of delimiters.
		   It returns the first word of the string */
		$word = strtok($searchString, $delimiters);

		// Parse the string word by word until there are no more words
		while ($word)
		{
		  // Short words are added to the ignored_words list from $search_result
		  if (strlen($word) < FT_MIN_WORD_LEN)
			$search_result['ignored_words'][] = $word;
		  else
			$search_result['accepted_words'][] = $word;

		  // Get the next word of the search string
		  $word = strtok($delimiters);
		}

		// If there aren't any accepted words return the $search_result
		if (count($search_result['accepted_words']) == 0)
		  return $search_result;

		// Build $search_string from accepted words list
		$search_string = '';

		// If $allWords is 'on' then we append a ' +' to each word
		if (strcmp($allWords, "on") == 0)
		  $search_string = implode(" +", $search_result['accepted_words']);
		else
		  $search_string = implode(" ", $search_result['accepted_words']);

		// Count the number of search results
		$sql = 'CALL catalog_count_search_result(:search_string, :all_words)';
		$params = array(':search_string' => $search_string,
						':all_words' => $allWords);

		// Calculate the number of pages required to display the products
		$rHowManyPages = Catalog::HowManyPages($sql, $params);
		// Calculate the start item
		$start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

		// Retrieve the list of matching products
		$sql = 'CALL catalog_search(:search_string, :all_words,
									:short_product_description_length,
									:products_per_page, :start_item)';

		// Build the parameters array
		$params = array (':search_string' => $search_string,
						 ':all_words' => $allWords,
						 ':short_product_description_length' =>
						   SHORT_PRODUCT_DESCRIPTION_LENGTH,
						 ':products_per_page' => PRODUCTS_PER_PAGE,
						 ':start_item' => $start_item);

		// Execute the query
		$search_result['products'] = DatabaseHandler::GetAll($sql, $params);

		// Return the results
		return $search_result;
	}
}
?>
