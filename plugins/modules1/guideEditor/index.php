<?php
if(!defined('ROOT')) exit('No direct script access allowed');
session_check(true);

_css(array("jquery.tagit"));
_js(array("jquery.tagit","validator"));

loadHelpers('uicomponents');

loadModule("editor");
loadEditor("cleditor");

if(isset($_REQUEST['refid'])) {
	loadModuleLib('guides','api');
	$guideDetails=getGuideDetails($_REQUEST['refid']);
} else {
	$guideDetails['editable']="true";
}
if($guideDetails['editable']=='true') {
?>
<style>

.cleditorMain {
	margin-top: 19px;
	height: 400px !important;
}
.cleditorToolbar {
	margin-top: -25px;
	border: 1px solid #AAA;
	height: 25px;
	width: 100%;
	margin-left: -2px;
}
.cleditorMain iframe {
	height: 100%;width: 100%;
}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">

			<form role="form" name="guideForm" id="guideForm" class='apiForm' onsubmit="return validateForm('#guideForm');">
				
				<div class="form-group">
					<label>Guide Title *</label>
					<input type="text" class="form-control required" name="title" id="title" value="<?php if(isset($guideDetails['title'])){echo $guideDetails['title']; } ?>" required>
					
				</div>
				<input type="hidden" name="id" id="id" value="<?php if(isset($guideDetails['id'])){echo md5($guideDetails['id']); }else{echo 0;} ?>" >
				<div class="form-group">
					<label>Category *</label>
					<input type="text" class="form-control required" name="guide_group" id="guide_group" value="<?php if(isset($guideDetails['guide_group'])){echo $guideDetails['guide_group']; } ?>" required>
				</div>
				<div class="form-group">
					<label>Tags</label>
					<input class="form-control tagfield" name="tags" id="tags" value="<?php if(isset($guideDetails['tags'])){echo $guideDetails['tags']; } ?>">
				</div>
				<div class="form-group">
					<label>Type *</label>
					<select class="form-control " name="type" id="type" value="<?php if(isset($guideDetails['type'])) echo $guideDetails['type']; ?>" required>
						<?=createDataSelector(_db(),"guide_category",false);?>
					</select>
				</div>
				<div class="form-group">
					<label>Description *</label>
					<div class='editorContainer'>
						<textarea class="form-control required" rows="3" name="guide_txt" id="guide_txt" required><?php if(isset($guideDetails['guide_txt'])){echo $guideDetails['guide_txt']; } ?></textarea>
					</div>
				</div>
				
				<div id="form-buttons-bar" class="form-group form-buttons">
					<button type="reset" class="btn btn-danger" id="cancel">Cancel</button>
					<button type="submit" class="btn btn-default" id="submitGuide">Submit</button>
				</div>
			</form>

		</div>   
	</div>
</div>
<script>
$(function(){
	$("select[value]").each(function() {
		$(this).val($(this).attr("value"));
	});
	$(".tagfield").tagit({
		singleField:true,
	});
	$('#guideForm').submit(function(e){
		e.preventDefault();
		saveGuide();
		return false;
	});
	$('#cancel').click(function(){
		window.history.back();
	});
	loadEditor("#guide_txt");
});
function saveGuide(){
	
		lx=getServiceCMD("guides")+"&action=create-guide&format=json";
		var guideId=$('#id').val();
		q="";
		$("#guideForm input, #guideForm textarea,#guideForm select").each(function() {
			if( $(this).attr("name") != null && $(this).attr("name").length > 0 ) {
				q += "&" + $(this).attr("name") + "=" + encodeURIComponent($(this).val());
				//$(this).val("");
			}
		});
		$('#form-buttons-bar').hide();
		processAJAXPostQuery(lx,q,function(txt) {console.log(txt);
			jsonData=$.parseJSON(txt);
			if(jsonData.Data!='error'){
				window.location=jsonData.Data;
			} else {
				$('#form-buttons-bar').show();
			}
		});
	
}
</script>
<?php } else {
	echo "<h1 align=center>Sorry, You do not have previlege to edit this Guide</h1>";
}
?>
