<?php
$clz=array("primary","success","info","warning","danger");
$nx=rand(0,count($clz)-1);
?>
<!-- <div class="container-fluid">
	<div class="row">
        <div class='col-sm-8 col-lg-8' style='margin:auto;margin-top:50px;float:none;'>
        	<div class="panel panel-<?=$clz[$nx]?>">
        		<div class="panel-heading">
        			<img class=logoimg src='<?=loadMedia("logos/logo-128.png")?>' alt='Logo Image' height=32px />
					<strong style='font-size:20px'>Welcome To Logiks APIDOCS</strong>
        		</div>
				<div class="panel-body">
			    	
				</div>
			</div>
        </div>
    </div>
</div> -->
<?php if(session_check(false)) { ?>
<div id='toolbar' class="text-right">
	<a href="<?=_link("api/create")?>">New API</a>
	<a href="<?=_link("guides/create")?>">New Guide</a>
</div>
<?php } ?>
<h1 class="title">
	<img class=logoimg src='<?=loadMedia("logos/logo-128.png")?>' alt='Logo Image' />
	<br/>
	Logiks APIDOCS
</h1>