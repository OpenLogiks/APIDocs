<?php
$arrCfg=loadFeature("facebookLike");
if(isset($arrCfg['APP-ID'])) {
?>
<div class="fb-like" data-href="<?=SiteLocation.$_SERVER['REQUEST_PATH']?>" data-layout="<?=$arrCfg['DATA-LAYOUT']?>" 
	data-action="<?=$arrCfg['DATA-ACTION']?>" data-show-faces="<?=$arrCfg['DATA-SHOWFACES']?>" data-share="<?=$arrCfg['DATA-SHARE']?>" style='margin-top: 6px;'></div>
<div id='fb-root'></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?=$arrCfg['APP-ID']?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php
} else {
	echo "<span class='errorMsg' style='color:red;'>Facebook Feature Not Found</span>";
}
?>