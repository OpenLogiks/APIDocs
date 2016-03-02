<?php
$arrSocial=loadFeature("socials");
?>
<style>
#content .lgks-widget[name=socialbar] {
	text-align: center;
}
#content .lgks-widget[name=socialbar] .fa {
	font-size: 2em;
	text-decoration: none;
	margin: 10px;
}
#content .lgks-widget[name=socialbar] .fa:hover {
	color: orange;
}
</style>
<div class="social_list social">
    <div class="hi-icon-wrap hi-icon-effect-5 hi-icon-effect-5c">
    	<?php
    		foreach ($arrSocial as $key => $lnk) {
    			if(strlen($lnk)>0) {
    				echo "<a href='$lnk' target='_blank' class='icon fa fa-{$key} animated fadeInLeft'></a>";
    			}
    		}
    	?>
    </div>
</div><!--/.social_list-->