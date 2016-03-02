<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("showMarkitupPreview")) {
	function showMarkitupPreview($txtData,$type="markitup") {
		$htmlData="";
		switch ($type) {
			case 'markitup':
				require dirname(__FILE__)."/Michelf/Markdown.inc.php";
				//require dirname(__FILE__)."/Michelf/MarkdownExtra.inc.php";

				$htmlData = Markdown::defaultTransform($txtData);
			break;
			default:
				$htmlData = $txtData;
			break;
		}
		return $htmlData;
	}
	function getMarkitupParsers() {
		return array("html"=>"HTML","markitup"=>"MarkitUp");
	}
}
?>