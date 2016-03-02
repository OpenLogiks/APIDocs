<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(isset($_REQUEST["action"])) {
  switch($_REQUEST["action"]) {
    case "save-feedback":
        $status=saveFeedback();
        printServiceMsg($status);
      break;
  }
} else { 
  printServiceErrorMsg("Sorry, Action Not Found");
}
function saveFeedback(){
	
			$feedback = array(
				'name'	 => '',
				'email'	 => '',
				'subject'=> '',
				'message'=>'',
				'userid' =>$_SESSION['SESS_USER_ID'],
				'blocked'=> 'false',
				'dtoc'   => date('Y-m-d H:i:s'),
				'dtoe' 	 => date('Y-m-d H:i:s')
			);
			
			foreach($feedback as $k => $v) {
				if(isset($_POST[$k])){
					$feedback[$k] = trim($_POST[$k]);
				}
			}
		$sql = _db()->_insertQ1('feedbacks',$feedback);
		$res = _dbQuery($sql);
		$id=_db()->insert_id();
		
		if($res) {
			_dbFree($res);
			//send email to admin
		$mailto = "dawkharrupali@gmail.com";
		$mailfrom  = "admin@openlogiks.com";						
		
		$feedback_content=array(
			"name"=>$_REQUEST['name'],
			"email"=>$_REQUEST['email'],
			"subject"=>$_REQUEST['subject'],
			"message"=>$_REQUEST['message'],
			);
		
		$template  = _template("feedback_admin",$profile);
		
		loadHelpers('email');
		sendMail($mailto," Feedback",$template,$mailfrom);
			return "success";
		}else{
			return "error";
		}
}
?>