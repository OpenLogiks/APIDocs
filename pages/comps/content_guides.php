<?php 
if(!defined('ROOT')) exit('No direct script access allowed');

$page=explode("/", $_REQUEST['page']);
if(count($page)<=1) $page[1]="";
if(isset($page[2]) && strlen($page[2])<=0) unset($page[2]);

switch($page[1]) {
	case "create":
		loadModule("guideEditor");
	break;
	case "edit":
		if(isset($page[2])) {
			$_REQUEST['refid']=$page[2];
			loadModule("guideEditor");
		} else {
			echo "Guide ID Not Defined";
		}
	break;

	case "":
		?>
		<div class="container-fluid">
			<?php if(session_check(false)) { ?>
			<div id='toolbar' class="text-right">
				<a href="<?=_link("guides/create")?>">Create</a>
			</div>
			<?php } ?>
			<div class="row">
		            <h1 class="title">
						<img class=logoimg src='<?=loadMedia("logos/logo-128.png")?>' alt='Logo Image' />
						<br/>
						Logiks GUIDES
					</h1>
		    </div>
		</div>
		<?php
	break;
	default:
		if(isset($page[2])) {
			loadModule("guides");
		} elseif(isset($page[1])) {
			$_REQUEST['category']=$page[1];
			loadModuleLib("guides","list");
		} else {
			?>
			<div class="container-fluid">
				<?php if(session_check(false)) { ?>
				<div id='toolbar' class="text-right">
					<a href="<?=_link("guides/create")?>">Create</a>
				</div>
				<?php } ?>
				<div class="row">
			            <h1 class="title">
							<img class=logoimg src='<?=loadMedia("logos/logo-128.png")?>' alt='Logo Image' />
							<br/>
							Logiks GUIDES
						</h1>
			    </div>
			</div>
			<?php
		}
		
}
?>