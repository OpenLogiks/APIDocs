<?php
if(!defined('ROOT')) exit('No direct script access allowed');
loadModuleLib('guides','api');
	
$page=$_REQUEST['page'];
$pageArr=explode("/",$page);
if(isset($pageArr[2]) && strlen($pageArr[2])<=0) unset($pageArr[2]);

if(count($pageArr)>1){
	$title=end($pageArr);
	$title=explode("-",$title);
	$title=implode(" ",$title);
	//$titleArr=explode("-",$title);
	//$type=end($titleArr);
	$guideCategoryList=getlist($title);
}
?>
<div class="apiContent container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
					<small><?=toTitle($title)?> Guides</small>
				</h1>
			<div class="navigation linkList">
				<ul class="list-group">
					<?php 
					if(count($guideCategoryList)>0){
						foreach($guideCategoryList as $guide){
							$subcategory=str_replace(" ","-" ,$guide['subcategory']);
							$title=str_replace(" ","-" ,$guide['title']);
							$guideLink=_link("guides/").$guide['category']."/".urlencode($subcategory.".".$title)."-".$guide['id'];
							?>
							<li class='list-group-item'><a href="<?=$guideLink?>"><?=toTitle($guide['title'])?></a></li>
							<?php 
							}
						} ?>
				<ul>
			</div>
		</div>
	</div>
</div>
