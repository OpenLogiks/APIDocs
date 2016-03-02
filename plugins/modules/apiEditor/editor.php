<?php
_js("jquery.form.min");
?>
<div style="display:none">
	<form id='api_upload_form' method="post" enctype="multipart/form-data" action="<?=SiteLocation?>services/?scmd=api&site=<?=SITENAME?>&action=upload-tmp-file">
		<input type='hidden' id='upload_tmp_api' name='api_id'/>
		<input type='file' id='upload_tmp_photo'  name='file' multiple />
		<div id='output' style="display:none">Output</div>
	</form> 
</div>
<script>
var cle,option={ target:'#output',beforeSubmit:beforeSubmit,success:afterSuccess,resetForm:true }

function loadCleditor() {
	loadEditor("#descs_long");
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
	$('#upload_tmp_photo').change(function(){
		$('#api_upload_form').ajaxSubmit(option);
	});
}
</script>