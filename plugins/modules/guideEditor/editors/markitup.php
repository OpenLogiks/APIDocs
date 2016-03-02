<?php
if(!defined('ROOT')) exit('No direct script access allowed');

loadModule("markitup");
loadMarkitupEditor("guides");
?>
<style>
textarea.markItUpEditor {
	width: 100%;min-height: 300px;
}
.markItUpHeader ul li {
	width: 20px;
	height: 20px;
	margin-top: -1px;
}
</style>
<script>
function initBaseEditor() {
	$('#guide_txt').markItUp(mySettings);
}
</script>