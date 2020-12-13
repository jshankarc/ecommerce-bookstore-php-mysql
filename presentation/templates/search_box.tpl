{load_presentation_object filename="searchbox" assign="obj"}

	
  <form class="search_form" method="post" action="{$obj->mLinkToSearch}">
    <div style="margin-top:0px;">
      <input class="txt" maxlength="100" id="search_string" name="search_string" value="{$obj->mSearchString}" size="19" />
      <input class="but" type="submit" value="Go!" />
      <input type="checkbox" id="all_words" name="all_words"
       {if $obj->mAllWords == "on"} checked="checked" {/if}/>
      Search for all words
    </div>
  </form>