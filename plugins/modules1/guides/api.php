<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("searchList")) {
	/**
	* function searchList search for  guide category and returns the matching category
	* @param  $q |string 
    * @return array of category
	**/
	function searchList($q) {
		$q=_clean($q);
		$arrWhr=array(
				"title LIKE '%$q%'",
				"guide_group LIKE '%$q%'",
				"FIND_IN_SET('$q',tags)",
				//"summary LIKE '%$q%'",
				"guide_txt LIKE '%$q%'",

				"author='$q'",
				"type='$q'",
				"id='$q'",
			);
		
		$cols ="*,".getConfig("CATEGORY_GUIDES")." as category,".getConfig("SUBCATEGORY_GUIDES")." as subcategory";
		$whr  ="blocked='false'";
		$whr.=getAPIListWhere("api");

		$sql  = _db()->_selectQ('guides_tbl',$cols,$whr)." AND (".implode(" OR ", $arrWhr).")";
		$res  = _dbQuery($sql);
		$data = _dbData($res);
		return $data;
	}
	/**
	* function getlist returns the guide of given $type
	* @param  $type |string 
    * @return array of guide
	**/
	function getlist($type){
		$cols ="*,".getConfig("CATEGORY_GUIDES")." as category,".getConfig("SUBCATEGORY_GUIDES")." as subcategory";
		$whr  ="blocked='false' AND type='".$type."'";
		$whr.=getAPIListWhere("api");
		
		$sql  = _db()->_selectQ('guides_tbl',$cols,$whr);
		$res  = _dbQuery($sql);
		$data = _dbData($res);
		return $data;
	}
	/**
	* function getCategory returns the category of given $type
	* @param  $type |string 
    * @return array of guide
	**/
	function getCategory(){
		$cols =" DISTINCT(".getConfig("CATEGORY_GUIDES").") as category ";
		$whr  ="blocked='false' ";
		$whr.=getAPIListWhere("api");
		
		$sql  = _db()->_selectQ('guides_tbl',$cols,$whr);
		$res  = _dbQuery($sql);
		$data = _dbData($res);
		$categoryArray=array();
		$i=0;
		foreach($data as $d){
			$categoryArray[$i]=$d['category'];
			$i++;
		}
		return $categoryArray;
	}
	/**
	* function saveGuide insert/update guide into guides_tbl
	* @param  null 
	* @return success/error
	**/
	function saveGuide(){
		
		if($_REQUEST['id']=='0'){
			
			$guide = array(
				'title' 	  => '',
				'guide_group' => '',
				'tags'		  => '',
				'type'        => '',
				'summary'     => '',
				'guide_txt'   =>'',
				'author'	  =>"{$_SESSION['SESS_USER_NAME']} [{$_SESSION['SESS_USER_ID']}]",
				'creator'	  =>"{$_SESSION['SESS_USER_NAME']} [{$_SESSION['SESS_USER_ID']}]",
				'userid' 	  =>$_SESSION['SESS_USER_ID'],
				'approved'	  => 'false',
				'blocked' 	  => 'false',
				'dtoc' 		  => date('Y-m-d H:i:s'),
				'dtoe' 		  => date('Y-m-d H:i:s')
			);
			
			foreach($guide as $k => $v) {
				if(isset($_POST[$k])){
					$guide[$k] = trim($_POST[$k]);
				}
			}
			$sql = _db()->_insertQ1('guides_tbl',$guide);
			$res = _dbQuery($sql);
			$id=_db()->insert_id();
		_dbFree($res);
			if($res) {
				$subcategory=str_replace(" ","-" ,$_REQUEST[getConfig("SUBCATEGORY_GUIDES")]);
				$title=str_replace(" ","-" ,$_REQUEST['title']);
				
				$guideLink=_link("guides/").$_REQUEST[getConfig("CATEGORY_GUIDES")]."/".$subcategory.".".$title."-".$id;
				return $guideLink;
				return "success";
			} else {
				return "error";
			}
		}else{
			
			$guide = array(
				'title' 	  => '',
				'guide_group' => '',
				'tags'		  => '',
				'type'        => '',
				'status'      => 'draft',
				'summary'     => '',
				'guide_txt'   =>'',
				'author'	  =>"{$_SESSION['SESS_USER_NAME']} [{$_SESSION['SESS_USER_ID']}]",
				'approved'	  => 'false',
				'userid' 	  =>$_SESSION['SESS_USER_ID'],
				'dtoe' 		  => date('Y-m-d H:i:s')
			);
			
			foreach($guide as $k => $v) {
				if(isset($_POST[$k])){
					$guide[$k] = trim($_POST[$k]);
				}
			}
			$whr="md5(id)='".$_REQUEST['id']."'";
			$sql = _db()->_updateQ('guides_tbl',$guide,$whr);
			$res = _dbQuery($sql);
		_dbFree($res);
		if($res) {
			$cols="id";
			$whr="md5(id)='".$_REQUEST['id']."'";
			$sql = _db()->_selectQ('api_toc',$cols,$whr);
			$res = _dbQuery($sql);
			$data = _dbFetch($res);
			
			$subcategory=str_replace(" ","-" ,$_REQUEST[getConfig("SUBCATEGORY_GUIDES")]);
			$title=str_replace(" ","-" ,$_REQUEST['title']);
			
			$guideLink=_link("guides/").$_REQUEST[getConfig("CATEGORY_GUIDES")]."/".$subcategory.".".$title."-".$data['id'];
			return $guideLink;
			
		} else {
			return "error";
		}
			
		}
	}
	/**
	* function getGuideDetails returns the array of guide details of given id
	* @param  $id |md5() 
	* @return success/error
	**/
	function getGuideDetails($id){
		$cols =" *,guides_tbl.type as category,guides_tbl.guide_group as subcategory";

		$whr  ="blocked='false' AND md5(id)='".$id."'";
		$sql  = _db()->_selectQ('guides_tbl',$cols,$whr);
		$res  = _dbQuery($sql);
		$data = _dbFetch($res);
		if(isset($data['author'])) $data['authorid']=extractUserID($data['author']); else $data['authorid']="";
		if(isset($data['creator'])) $data['creatorid']=extractUserID($data['creator']); else $data['creatorid']="";
		if($data['authorid']==$_SESSION['SESS_USER_ID'] || $data['creatorid']==$_SESSION['SESS_USER_ID']) {
			$data['editable']="true";
			$data['edit_url']=_link("guides")."/edit/".md5($data['id']);
		} elseif(checkUserRoles("guides","Allow Editing All Guides")) {
			$data['editable']="true";
			$data['edit_url']=_link("guides")."/edit/".md5($data['id']);
		} else{
			$data['editable']="false";
		}
		if($data['status']=="draft") {
			if($data['authorid']==$_SESSION['SESS_USER_ID'] || $data['creatorid']==$_SESSION['SESS_USER_ID']) {
				$data['viewable']=true;
			} else {
				$data['viewable']=false;
			}
		} else {
			$data['viewable']=true;
		}
		return $data;
	}
	
}
?>
