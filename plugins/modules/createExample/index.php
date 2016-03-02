<?php
if(!defined('ROOT')) exit('No direct script access allowed');
session_check(true);

_css(array("jquery.tagit"));
_js(array("jquery.tagit","validator"));
if(isset($_REQUEST['refid'])){
    $id=$_REQUEST['refid'];
    $exampleDetails['editable']='true';
}elseif(isset($_REQUEST['egid'])){
	$eg_id=$_REQUEST['egid'];
	loadModuleLib('api','api');
	$exampleDetails=getExampleDetails($eg_id);
}else{
	echo "<h1 align=center>Something Went Wrong</h1>";
}
if($exampleDetails['editable']=='true') {
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">

			<form role="form" name="exampleForm" id="exampleForm" class='apiForm' onsubmit="return validateForm('#exampleForm');">
				<div class="form-group">
					<label> Example Description *</label>
					<textarea class="form-control required" rows="3" name="eg_descs" id="eg_descs" required><?php if(isset($exampleDetails['eg_descs'])){echo $exampleDetails['eg_descs']; } ?></textarea>
					<input type="hidden" name="api_id" id="api_id" value="<?php if(isset($id)){echo $id; }else{ echo 0;}?>">
					<?php if(isset($_REQUEST['egid'])){ ?>
						<input type="hidden" name="eg_id" id="eg_id" value="<?php if(isset($eg_id)){echo $eg_id; }else{echo 0;}?>">
					<?php } ?>
				</div>
				<div class="form-group">
					<label>Tags</label>
					<input class="form-control tagfield" name="tags" id="tags" value="<?php if(isset($exampleDetails['tags'])){echo $exampleDetails['tags']; } ?>">
				</div>
				<div class="form-group">
					<label>Source Code*</label>
					<textarea class="form-control required" rows="3" name="eg_php" id="eg_php" style="height: 200px;" required><?php if(isset($exampleDetails['eg_php'])){echo $exampleDetails['eg_php']; } ?></textarea>
				</div>
				<div id='form-buttons-bar' class="form-group form-buttons">
					<button type="reset" class="btn btn-danger" id="cancel" >Cancel</button>
					<button type="submit" class="btn btn-default" id="submitExample">Submit</button>
				</div>
			</form>

		</div>   
	</div>
</div>
<script>
	$(function(){
		$(".tagfield").tagit({
		singleField:true,			
	});
		
	$('#exampleForm').submit(function(e){
		e.preventDefault();
		saveExample();
		return false;
	});
	$('#cancel').click(function(){
		window.history.back();
	});
	});
	function saveExample(){
		lx=getServiceCMD("api")+"&action=add-example&format=json";
		var apiId=$('#id').val();
		q="";
		$("#exampleForm input, #exampleForm textarea").each(function() {
			if( $(this).attr("name") != null && $(this).attr("name").length > 0 ) {
				q += "&" + $(this).attr("name") + "=" + encodeURIComponent($(this).val());
				//$(this).val("");
			}
		});
		$('#form-buttons-bar').hide();
		processAJAXPostQuery(lx,q,function(txt) {
			
			jsonData=$.parseJSON(txt);
			if(jsonData.Data=='success'){
				alert("Example Saved Successfully.");
				$('#form-buttons-bar').show();
				window.history.back();	
			}	
		});
	}
</script>
<?php } else {
	echo "<h1 align=center>Sorry, You do not have previlege to edit this Guide</h1>";
}
?>