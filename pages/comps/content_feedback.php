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

.jumbotron {
background: #039678;
color: #FFF;
border-radius: 0px;
}
.jumbotron-sm { padding-top: 5px;
padding-bottom: 5px; }
.jumbotron small {
color: #FFF;
}
.h1 small {
font-size: 24px;
}
textarea {resize:none;}
</style>
<div class="jumbotron jumbotron-sm">
     <div class="row" style='height:auto;'>
        <div class="col-sm-8 col-lg-8" style="margin: auto;float: none;text-align:left;">
            <h1 class="h1">
                Feed us back <small>Feel free to contact us</small></h1>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8" style='margin:auto;float: none;'>
            <div class="well well-sm">
                <form name="feedbackForm" id="feedbackForm" >
                <div class="row" style='height:auto;'>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">
                                Name</label>
                            <input type="text" class="form-control required" id="name" name="name" placeholder="Enter name" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="email">
                                Email Address</label>
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
                                </span>
                                <input type="email" class="form-control required" id="email" name="email" placeholder="Enter email" required="required" /></div>
                        </div>
                        <div class="form-group">
                            <label for="subject">
                                Subject</label>
                            <select id="subject" name="subject" class="form-control required" required="required">
                                <option value="APIDOCS Feedback">Feedback</option>
                                <option value="APIDOCS Complain">Complain</option>
                                <option value="APIDOCS Bugs">Bugs</option>
                                <!-- <option value="*" selected="">Choose One:</option>
                                <option value="service">General Customer Service</option>
                                <option value="suggestions">Suggestions</option>
                                <option value="product">Product Support</option>
                                <option value='others'>Others</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">
                                Message</label>
                            <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required"
                                placeholder="Message"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success pull-right" id="btnContactUs">
                            Send Message</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
	$('#btnContactUs').click(function(){
	saveFeedback();
	});

});
	function saveFeedback(){
	if(validateForm('#feedbackForm')){
		lx=getServiceCMD("feedback")+"&action=save-feedback&format=json";
		
		q="";
		$("#feedbackForm input, #feedbackForm textarea,#feedbackForm select").each(function() {
			if( $(this).attr("name") != null && $(this).attr("name").length > 0 ) {
				q += "&" + $(this).attr("name") + "=" + encodeURIComponent($(this).val());
				$(this).val("");
			}
		});
		processAJAXPostQuery(lx,q,function(txt) {
			
			jsonData=$.parseJSON(txt);
			if(jsonData.Data=='success'){
				alert("Feedback Send Successfully!");
			}
		});
	}else{
		alert("Please fill mandatory fields");
		
	}
}
</script>