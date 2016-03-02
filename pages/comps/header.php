<?php
if(!defined('ROOT')) exit('No direct script access allowed');

$sql="SELECT * FROM "._dbTable("links")." WHERE menuid='header' AND (site='".SITENAME."' OR site='*') AND blocked='false' AND onmenu='true' AND (device='*')";
$res=_dbQuery($sql);
if($res) {
	$menuData=_dbData($res);
	_dbFree($res);
} else {
	$menuData=array();
}
?>
<style>
.hea-navbar .dropdown-menu {
	margin-left: -150px;
	padding-top: 5px;padding-bottom: 5px;
	right: 0px;left: auto;
}
.hea-navbar .dropdown-menu li {
	display: block !important;
	float:none !important;
}
.hea-navbar .dropdown-menu a {
	padding-bottom: 0px;
	padding-top: 0px;
	width: 100%;
	line-height: 30px;
}
#header .toggle {display: none;}
</style>
<div class="toggle"><a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars"></i></a></div>
<a class='logoLink' href='<?=_link("")?>'><div class="logo">Logiks APIDOCS</div></a>
<div class="hea-navbar">
	  <ul>
		<?php
			$pg=$_REQUEST['page'];
			$pg=explode("/",$pg);
			if($pg[0]=="home") $pg[0]="api";
			foreach($menuData as $n=>$a) {
				$cls=$a['class'];
				if($n==0) $cls.=" first";
				$cls=trim($cls);
				if(strlen($a['iconpath'])>0) {
					$icon=loadMedia($a['iconpath']);
					$icon="<img src='$icon' />";
				} else $icon="";
				if($a['link']==$_REQUEST['page'] || $a['link']==$pg[0]) $cls.=" active";
				$a['link']=_link($a['link']);
				$a['link']=str_replace("//","/",$a['link']);
				$a['link']=str_replace(":/","://",$a['link']);
				echo "<li class='{$cls}' title='{$a['tips']}' ><a href='{$a['link']}'>{$icon}{$a['title']}</a></li>";
			}
		?>
		<li class="dropdown">
          <?php if(session_check(false)) { ?>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					<span class="fa fa-gear" style="font-size:19px;">
						<citie style='font-size:14px;'><?=current(explode(" ",$_SESSION['SESS_USER_NAME']))?></citie>
					</span>
				</a>
           <?php } else { ?>
           		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					<span class="fa fa-unlock-alt" style="font-size:19px;"></span>
				</a>
           <?php } ?>
          
          <ul class="dropdown-menu" role="menu" style='z-index: 9999999;'>
          	<?php if(session_check(false)) { ?>
          		<li><a href="<?=SiteLocation."logout.php?relink="._link("home")?>">Logout</a></li>
          	<?php } else { ?>
	          	<li><a href="<?=_link("login")?>">Login</a></li>
	            <li><a href="<?=_link("register")?>">Register</a></li>
          	<?php } ?>
          </ul>
        </li>
	 </ul>
 </div>
<script>
$(function() {
	if($("#sidebar").length>0) {
		$("#header .toggle").show();
	}
});
</script>
