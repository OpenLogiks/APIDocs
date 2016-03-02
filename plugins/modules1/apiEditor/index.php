<?php
if(!defined('ROOT')) exit('No direct script access allowed');
session_check(true);

_css(array("jquery.tagit"));
_js(array("jquery.tagit","validator","jquery.form.min"));

loadHelpers('uicomponents');
loadModule("editor");
loadEditor("cleditor");

if(isset($_REQUEST['refid'])) {
	loadModuleLib('api','api');
	$apiDetails=getApiDetails($_REQUEST['refid']);
} else {
	$apiDetails['editable']=true;
}
if($apiDetails['editable']=='true') {
?>
<style>
.cleditorMain {
	height: auto !important;
}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">

			<form  name="apiForm" id="apiForm" class='apiForm' onsubmit="return validateForm('#apiForm');">
				<input type="hidden" name="id" id="id" value="<?php if(isset($apiDetails['id'])){echo md5($apiDetails['id']); }else{echo 0;} ?>" >
				
				<div class="form-group">
					<label>API  Title  *</label> <small>( only name )</small>
					<input type="text" class="form-control required " name="title" id="title" value="<?php if(isset($apiDetails['title'])){echo $apiDetails['title']; } ?>" placeholder="e.g. _dbQuery"  required>
					
				</div>

				<div class="form-group">
					<label>Category *</label>
					<select class="form-control required" name="lgks_type" id="lgks_type" value="<?php if(isset($apiDetails['lgks_type'])) echo $apiDetails['lgks_type']; ?>" required>
						<?=createDataSelector(_db(),"api_category",false);?>
					</select>
				</div>
				
				<div class="form-group">
					<label>Tags</label>
					<input class="form-control tagfield" name="tags" id="tags" value="<?php if(isset($apiDetails['tags'])){echo $apiDetails['tags']; } ?>" >
				</div>
				<div class="form-group">
					<label>Defination *</label>
					<textarea class="form-control required" name="defination" id="defination" placeholder="e.g. _dbQuery($q,$sys=false)" required><?php if(isset($apiDetails['defination'])){echo $apiDetails['defination']; } ?></textarea>
				</div>

				<div class="form-group">
					<label>Type *</label>
					<select class="form-control required" name="obj_type" id="obj_type" value="<?php if(isset($apiDetails['obj_type'])) echo $apiDetails['obj_type']; ?>" required>
						<?=createDataSelector(_db(),"obj_type");?>
					</select>
				</div>
				<hr/>

				<div class="form-group">
					<label>Source Language *</label>
					
					<select class="form-control required" name="src_lang" id="src_lang" value="<?php if(isset($apiDetails['src_lang'])){echo $apiDetails['src_lang'];}else{echo 'PHP';} ?>" required>
						<?=createSelectorFromListFile("lookups/language.dat");?>
					</select>
				</div>
				<div class="form-group">
					<label>Source Path *</label> <small>(Relative path of file)</small>
					<input type="text" class="form-control required" name="src_path" id="src_path" value="<?php if(isset($apiDetails['src_path'])){echo $apiDetails['src_path']; } ?>" placeholder="e.g. api/helpers/shortfuncs.php " required>
				</div>
				<div class="form-group">
					<label>Source Name *</label> <small>(Name For module/library/helper)</small>
					<input type="text" class="form-control required" name="src_name" id="src_name" value="<?php if(isset($apiDetails['src_name'])){echo $apiDetails['src_name']; } ?>" placeholder="e.g. shortfuncs" required>
				</div>
				<div class="form-group">
					<label>Source Checkout</label> <small>(Referance URL)</small>
					<input type="url" class="form-control" name="src_checkout" id="src_checkout" value="<?php if(isset($apiDetails['src_checkout'])){echo $apiDetails['src_checkout']; } ?>" placeholder="e.g. https://github.com/Logiks/Logiks-Core/blob/master/api/helpers/shortfuncs.php#L66-75" >
				</div>

				<hr/>

				<div class="form-group">
					<label>Short Description *</label> <small>(function description)</small>
					<textarea class="form-control required" rows="3" name="descs_short" id="descs_short" required><?php if(isset($apiDetails['descs_short'])){echo strip_tags($apiDetails['descs_short']); } ?></textarea>
				</div>
				<div class="form-group">
					<label>Long Description *</label> <small>(function parameters.return type description)</small>
					<a id="showHelp" class='fa fa-question-circle' style="cursor:pointer;float:right;font-size: 12px;line-height: 26px;">  Help <i fa  fa-question></i></a>
					<textarea class="form-control required" rows="3" name="descs_long" id="descs_long" required><?php if(isset($apiDetails['descs_long'])){echo $apiDetails['descs_long']; } ?></textarea>
				</div>
										
			
				<div class="form-group" id="descriptionHelp" style="display:none;">
					<img src="<?=loadMedia('images/text-help.png')?>" style='max-width:100%;width:100%;' />
				</div>
				<hr/>
				
				<div class="form-inline">
					<div class="form-group">
						<label>Version Dependencies</label>
						
						<select class="form-control " name="min_vers" id="min_vers" value="<?php if(isset($apiDetails['min_vers'])){echo $apiDetails['min_vers']; }else{ echo "*";} ?>">
							<?=createDataSelector(_db(),"versions",false);?>
						</select>
						<div class="form-group">
							<select class="form-control " name="max_vers" id="max_vers" value="<?php if(isset($apiDetails['max_vers'])){ echo $apiDetails['max_vers']; }else{ echo "*";}?>">
								<?=createDataSelector(_db(),"versions",false);?>
							</select>
						</div>
					</div>
				</div>

				<br/>
				<div class="form-inline">
					<div class="form-group">
						<label>Logiks Package</label>

						<select class="form-control" name="package_id" id="package_id" value="<?php if(isset($apiDetails['package_id'])){echo $apiDetails['package_id']; } ?>">
							<?=createDataSelector(_db(),"packages");?>
						</select>
					</div>
				</div>
				
				<div id='form-buttons-bar' class="form-group form-buttons">
					<button type="reset" class="btn btn-danger" id="cancel">Cancel</button>
					<button type="submit" class="btn btn-default" id="submitApi">Submit</button>
				</div>
			</form>
			<div style="display:none">
					<form id='api_upload_form' method="post" enctype="multipart/form-data" action="<?=SiteLocation?>services/?scmd=api&site=<?=SITENAME?>&action=upload-tmp-file">
						<input type='hidden' id='upload_tmp_api' name='api_id'/>
						<input type='file' id='upload_tmp_photo'  name='file' multiple />
						<div id='output' style="display:none">Output</div>
					</form> 
			</div>
		</div>
	</div>
</div>
<script>
var editor,cle;
var files, 
		option={ target:'#output',beforeSubmit:beforeSubmit,success:afterSuccess,resetForm:true };	
$(function(){
	
	$("select[value]").each(function() {
		$(this).val($(this).attr("value"));
	});
	$(".tagfield").tagit();
	$('#showHelp').click(function(){
		$('#descriptionHelp').toggle();
	});
	$('#apiForm').submit(function(e){
		
		e.preventDefault();
		saveApi();
		return false;
	});
	$('#cancel').click(function(){
			window.history.back();
		});
	$("#src_path").blur(function() {
		src=$("#src_path").val();
		if(src.length>0) src=src.split("/");
		src=src[src.length-1];
		src=src.split(".");
		$("#src_name").val(src[0]);
	});
	//loadEditor("#descs_long");
	cle=$("#descs_long").cleditor({
			width: '100%',
            height: '99%',
            controls: "bold italic underline strikethrough subscript superscript | size " +//font 
					"style | color highlight removeformat | bullets numbering | outdent " +
					"indent | alignleft center alignright justify | undo redo | " +
					"rule image link unlink | cut copy paste pastetext | print source",
			fonts:"Arial, Arial Black, Helvetica, sans-serif",
			sizes:"1,2,3,4,5,6,7",
			styles:[["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
					  ["Header 3", "<h3>"], ["Header 4", "<h4>"], ["Header 5", "<h5>"],
					  ["Header 6", "<h6>"]],
		});
	cle[0].updateFrame(cle,true);
	// Image button
	$.cleditor.buttons.image = {
			name: 'image',
			title: 'Insert/Upload Image',
			command: 'insertimage',
			popupName: 'image',
			popupClass: 'cleditorPrompt',
			stripIndex: $.cleditor.buttons.image.stripIndex,
			popupContent: 'Please configure imageListUrl',
			buttonClick: insertImageClick,
			uploadUrl: 'imageUpload',
			imageListUrl: 'imagesList'
		};
	//getTags();
	$('#upload_tmp_photo').change(function(){
		
		$('#api_upload_form').ajaxSubmit(option);
	});
});
	
	function saveApi(){
			lx=getServiceCMD("api")+"&action=create-api&format=json";
			var  q="";
			$("#apiForm input, #apiForm textarea ,#apiForm select").each(function() {
				if( $(this).attr("name") != null && $(this).attr("name").length > 0 ) {
					q += "&" + $(this).attr("name") + "=" + encodeURIComponent($(this).val());
					//$(this).val("");
				}
			});
		
			$('#form-buttons-bar').hide();
		
			processAJAXPostQuery(lx,q,function(txt) {
				jsonData=$.parseJSON(txt);
				if(jsonData.Data!='error'){
					window.location=jsonData.Data;
				} else {
					$('#form-buttons-bar').show();
				}
			});
	}
	
	function getTags(){
		var l = getServiceCMD('api','get-tags');
		
		processAJAXQuery(l,function(data){
			try {
				var json = $.parseJSON(data), tagclue = [];
				if(typeof json.Data == 'object') {
					json = json.Data;
					$.each(json,function(itm,val){
						tagclue.push(val);
					});
					console.log(tagclue);				
					$(".tagfield").tagit({
						availableTags : tagclue
					});
				}
			} catch (e) {
				console.error(e.message);
			}
		});
		
	}
/* Used after image submit */
function afterSuccess(data){
	
	data=$.parseJSON(data);
	var fsize=bytesToSize(data.size,0);
	var str='<li class="" rel="'+data.tmp_name+'" size="'+data.size+'">';
	str+='<div class="photos" title="'+data.name+'"  style="background-image: url('+data.path+');"></div>';
	
	$('#create_class_photos').show();
	$('#create_class_photos').append(str);
	editor.execCommand('insertimage', data.path, null,'div.cleditorButton');
}
/* Used before image submit */
function beforeSubmit(){
	if (window.File && window.FileReader && window.FileList && window.Blob) {
		if( !$('#upload_tmp_photo').val()) {
			alert("Are you kidding me?");
			return false
			}
		var fsize, ftype;
		fsize = $('#upload_tmp_photo')[0].files[0].size;
		ftype = $('#upload_tmp_photo')[0].files[0].type;
		if(fsize>1048576){
			alert( "<b>"+bytesToSize(fsize,0) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
			return false
				}
	}else{
		alert( "Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}
}
/* Function to cal photo size. */
function bytesToSize(bytes, precision){  
    var kilobyte = 1024;
    var megabyte = kilobyte * 1024;
    var gigabyte = megabyte * 1024;
    var terabyte = gigabyte * 1024;
    if ((bytes >= 0) && (bytes < kilobyte)) {
        return bytes + ' B'; 
    } else if ((bytes >= kilobyte) && (bytes < megabyte)) {
        return (bytes / kilobyte).toFixed(precision) + ' KB';
    } else if ((bytes >= megabyte) && (bytes < gigabyte)) {
        return (bytes / megabyte).toFixed(precision) + ' MB'; 
    } else if ((bytes >= gigabyte) && (bytes < terabyte)) {
        return (bytes / gigabyte).toFixed(precision) + ' GB'; 
    } else if (bytes >= terabyte) {
        return (bytes / terabyte).toFixed(precision) + ' TB'; 
    } else {
        return bytes + ' B';
    }
}
	/* Used to insert image */
function insertImageClick(e, data) {
	//createDlg($.cleditor.buttons.image.imageListUrl, $.cleditor.buttons.image.uploadUrl, data);
	editor=data.editor;
	$('#upload_tmp_photo').click();
}
</script>
<?php } else {
	echo "<h1 align=center>Sorry, You do not have previlege to edit this API</h1>";
}
?>
