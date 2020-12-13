{load_presentation_object filename="controller" assign="obj"}

<!DOCTYPE HTML>
<html>
	<head>
	<title>{$obj->mPageTitle}</title>
	<link rel="stylesheet" style="text/css" href="{$obj->mSiteUrl}styles/style.css">
	</head>
	<body>
	<div id="header">
		<table border="0" style="width:100%;height:100%;">
			<tr id="logo" style="height:170px;">
				<td rowspan="2"> 
				<a href="{$obj->mSiteUrl}" >
					<img src="{$obj->mSiteUrl}images/logo/logo.png" style="height:40%;width:80%;" alt="Book Shop Logo"/>
				</a> 
				</td>
				<td colspan=2 align="right" valign="bottom"> 
					{include file = "search_box.tpl"}
				</td>
			</tr>
			<tr>
				<td colspan=2></td>
			</tr> 
			<tr valign="top">
				<td  style="width:350px;" id="category" >
					<div style="border:2px solid gray;margin-top:10px;width:360px;">
						{include file="categorylist.tpl"}
					</div>
					<div style="border:2px solid gray;margin-top:10px;width:360px;">
						{include file = $obj->mCartSummaryCell}
					<div>
					<br/>
				</td>
				<td align="center" rowspan='2'>{include file= $obj->mContentsCell }</td>
			</tr>
			<tr>
				<td colspan='2'>c7</td>
			</tr>
		</table>
	</body>
</html>