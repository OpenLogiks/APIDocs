<?php
if(!defined('ROOT')) exit('No direct script access allowed');
loadModuleLib('api','api');

if(isset($_REQUEST["action"])) {
  switch($_REQUEST["action"]) {
    case "fetch-list":
      if(isset($_REQUEST['category'])) {
        $api_list=getlist($_REQUEST['category']);
        printServiceMsg($api_list);
      } else {
        printServiceMsg(array());
      }
      break;
   case "fetch-category":
      $api_cat=getCategory();
      printServiceMsg($api_cat);
      break;
   case "search-list":
       if(isset($_REQUEST['q'])) {
          $api_list=searchList($_REQUEST['q']);
          printServiceMsg($api_list);
       } else {
          printServiceMsg(array());
       }
      break;
   case "create-api":
	  checkServiceSession();
      $status=saveApi();
      printServiceMsg($status);
      break;
   case "post-comment":
	  checkServiceSession();
	  if(isset($_REQUEST['api_id'])) {
       $status=saveComment();
       printServiceMsg($status);
	  }else{
	   printServiceMsg("error");
	  }
      break;
	case "add-example":
		checkServiceSession();
	  if(isset($_REQUEST['api_id']) || isset($_REQUEST['eg_id']) ) {
       $example=saveExample();
       printServiceMsg($example);
	  }else{
	   printServiceMsg("error");
	  }
      break;
	 case "check-title":
	  if(isset($_REQUEST['title']) || isset($_REQUEST['title']) ) {
       $status=checkTitle($_REQUEST['title']);
       printServiceMsg($status);
	  }else{
	   printServiceMsg("error");
	  }
      break;
	case "get-tags":
	  //if(isset($_REQUEST['tags']) || isset($_REQUEST['tags']) ) {
       $tags=getTags($_REQUEST['tags']);
       printServiceMsg($tags);
	  //}else{
	  // printServiceMsg("error");
	  // }
      break;
	case 'upload-tmp-file':
		checkServiceSession();
		if($_FILES['file']){
			$result=uploadTmpFile();
			echo json_encode($result);
		}
		break;
	case 'approve-api':
		checkServiceSession();
		if(isset($_REQUEST['apid'])) {
			$sql=_db()->_updateQ("api_toc",array(
					"approved"=>"true",
					"approved_on"=>date("Y-m-d H:i:s"),
					"approver"=>$_SESSION['SESS_USER_ID'],
					"userid"=>$_SESSION['SESS_USER_ID'],
					"dtoe"=>date("Y-m-d H:i:s"),
				),array("id"=>$_POST['apid']));
			$res=_dbQuery($sql);
			if($res) echo "success";
			else echo "Sorry, couldn't approve the API, try again later.";
		} else {
			printServiceErrorMsg(501,"API ID Missing");
		}
		break;
  }
} else { 
  printServiceErrorMsg("Sorry, Action Not Found");
}
/* Used to upload tmp files */
function uploadTmpFile() {
	//return $_FILES['file'];
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {	//check if this is an ajax request
		exit();
	}
	if(!isset($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
		exit('Something wrong with uploaded file, something missing!');
	}
	$date=date('Y-m-d H:i:s');
	$file_name = $_FILES['file']['name'];
	$filename  = $_FILES['file']['name'];
	$file_size = $_FILES['file']['size'];
	$file_src  = $_FILES['file']['tmp_name'];
	$file_type = $_FILES['file']['type'];
	//return $_POST;
		$time_stamp= time();
		$file_name = str_replace(' ','-',strtolower($file_name));
		$file_name = str_replace('(', '',strtolower($file_name));
		$file_name = str_replace(')', '',strtolower($file_name));
		$path_part = pathinfo($file_name);
		$file_ext  = $path_part['extension'];
		$file_name = $path_part['filename'];
		$file_new_name = $file_name.'-'.$time_stamp.'.'.$file_ext;
	
	$plain_path ='api_files/';
	$source_path=APPROOT.APPS_USERDATA_FOLDER.$plain_path;
	if (!file_exists($source_path)) {
    	mkdir($source_path, 0777, true);
	}	
	$file_dest_name = $source_path.$file_new_name;
	if(!move_uploaded_file( $file_src, $file_dest_name)){
		echo "failed to copy $filename src ".$file_src." dest ".$file_dest_name;
	}else{
		/*echo json_encode($_FILES['file']);*/
		$_FILES['file']['path']=loadMedia($plain_path.$file_new_name);
		//$_FILES['file']['path']=$plain_path.$file_new_name;
		return $_FILES['file'];
	}		
}
?>
