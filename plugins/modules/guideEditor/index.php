<?php
if(!defined('ROOT')) exit('No direct script access allowed');
session_check(true);

_css(array("jquery.tagit"));
_js(array("jquery.tagit","validator"));

loadHelpers('uicomponents');

$editor="html";
if(isset($_REQUEST['refid'])) {
	loadModuleLib('guides','api');
	$guideDetails=getGuideDetails($_REQUEST['refid']);

	$editor=$guideDetails['guide_parser'];
	if(isset($_REQUEST['editor']) && strlen($_REQUEST['editor'])>0) {
		$editor=$_REQUEST['editor'];
	}
} else {
	$guideDetails['editable']="true";

	if(isset($_REQUEST['editor']) && strlen($_REQUEST['editor'])>0) {
		$editor=$_REQUEST['editor'];
	} elseif(isset($_COOKIE['GUIDE-EDITOR']) && strlen($_COOKIE['GUIDE-EDITOR'])>0) {
		$editor=$_COOKIE['GUIDE-EDITOR'];
	}
	setcookie('GUIDE-EDITOR',$editor,mktime(). time()+60*60*24*30,'/');
}
if($guideDetails['editable']=='true') {
	$fEditor=dirname(__FILE__)."/editors/{$editor}.php";
	if(!file_exists($fEditor)) {
		$fEditor=dirname(__FILE__)."/editors/html.php";
	}
	include_once $fEditor;
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
select#changeEditor {
	float: right;
	width: 120px;
	font-size: 12px;
	color: #555;
	background-color: #fff;
	background-image: none;
	border: 1px solid #ccc;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
	-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	padding: 4px;
	margin-top: -4px;
}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">

			<form role="form" name="guideForm" id="guideForm" class='apiForm' onsubmit="return validateForm('#guideForm');">
				<input type="hidden" name="guide_parser" id="guide_parser" value="<?=$editor?>" required>

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
					<select id='changeEditor'>
						<option value=''>Change Editor</option>
							<option value='html'>HTML</option>
							<option value='markitup'>Markitup</option>
					</select>
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
	$("#changeEditor").change(function() {
		changeEditor(this.value);
	});
	initBaseEditor();
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
function changeEditor(editor) {
	window.location=_link(PAGE)+"?editor="+editor;
}
</script>
<?php } else {
	echo "<h1 align=center>Sorry, You do not have previlege to edit this Guide</h1>";
}
?>
