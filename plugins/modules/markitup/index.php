<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("loadMarkitupEditor")) {
	function loadMarkitupEditor($sets="markdown") {
		$webpath=getWebpath(__FILE__);
		$editorSets=explode(",", $sets);
		echo "<link rel='stylesheet' type='text/css' href='{$webpath}markitup/skins/markitup/style.css'>";
		echo "<script type='text/javascript' src='{$webpath}markitup/jquery.markitup.js'></script>";

		foreach ($editorSets as $set) {
			echo "<link rel='stylesheet' type='text/css' href='{$webpath}markitup/sets/$set/style.css'>";
			echo "<script type='text/javascript' src='{$webpath}markitup/sets/$set/set.js'></script>";
		}
	}
}
?>
<style>
.markItUpEditor {
	background-position-x: -50px !important;
	padding: 5px 5px 5px 5px !important;
}
</style>