<?php 
if(!defined('ROOT')) exit('No direct script access allowed');

loadModuleLib('api','api');
loadModuleLib("markitup","api");

$login=session_check();

$page=$_REQUEST['page'];
$pageArr=explode("/",$page);
if(count($pageArr)>1){
	$title=end($pageArr);
	$titleArr=explode("-",$title);
	$id=md5(end($titleArr));
	$apiDetails=getApiDetails($id);
	
	_js(array("jquery.snippet.min"));
	_css(array("jquery.snippet.min"));
?>
 
<div class="apiContent container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<?php if(session_check(false)) { ?>
			<div id='toolbar' class="text-right">
				<a href="<?=_link("api/create")?>">Create</a> |
				<?php if($apiDetails['editable']=='true'){ ?>
					<a href="<?=$apiDetails['edit_url']?>">Edit</a> |
					<a href="<?=$apiDetails['addExample']?>">Add Example</a> | 
				<?php } ?>
				<a href="#" id="addCommentLnk">Add Comment</a>
			</div>
			<?php } ?>
				<?php
					if($apiDetails['approved']=="false") {
						echo "<div class='alert alert-danger' style='margin-top: 40px;'>This api is still to be approved.";
						if($_SESSION['SESS_PRIVILEGE_ID']<=3) {
							echo "<button id='approveArticle' rel='{$apiDetails['id']}' type='button' class='btn btn-success pull-right' style='margin-top: -7px;'>Approve</button></div>";
						} elseif(checkUserRoles("api","Allow Approval Of API") && 
							($apiDetails['creatorid']<>$_SESSION['SESS_USER_ID'])) {
							echo "<button id='approveArticle' rel='{$apiDetails['id']}' type='button' class='btn btn-success pull-right' style='margin-top: -7px;'>Approve</button></div>";
						} else {
							echo "</div>";
						}
					}
				?>
				<h1 class="page-header">
					<small><?=$apiDetails['title']?>
						<?php
							if(strlen($apiDetails['tags'])>0) {
								echo "<span class='tags'>";
								$apiDetails['tags']=explode(",", $apiDetails['tags']);
								foreach ($apiDetails['tags'] as $tag) {
									echo "<a>{$tag}</a>";
								}
								echo "</span>";
							}
						?>
					</small>
				</h1>
				<div class="versionReference text-right" style='margin-top: -38px'>
					<?php 
						$version=strtoupper($apiDetails['src_lang']);
						if($apiDetails['min_vers']!=""){
							$version.=" ,".getConfig("VERS_REFERENCE")." >= ".$apiDetails['min_vers'];
						}
						if($apiDetails['max_vers']!=""){
							$version.=",".getConfig("VERS_REFERENCE")." <= ".$apiDetails['max_vers'];
						}
						echo $version;
					?>
				</div>
				<div class='shortDescs'> 
					<span class="refname"><?=$apiDetails['obj_type']." ".$apiDetails['title']?></span>
					-
					<?=stripslashes($apiDetails['descs_short'])?>
				</div>
				<h2>
					<small>Description</small>
				</h2>
				<?php
					if(strlen($apiDetails['defination'])>0) {
						echo "<div class='alert alert-info'><i class='fa fa-info-circle'></i>  {$apiDetails['defination']}</div>";
					}
					echo "<div class='nested textContent'>".showMarkitupPreview($apiDetails['descs_long'],$apiDetails['descs_parser'])."</div>";
				?>
				

				<?php if($apiDetails['descs_params']!="") { ?>
					<hr/>
					<h2>
						<small>Parameters</small>
					</h2>
					<div class='nested'><?=stripslashes($apiDetails['descs_params'])?></div>
				<?php } ?>

				<h2>
					<small>Other Informations</small>
					<i class='fa fa-expand toggleButton pull-right' for='#otherInformations'></i>
				</h2>
				<div id='otherInformations' class='nested' style='clear:both;height: 205px;border:1px solid #CCC;display:none;'>
					<div class="panel panel-default col-sm-6 col-lg-6" style='border:0px;'>
						<table class="table" width=100%>
							<?php
							  	$arrInfo=array(
							  			"obj_type"=>"Type",
							  			"lgks_type"=>"Category",
							  			"src_lang"=>"Lang",
							  			"src_name"=>"Source",
							  			"src_path"=>"Source Path",
							  		);
							  	foreach ($arrInfo as $key => $value) {
							  		echo "<tr><td>{$value}</td><td>{$apiDetails[$key]}</td></tr>";
							  	}
							  ?>
						</table>
					</div>
					<div class="panel panel-default col-sm-6 col-lg-6" style='border:0px;'>
						<table class="table" width=100%>
							<tr><td>Source Link</td><td><a href='<?=$apiDetails['src_checkout']?>' target=_blank><i class='fa fa-link'></i> View Source</a></td></tr>
							<tr><td>Package</td><td><?=$apiDetails['package_name']." [{$apiDetails['package_id']}]"?></td></tr>
							<tr><td>Last Author</td><td><?=$apiDetails['author']?></td></tr>
							<tr><td>Submitted By</td><td><?=$apiDetails['creator']?></td></tr>
						</table>
					</div>
				</div>

				<?php 
					if(count($apiDetails['examples'])>0){
						?>
						<hr/>
					<h2>
						<small>
							<i class="fa fa-code" style="margin-right: 5px;font-size: 1.5em;"></i>
							Examples
						</small>
					</h2>
					<div class='nested'>
					<?php 
						for($i=0;$i<count($apiDetails['examples']);$i++){
							$phpcode=stripslashes($apiDetails['examples'][$i]['eg_php']);
							$phpcode =str_replace('<br>',PHP_EOL,$phpcode);
							$id="codeBlock".$i;
							$blockid="pre#".$id;
							?>
							<div class='exampleBlock'> 
								<div class='descs'><?=$apiDetails['examples'][$i]['eg_descs']?></div>
								<?php 
									if($apiDetails['examples'][$i]['editable']=='true'){ 
								?>
									<div class="text-right"><a href="<?=$apiDetails['examples'][$i]['edit_url']?>">Edit</a></div>
								<?php
									}
								?>
								<pre class='codeBlock' id='<?=$id?>'><?php echo htmlentities($phpcode); ?></pre>
							</div>
							<script>
								$("<?=$blockid?>").snippet("html",{style:"whitengrey",menu:false});
							</script>
							<?php 
						}
					?>
					</div>
				<?php }?>

				<?php 
					if(count($apiDetails['comments'])>0){
				?>
				<hr/>
				<h2>
						<small>
							<i class="fa fa-comments" style="margin-right: 5px;font-size: 1.5em;"></i>
							Comments
						</small>
				</h2>
	
				
				<div class="actionBox" id="apiComment">
					<ul class="commentList">
				<?php 
					for($i=0;$i<count($apiDetails['comments']);$i++){
				?>
				
				 <li>
                        <div class="commenterImage">
							<img src="<?=loadMedia('images/loginUser.png')?>" />
                        </div>
                        <div class="commentText">
                            <h5><?=$apiDetails['comments'][$i]['username']?> [<?=$apiDetails['comments'][$i]['userid']?>]</h5>
                            <p class=""><?=$apiDetails['comments'][$i]['comment']?></p> <span class="date sub-text">on <?php echo date("d M, Y",strtotime($apiDetails['comments'][$i]['dtoc'])); ?></span>
                
                        </div>
                    </li>
				<?php 
					}
						
				?>
				</ul>		
				</div><!--api comment end-->
				<?php if($login){ ?>
					<div class="text-right">
						<a href="#" id="addCommentLnk1" >Add Comment</a>
					</div>
			
				<?php }
				} ?>
				<?php if($login){ ?>
				<div id="CommentDiv" style="display:none;">
					<div class="form-group">
						<label>Add Comment</label>
						<input type="hidden" name="api_id" id="api_id" value="<?=$apiDetails['id']?>">
						<textarea class="form-control required" rows="3" name="comment" id="comment"></textarea>
					</div>
					<div class="form-group form-buttons">
							<button type="button" class="btn btn-default" id="postComment">Post</button>
					</div>
				</div>
				<?php } ?>
				<p></p>
		</div><!-- col-lg-12 end-->
		
	</div>
</div>
<script> 
$(function(){
	
	//$("pre.codeBlock").snippet("html",{style:"whitengrey"});
	/*$("pre.codeBlock").each(function(){
		alert("code 1");
		$(this).snippet("html",{style:"whitengrey",startText:true,menu:true});
	});
	*/
	//whitengrey
	$('#postComment').click(function(){
		saveComment();
	});
	$('#addCommentLnk').click(function(e){
		e.preventDefault();
		$('#CommentDiv').show();
	});
	$('#addCommentLnk1').click(function(e){
		e.preventDefault();
		$('#CommentDiv').show();
	});
	$("#approveArticle").click(function() {
		rel=$(this).attr("rel");
		lx=getServiceCMD("api","approve-api");
		processAJAXPostQuery(lx,"apid="+rel,function(txt) {
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
function saveComment(){
	$('#postComment').hide();
	var lcomment=$('#comment').val().length;

	if(lcomment>0){
		lx=getServiceCMD("api")+"&action=post-comment&format=json";
		var  q="comment="+$('#comment').val();
			 q+="&api_id="+$('#api_id').val();
		processAJAXPostQuery(lx,q,function(txt) {
			jsonData=$.parseJSON(txt);
			if(jsonData.Data=='success'){
				   	$('#CommentDiv').hide();
					window.location.reload()
			   }
		});
	} else {
		$('#comment').focus();
		$('#postComment').show();
	}
}

</script>
<?php 
} 
else{
	?>
<div class="container-fluid">
	<div class="row">
            <h1 class="title">
				<img class=logoimg src='<?=loadMedia("logos/logo-128.png")?>' alt='Logo Image' />
				<br/>
				Logiks APIDOCS
			</h1>
    </div>
</div>
<?php
}
?>
