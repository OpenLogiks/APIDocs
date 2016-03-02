<?php 
if(!defined('ROOT')) exit('No direct script access allowed');

loadModuleLib('guides','api');
loadModuleLib("markitup","api");

$page=$_REQUEST['page'];
$pageArr=explode("/",$page);

if(count($pageArr)>1){
	$title=end($pageArr);
	$titleArr=explode("-",$title);
	$id=md5(end($titleArr));
	$guideDetails=getGuideDetails($id);
	//printArray($guideDetails);

	if($guideDetails['viewable']) {
?>
<div class="guideContent container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<?php if(session_check(false)) { ?>
			<div id='toolbar' class="text-right">
				<a href="<?=_link("guides/create")?>">Create</a> |
				<?php if($guideDetails['editable']=='true'){ ?>
					<a href="<?=$guideDetails['edit_url']?>">Edit</a>
				<?php } ?>
			</div>
			<?php } ?>
			<?php
				if($guideDetails['status']=="draft") {
					echo "<div class='alert alert-warning' style='margin-top: 40px;'>This guide is still a <b>DRAFT</b>.";
					if($guideDetails['creatorid']==$_SESSION['SESS_USER_ID']) {
						echo "<button id='publishArticle' rel='{$guideDetails['id']}' type='button' class='btn btn-success pull-right' style='margin-top: -7px;'> Publish</button></div>";
					} else {
						echo "</div>";
					}
				} elseif($guideDetails['approved']=="false") {
					echo "<div class='alert alert-danger' style='margin-top: 40px;'>This guide is still to be approved.";
					if($_SESSION['SESS_PRIVILEGE_ID']<=3) {
							echo "<button id='approveArticle' rel='{$guideDetails['id']}' type='button' class='btn btn-success pull-right' style='margin-top: -7px;'>Approve</button></div>";
					} elseif(checkUserRoles("guides","Allow Approval Of Guides") && 
						($guideDetails['creatorid']<>$_SESSION['SESS_USER_ID'])) {
						echo "<button id='approveArticle' rel='{$guideDetails['id']}' type='button' class='btn btn-success pull-right' style='margin-top: -7px;'>Approve</button></div>";
					} else {
						echo "</div>";
					}
				}
			?>
			<h1 class="page-header">
				<small><?=$guideDetails['title']?> 
				<?php
					if(strlen($guideDetails['tags'])>0) {
						echo "<span class='tags'>";
						$guideDetails['tags']=explode(",", $guideDetails['tags']);
						foreach ($guideDetails['tags'] as $tag) {
							echo "<a>{$tag}</a>";
						}
						echo "</span>";
					}
					?>
				</small>
			</h1>			
			
			<div><?php
				//$guideDetails['guide_txt']=stripslashes($guideDetails['guide_txt']);
				//echo $guideDetails['guide_txt'];
				echo showMarkitupPreview($guideDetails['guide_txt'],$guideDetails['guide_parser']);
			?></div>
					
				
			<p></p>
		</div><!-- col-lg-12 end-->
		
	</div>
</div>
<script>
$(function() {
	$("#publishArticle").click(function() {
		rel=$(this).attr("rel");
		lx=getServiceCMD("guides","publish-guide");
		processAJAXPostQuery(lx,"gid="+rel,function(txt) {
				if(txt.length>0 && txt!="success") {
					$("#publishArticle").parent(".alert").attr("class","alert alert-danger").
						html(txt).delay(4000).slideUp(function() {$(this).detach();window.location.reload();});
				} else {
					$("#publishArticle").parent(".alert").attr("class","alert alert-info").
						html("Published the article successfully").delay(2000).slideUp(function() {$(this).detach();});
				}
			});
	});
	$("#approveArticle").click(function() {
		rel=$(this).attr("rel");
		lx=getServiceCMD("guides","approve-guide");
		processAJAXPostQuery(lx,"gid="+rel,function(txt) {
				if(txt.length>0 && txt!="success") {
					$("#approveArticle").parent(".alert").attr("class","alert alert-danger").
						html(txt).delay(4000).slideUp(function() {$(this).detach();window.location.reload();});
				} else {
					$("#approveArticle").parent(".alert").attr("class","alert alert-info").
						html("Approved the article successfully").delay(2000).slideUp(function() {$(this).detach();});
				}
			});
	});
});
</script>
<?php } else {
		?>
<div class="container-fluid">
	<div class="row">
            <h1 class="title">
				Sorry, you do not have privilege to view this article. Contact Admin.
			</h1>
    </div>
</div>
<?php
	  } 
} else { ?>
<div class="container-fluid">
	<div class="row">
            <h1 class="title">
				<img class=logoimg src='<?=loadMedia("logos/logo-128.png")?>' alt='Logo Image' />
				<br/>
				Logiks APIDOCS
			</h1>
    </div>
</div>
<?php } ?>
