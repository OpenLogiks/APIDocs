<?php
if(!defined('ROOT')) exit('No direct script access allowed');
loadModuleLib('guides','api');
if(isset($_REQUEST["action"])) {
  switch($_REQUEST["action"]) {
    case "fetch-list":
      if(isset($_REQUEST['category'])) {
        $guides_list=getlist($_REQUEST['category']);
        printServiceMsg($guides_list);
      } else {
        printServiceMsg(array());
      }
      break;
	 case "fetch-category":
      $guides_cat=getCategory();
      printServiceMsg($guides_cat);
      break;
    case "search-list":
       if(isset($_REQUEST['q'])) {
          $api_list=searchList($_REQUEST['q']);
          printServiceMsg($api_list);
       } else {
          printServiceMsg(array());
       }
      break;
	case "create-guide":
	  checkServiceSession();
      $status=saveGuide();
      printServiceMsg($status);
      break;
    case "publish-guide":
		checkServiceSession();
		if(isset($_POST['gid'])) {
			$sql=_db()->_updateQ("guides_tbl",array(
					"status"=>"published",
					"approved"=>"false",
					"userid"=>$_SESSION['SESS_USER_ID'],
					"dtoe"=>date("Y-m-d H:i:s"),
				),array("id"=>$_POST['gid']));
			$res=_dbQuery($sql);
			if($res) echo "success";
			else echo "Sorry, couldn't publish your article, try again later.";
		} else {
			printServiceErrorMsg(501,"GID Missing");
		}
      break;
    case "approve-guide":
        checkServiceSession();
		if(isset($_REQUEST['gid'])) {
			$sql=_db()->_updateQ("guides_tbl",array(
					"approved"=>"true",
					"approved_on"=>date("Y-m-d H:i:s"),
					"approver"=>$_SESSION['SESS_USER_ID'],
					"userid"=>$_SESSION['SESS_USER_ID'],
					"dtoe"=>date("Y-m-d H:i:s"),
				),array("id"=>$_POST['gid']));
			$res=_dbQuery($sql);
			if($res) echo "success";
			else echo "Sorry, couldn't approve your article, try again later.";
		} else {
			printServiceErrorMsg(501,"GID Missing");
		}
      break;
  }
} else { 
  printServiceErrorMsg("Sorry, Action Not Found");
}
?>
