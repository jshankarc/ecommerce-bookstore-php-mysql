<?php
class Product
{
	public $mProduct;
	public $mLinkToContinueShopping;

	  
	private $_mProductId;
	public function __construct()
	{
		if (isset ($_GET['ProductId']))
			$this->_mProductId = (int)$_GET['ProductId'];
		else
			trigger_error('ProductId not set');
	}
	
	public function init()
	{
		$this->mProduct = Catalog::GetProductDetails($this->_mProductId);
		

				
		if (isset ($_SESSION['link_to_continue_shopping']))
		{
		  $continue_shopping =
			Link::QueryStringToArray($_SESSION['link_to_continue_shopping']);

		  $page = 1;

		  if (isset ($continue_shopping['Page']))
			$page = (int)$continue_shopping['Page'];
		  elseif (isset ($continue_shopping['CategoryId']))
			$this->mLinkToContinueShopping =
			  Link::ToCategory((int)$continue_shopping['CategoryId'], $page);
		  elseif (isset ($continue_shopping['SearchResults']))
			$this->mLinkToContinueShopping =
				Link::ToSearchResults(
					trim(str_replace('-', ' ', $continue_shopping['SearchString'])),
						$continue_shopping['AllWords'], $page);
		  else
			$this->mLinkToContinueShopping = Link::ToIndex($page);
		}
 		     $this->mProduct[0]['link_to_add_product'] =
       Link::ToCart(ADD_PRODUCT, $this->_mProductId); 

	
	}
}
?>