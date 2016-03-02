<?php
if(!defined('ROOT')) exit('No direct script access allowed');
loadModuleLib('api','api');
	
$page=$_REQUEST['page'];
$pageArr=explode("/",$page);
if(isset($pageArr[2]) && strlen($pageArr[2])<=0) unset($pageArr[2]);

if(count($pageArr)>1){
	$title=end($pageArr);
	$titleArr=explode("-",$title);
	$category=end($titleArr);
	$apiCategoryList=getlist($category);
}
?>
<div class="apiContent container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
					<small><?=toTitle($category)?> APIs</small>
				</h1>
			<div class="navigation">
				<ul class="list-group linkList">
					<?php 
					if(count($apiCategoryList)>0){
						foreach($apiCategoryList as $api){
							$apiLink=_link("api/").$api['category']."/".urlencode($api['subcategory'].".".$api['title'])."-".$api['id'];
							?>
							<li class='list-group-item'> <a href="<?=$apiLink?>"> <?=$api['title']?></a></li>
							<?php 
						}
					} ?>
				<ul>
			</div>
		</div>
	</div>
</div>