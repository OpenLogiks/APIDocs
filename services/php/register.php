<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(isset($_REQUEST["action"])) {
  switch($_REQUEST["action"]) {
    case "save-user":
        $status=register();
        printServiceMsg($status);
      break;
  }
} else { 
  printServiceErrorMsg("Sorry, Action Not Found");
}
function register(){
	$check=checkUserID($_POST['email']);
	if(!$check){
		$user_id=$_REQUEST['email'];
		$previlage=getConfig("USER_REGISTER_PRIVILEGE");
		$access=getConfig("USER_REGISTER_ACCESS");
		$password=$_REQUEST['password'];
		unset($_POST['password']);
		
		$createUser=createUser($user_id,$previlage,$access,$password,$_POST);
		if($createUser){
			return $createUser;
		}else{
			return $createUser;
		}
	}else{
		return "You Are Already Registered With Us";
		
	}
		
}
?>