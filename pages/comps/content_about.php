<style>
#wrapper {
	padding-left: 0px;
}
#wrapper #footer {
	left:0px;
}
</style>
<div class="container-fluid">
	<div class='articleContent'>
		<?php
			//loadModule("test");exit();

			$page=explode("/", $_REQUEST['page']);
			if(isset($page[count($page)-1]) && strlen($page[count($page)-1])<=0) unset($page[count($page)-1]);
			if(count($page)<=1) $page[1]="about";
			loadModule("content");
			$title=printContent($page[1]);
			if($title==null) {
				include APPROOT.APPS_PAGES_FOLDER."error.php";
			} else {
				if($page[1]=="about") {
					loadWidget("socialbar");
				}
			}
		?>
	</div>
</div>