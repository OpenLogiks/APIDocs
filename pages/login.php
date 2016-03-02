<?php
define("WEBAPPROOT",SiteLocation.BASEPATH);

_css(explode(",", "bootstrap.min,font-awesome.min"));
_js(explode(",", "jquery,bootstrap.min"));

?>
<style>
body {
	padding-top: 40px;
	padding-bottom: 40px;
		margin:0px;
	background-color: #eee;
	box-shadow: inset -4px -3px 42px -3px #9B9B9B;
	-webkit-box-shadow: inset -4px -3px 42px -3px #9B9B9B;
	-moz-box-shadow: inset -4px -3px 42px -3px #9B9B9B;
	-o-box-shadow: inset -4px -3px 42px -3px #9B9B9B;
	-webkit-transition: all 0.5s ease;
	-moz-transition: all 0.5s ease;
	-o-transition: all 0.5s ease;
	transition: all 0.5s ease;
		height:100%;
}

.panel-heading {
    padding: 5px 15px;
}

.panel-footer {
	padding: 5px 15px;
	color: #A0A0A0;
	font-size: 12px;
}

.profile-img {
	width: 96px;
	height: 96px;
	margin: 0 auto 10px;
	display: block;
	-moz-border-radius: 50%;
	-webkit-border-radius: 50%;
	border-radius: 50%;
}
#errormsg {
	margin: 1.5rem 0;
	padding: .5rem .875rem;
	background: #f8f8dd;
	border: 1px solid #d3d952;
	border-radius: 3px;
	margin-top: -50px;
}
</style>
<div class="container fluid" style="margin-top:80px">
	<div class="row">
		<div class="col-sm-6 col-md-4 col-md-offset-4">
			<div id="errormsg" style="display:none;">Hello World</div>
		</div>
		<div class="col-sm-12 col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong> Sign in to continue</strong>
				</div>
				<div class="panel-body">
					<form role="form"  method="post" action="<?=_service("auth")?>" autocomplete='off'>
						<input name=onsuccess type=hidden value="<?=SiteLocation.SITENAME?>" />
						<input name=onerror type=hidden value="*" />
						<input name=site type=hidden value="<?=SITENAME?>" />
						<fieldset>
							<div class="row">
								<div class="center-block">
									<img class='profile-img' src="<?=loadMedia("images/loginUser.png")?>" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-10  col-md-offset-1 ">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-user"></i>
											</span> 
											<input class="form-control" placeholder="Username" name="userid" type="text" autofocus>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-lock"></i>
											</span>
											<input class="form-control" placeholder="Password" name="password" type="password" value="">
										</div>
									</div>
									<div class="form-group">
										<input type="submit" class="btn btn-lg btn-primary btn-block" value="Sign in">
									</div>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="panel-footer ">
					Don't have an account! <a href="<?=SiteLocation.SITENAME."/register"?>"> Sign Up Here </a>
					OR <a href="<?=SiteLocation.SITENAME."/home"?>">Go Home</a>
				</div>
            </div>
		</div>
	</div>
</div>
<script>
$(function() {
	$('input[name=userid]').val("");
	$('input[name=password]').val("");
	$('input[name=userid]').focus();
	
	<?php
		if(isset($errormsg)) echo "showError('$errormsg');";
	?>
});
function showError(msg) {
	$('#errormsg').hide();
	$('#errormsg').html(msg);
	$('#errormsg').fadeIn('slow').delay(5000).slideUp("slow");
}
</script>