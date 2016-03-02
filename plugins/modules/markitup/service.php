<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(isset($_REQUEST["action"])) {
	loadModuleLib("markitup","api");
	switch ($_REQUEST["action"]) {
		case 'preview':
			if(!isset($_POST['data'])) $_POST['data']="";
			if(isset($_REQUEST['type'])) {
				echo showMarkitupPreview($_POST['data'],$_REQUEST['type']);
			} else {
				echo "Sorry, Type Not Defined";
			}
			break;
		case "template":
			
			break;
		default:
			# code...
			break;
	}
}
?>