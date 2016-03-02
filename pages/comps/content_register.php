<?php
if(!defined('ROOT')) exit('No direct script access allowed');

_js(array("validator"));
?>
<style>
#wrapper {
	padding-left: 0px;
}
#wrapper #footer {
	left:0px;
}
.registraion{
	margin:0px;
	padding:0px;
	max-width: 380px;
	margin: auto;
	margin-top: 50px;
}
.form-signin {
  margin: 0 auto;
  background-color: #fff;
  
	}
  .form-signin-heading,
	.checkbox {
	  margin-bottom: 30px;
	
	}

	.form-signin .checkbox {
	  font-weight: normal;
	}

	.form-control {
	  position: relative;
	  font-size: 16px;
	  height: auto;
	  padding: 10px;
		@include box-sizing(border-box);

		&:focus {
		  z-index: 2;
		}
	}

	input[type="text"] {
	  margin-bottom: -1px;
	  border-bottom-left-radius: 0;
	  border-bottom-right-radius: 0;
	}

	input[type="password"] {
	  margin-bottom: 20px;
	  border-top-left-radius: 0;
	  border-top-right-radius: 0;
	}
}
</style>
<div class="container-fluid">
	         <div class="row">
              <div class="registraion">
                        
                        <div class="panel panel-default">
                            <div class="panel-heading">
                        <strong>   Register </strong>  
                            </div>
                            <div class="panel-body">
								<div class="alert alert-success" style="display:none;" id="msg_box">
							
								<span id="error_message"></psan>
							</div>
                                <form role="form" method="post" name="registrationForm" id="registrationForm" class="form-signin required" onsubmit="return validateForm('#registrationForm');">
									<br>
										<div class="form-group input-group">
                                            <span class="input-group-addon">@</span>
                                            <input type="email" name="email" id="email" class="form-control emailfield required " placeholder="Your Email" required>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" name="name" id="name" class="form-control required" placeholder="Your Name" required>
                                        </div>
                                        
                                         
                                      <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input type="password" name="password" id="password" class="form-control required" placeholder="Enter Password" required>
                                        </div>
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input type="password" name="cpassword" id="cpassword" class="form-control required" placeholder="Retype Password" required>
                                        </div>
                                     
                                     
                                    <input type="submit"  class="btn btn-success" style='float:right;'  value="Register Me">
									<br/><hr>
                                    Already Registered ?  <a href="<?=_link("login")?>">Login here</a>
                                    OR <a href="<?=_link("home")?>">Go Home</a>
                                    </form>
                            </div>
                           
                        </div>
            </div>
    </div>
</div>
<script>
	$(function() {
	$('#registrationForm').submit(function(e){	
		saveUser();
		return false;
	});

});
	
	function saveUser(){
		//var emailcheck=checkmail($('#email').val());
		//alert(emailcheck);
		//return false;
		if(validateForm('#registrationForm')){
			data  = $('#registrationForm #password').val().trim();
			data1 = $('#registrationForm #cpassword').val().trim();
			if(data != data1){
				$('#cpassword').focus();
				$('#error_message').html('Passwords should be equal!');
				$('#msg_box').show();
				return false;
			}else{
		
				lx=getServiceCMD("register")+"&action=save-user&format=json";
				
				q="";
				$("#registrationForm input").each(function() {
					if( $(this).attr("name") != null && $(this).attr("name").length > 0 ) {
						q += "&" + $(this).attr("name") + "=" + encodeURIComponent($(this).val());
						$(this).val("");
					}
				});
				processAJAXPostQuery(lx,q,function(txt) {
					jsonData=$.parseJSON(txt);
					if(jsonData.Data==true){
						$('#error_message').html('You Are Registered Successfully!');
						$('#msg_box').show();
					}else{
						$('#error_message').html(jsonData.Data);
						$('#msg_box').show();
					}
				});
			}
		}else{
			$('#error_message').html("Please Fill Form Properly.");
			$('#msg_box').show();
		}
	
}
</script>