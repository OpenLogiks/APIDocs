<?php
if(!defined('ROOT')) exit('No direct script access allowed');

$pages=explode("/",$_REQUEST['page']);
$pth=_link("");
?>
<ol class="breadcrumb"> 
	<i class="fa fa-home"></i>
	<a href="<?=$pth?>"> Home</a>
	<?php
		$max=count($pages)-1;
		if(isset($_COOKIE['PAGEPATH'])) {
			$oldPages=explode("/",$_COOKIE['PAGEPATH']);
			if(in_array($pages[$max],$oldPages)) {
				$pages=$oldPages;
			} else {
				setCookie("PAGEPATH",$_REQUEST['page'], time() + (86400 * 30), "/");
			}
		} else {
			setCookie("PAGEPATH",$_REQUEST['page'], time() + (86400 * 30), "/");
		}
		if(count($pages)>0 && $pages[0]!='home') {
			foreach($pages as $n=>$p) {
				if(strlen($p)<=0) continue;
				$pth.="/{$p}";
				$t=toTitle(_ling("MENU-".$p));
				if(strtoupper($t)==strtoupper("MENU-".$p)) {
					
					$t=explode("-",$p);
					if(is_numeric($t[count($t)-1])) {
						$t=array_splice($t,0,count($t)-1);
						$t=toTitle(implode("_",$t));
					} else {
						$t=toTitle($p);
					}
				}
				if($p==$pages[$max]) echo "<a class='active'>$t</a>";
				else echo "<a href='{$pth}'>$t</a>";
			}
		}
		
	?>
 </ol>
<ol class='footlink' style='float:right;margin-right:10px;'>
	<a href='<?=_link("about/license")?>'>License</a>
	<a href='<?=_link("about/terms")?>'>Usage Terms</a>
</ol>