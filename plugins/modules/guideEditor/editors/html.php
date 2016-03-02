<?php
if(!defined('ROOT')) exit('No direct script access allowed');

loadModule("editor");
loadEditor("cleditor");
?>
<script>
function initBaseEditor() {
	loadEditor("#guide_txt");
}
</script>