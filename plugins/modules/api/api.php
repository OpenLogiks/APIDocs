<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("searchList")) {
	/**
	* function searchList search for  api category and returns the matching category
	* @param $q |string
	* @return $data |array
	**/
	function searchList($q){
		$q=_clean($q);
		$arrWhr=array(
				"title LIKE '%$q%'",
				"src_path LIKE '%$q%'",
				"descs_short LIKE '$q'",
				"FIND_IN_SET('$q',tags)",
				"package_id='$q'",
			);
		
		$cols =" *,".getConfig("SUBCATEGORY_API")." as subcategory,".getConfig("CATEGORY_API")." as category";
		$whr  ="blocked='false'";
		$whr.=getAPIListWhere("api");
		
		$sql  = _db()->_selectQ('api_toc',$cols,$whr)." AND (".implode(" OR ", $arrWhr).")";
		$res  = _dbQuery($sql);
		$data = _dbData($res);
		return $data;
	}
	/**
	* function getlist returns list of api maching $category
	* @param  $q    |string
	* @return $data |array
	**/
	function getlist($category){
		$cols =" *,".getConfig("SUBCATEGORY_API")." as subcategory,".getConfig("CATEGORY_API")." as category";
		if($category=="*") {
			$whr  ="blocked='false'";
		} else {
			$whr  ="blocked='false' AND ".getConfig("CATEGORY_API")."='".$category."'";
		}
		$whr.=getAPIListWhere("api");
		
		$sql  = _db()->_selectQ('api_toc',$cols,$whr);
		$res  = _dbQuery($sql);
		$data = _dbData($res);
		return $data;
	}
	/**
	* function getCategory returns list of api category 
	* @param  null
	* @return $data |array
	**/
	function getCategory(){
		$cols =" DISTINCT(".getConfig("CATEGORY_API").") as category ";
		$whr  ="blocked='false' ";
		
		$sql  = _db()->_selectQ('api_toc',$cols,$whr);
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
	* function saveApi insert/update api into api_toc
	* @param  null
	* @return success/error
	**/
	function saveApi(){
		if($_REQUEST['id']=='0'){
			$api = array(
				'title' 	  => '',
				'lgks_type'	  => '',
				'obj_type'	  => '',
				'tags'		  => '',
				'descs_short' => '',
				'descs_long'  => '',
				'descs_params'=>'',
				'descs_parser'=>'markitup',
				'defination'  =>'',
				'src_name'    => '',
				'src_lang'    => '',
				'src_path'    => '',
				'src_checkout'=> '',
				'max_vers'	  => '',
				'min_vers'	  => '',
				'package_id'  => '',
				'author'	  =>"{$_SESSION['SESS_USER_NAME']} [{$_SESSION['SESS_USER_ID']}]",
				'creator'	  =>"{$_SESSION['SESS_USER_NAME']} [{$_SESSION['SESS_USER_ID']}]",
				'userid' 	  =>$_SESSION['SESS_USER_ID'],
				'blocked' 	  => 'false',
				'dtoc' 		  => date('Y-m-d H:i:s'),
				'dtoe' 		  => date('Y-m-d H:i:s')
			);
			if($_POST["max_vers"]==null || $_POST["max_vers"]=="null") $_POST["max_vers"]="*";
			if($_POST["min_vers"]==null || $_POST["min_vers"]=="null") $_POST["min_vers"]="*";
			foreach($api as $k => $v) {
				if(isset($_POST[$k])){
					$api[$k] = trim($_POST[$k]);
				}
			}
			$title=split('[(]', $api['title']);
			$api['title']=stripslashes($title[0]);
			$api['defination']=stripslashes($api['defination']);
			$api['descs_short']=nl2br($api['descs_short']);
			$sql = _db()->_insertQ1('api_toc',$api);
			$res = _dbQuery($sql);
			$id=_db()->insert_id();
			_dbFree($res);
			if($res) {
				$apiLink=_link("api/").$_REQUEST[getConfig("CATEGORY_API")]."/".$_REQUEST[getConfig("SUBCATEGORY_API")].".".$_REQUEST['title']."-".$id;
				return $apiLink;
			} else {
				return "error";
			}
		}else{
			
			$api = array(
				'title' 	  => '',
				'lgks_type'	  => '',
				'obj_type'	  => '',
				'tags'		  => '',
				'descs_short' => '',
				'descs_long'  => '',
				'descs_params'=> '',
				'descs_parser'=>'markitup',
				'defination'  => '',
				'src_name'    => '',
				'src_lang'    => '',
				'src_path'    => '',
				'src_checkout'=> '',
				'max_vers'	  => '',
				'min_vers'	  => '',	
				'package_id'  => '',
				'author'	  =>"{$_SESSION['SESS_USER_NAME']} [{$_SESSION['SESS_USER_ID']}]",
				'userid' 	  =>$_SESSION['SESS_USER_ID'],
				'blocked' 	  => 'false',
				'dtoc' 		  => date('Y-m-d H:i:s'),
				'dtoe' 		  => date('Y-m-d H:i:s')
			);
		
			foreach($api as $k => $v) {
				if(isset($_POST[$k])){
					$api[$k] = trim($_POST[$k]);
				}
			}
			$title=split('[(]', $api['title']);
			$api['title']=stripslashes($title[0]);
			$api['defination']=stripslashes($api['defination']);
			$api['descs_short']=nl2br($api['descs_short']);
			$whr="md5(id)='".$_REQUEST['id']."'";
			$sql = _db()->_updateQ('api_toc',$api,$whr);
			$res = _dbQuery($sql);
			
		_dbFree($res);
			if($res) {
				$cols="id";
				$whr="md5(id)='".$_REQUEST['id']."'";
				$sql = _db()->_selectQ('api_toc',$cols,$whr);
				$res = _dbQuery($sql);
				$data = _dbFetch($res);
				$apiLink=_link("api/").$_REQUEST[getConfig("CATEGORY_API")]."/".$_REQUEST[getConfig("SUBCATEGORY_API")].".".$_REQUEST['title']."-".$data['id'];
				return $apiLink;
			} else {
				return "error";
			}
		}
		
	}
	/**
	* function getApiDetails returns api details with examples and comments 
	* @param  $id |md5()
	* @return $data |array 
	**/
	function getApiDetails($id){
		$cols =" *,src_name as subcategory,lgks_type as category";
		$whr  ="blocked='false' AND md5(id)='".$id."'";
		$whr.=getAPIListWhere("api");
		
		$sql  = _db()->_selectQ('api_toc',$cols,$whr);
		$res  = _dbQuery($sql);
		if($res) {
			$data = _dbFetch($res);
			_dbFree($res);
			if(isset($data['author'])) $data['authorid']=extractUserID($data['author']); else $data['authorid']="";
			if(isset($data['creator'])) $data['creatorid']=extractUserID($data['creator']); else $data['creatorid']="";
			if($data['authorid']==$_SESSION['SESS_USER_ID'] || $data['creatorid']==$_SESSION['SESS_USER_ID']) {
				$data['editable']="true";
				$data['edit_url']=_link("api/edit/").md5($data['id']);
			} elseif(checkUserRoles("api","Allow Editing ALL API")) {
				$data['editable']="true";
				$data['edit_url']=_link("api/edit/").md5($data['id']);
			}else{
				$data['editable']="false";
			}
			$data['defination']=stripslashes($data['defination']);
			$data['addExample']=_link("api/createExample/").$data['id'];
			//getcomments
			$cols=" id,comment,username,userid,dtoc";
			$whr="api_id='".$data['id']."' AND approved='true'";
			$sql  = _db()->_selectQ('api_comments',$cols,$whr);
			$res  = _dbQuery($sql);
			$comments = _dbData($res);
			$data['comments']=$comments;
			
			//getexamples
			$cols=" id,eg_descs,eg_php,eg_author,dtoe";
			$whr="api_id='".$data['id']."' AND locked='true'";
			$sql  = _db()->_selectQ('api_examples',$cols,$whr);
			$res  = _dbQuery($sql);
			$examples = _dbData($res);
			_dbFree($res);
			$i=0;
			foreach($examples as $example){
				if(isset($example['eg_author'])) $example['eg_authorid']=extractUserID($example['eg_author']); 
				else $example['eg_authorid']="";
				
				if($example['eg_authorid']==$_SESSION['SESS_USER_ID']){
					$example['editable']='true';
					$example['edit_url']=_link("api/edit_example/").md5($example['id']);
				}
				$data['examples'][$i]=$example;
				$i++;
			}
		} else {
			$data=array();
		}
		
		return $data;
	}
	/**
	* function saveComment insert api comment into api_comments
	* @param  null
    * @return success/error
	**/
	function saveComment(){
		$comment=array(
			"api_id"  =>$_REQUEST['api_id'],
			"comment" =>stripslashes($_REQUEST['comment']),
			'username'  =>$_SESSION['SESS_USER_NAME'],
			'userid'  =>$_SESSION['SESS_USER_ID'],
			'approved'=>'true',
			'blocked' => 'false',
			'dtoc' 	  => date('Y-m-d H:i:s'),
			'dtoe' 	  => date('Y-m-d H:i:s')
			);
		$sql = _db()->_insertQ1('api_comments',$comment);
		$res = _dbQuery($sql);
		_dbFree($res);
		if($res) {
			return "success";
		} else {
			return "error";
		}
	}
	/**
	* function saveExample insert/update api example into api_examples
	* @param  null
    * @return success/error
	**/
	function saveExample(){
		$eg_php=str_replace(PHP_EOL,'<br>',$_REQUEST['eg_php']);
		$eg_php=stripslashes($eg_php);
		if(isset($_REQUEST['api_id']) && $_REQUEST['api_id']!=0){
			$example=array(
				"api_id"	=> $_REQUEST['api_id'],
				"tags"		=> $_REQUEST['tags'],
				"eg_descs"  => stripslashes($_REQUEST['eg_descs']),
				"eg_php"	=> $eg_php,
				"eg_author" => "{$_SESSION['SESS_USER_NAME']} [{$_SESSION['SESS_USER_ID']}]",
				'userid'	=> $_SESSION['SESS_USER_ID'],
				'locked'	=> 'true',
				'blocked' 	=> 'false',
				'dtoc' 	  	=> date('Y-m-d H:i:s'),
				'dtoe' 	  	=> date('Y-m-d H:i:s')
				);
			$sql = _db()->_insertQ1('api_examples',$example);
		}elseif(isset($_REQUEST['eg_id'])){
			$example=array(
				"tags"		=> $_REQUEST['tags'],
				"eg_descs"  => stripslashes($_REQUEST['eg_descs']),
				"eg_php"	=> $eg_php,
				"eg_author" => "{$_SESSION['SESS_USER_NAME']} [{$_SESSION['SESS_USER_ID']}]",
				'userid'	=> $_SESSION['SESS_USER_ID'],
				'locked'	=> 'true',
				'blocked' 	=> 'false',				
				'dtoe' 	  	=> date('Y-m-d H:i:s')
				);
			
			$whr="md5(id)='".$_REQUEST['eg_id']."'";
			$sql = _db()->_updateQ('api_examples',$example,$whr);
		}
		
		$res = _dbQuery($sql);
		_dbFree($res);
		if($res) {
			return "success";
		} else {
			return "error in query";
		}
	}
	/**
	* function getExampleDetails returns details of given  example id
	* @param  $id 
    * @return example|array
	**/
	function getExampleDetails($id){ 
		
		$cols=" id,api_id,eg_descs,tags,eg_php,eg_author,dtoe";
		$whr="md5(id)='".$id."' AND locked='true'";
		$sql  = _db()->_selectQ('api_examples',$cols,$whr);
		$res  = _dbQuery($sql);
		$example = _dbFetch($res);
		$eg_php=str_replace('<br>',PHP_EOL,$example['eg_php']);
		$eg_php=stripslashes($eg_php);
		$example['eg_php']=$eg_php;
		
		if(isset($example['eg_author'])) $example['eg_authorid']=extractUserID($example['eg_author']); 
		else $example['eg_authorid']="";
		if($example['eg_authorid']==$_SESSION['SESS_USER_ID']){
			$example['editable']='true';
		} else {
			$example['editable']='false';
		}
		return $example; 
	}
	function checkTitle($title){
		$cols =" count(*) as titlecount";
		$whr  ="blocked='false' AND title LIKE '".$title."'";
		$sql  = _db()->_selectQ('api_toc',$cols,$whr);
		$res  = _dbQuery($sql);
		$data = _dbFetch($res);
		
		return $data;
	}
	function getTags(){
		$cols =" tags";
		$whr  ="blocked='false'";
		$sql  = _db()->_selectQ('api_toc',$cols,$whr);
		$res  = _dbQuery($sql);
		$data = _dbData($res);
		$tags=array();
		foreach($data as $d){
			$tagarray=explode(',',$d['tags']);
			foreach($tagarray as $tg){
				$tags[]=$tg;
			}
			$tags=array_unique($tags);
				//array_push($tags,$tagarray);		
		}
		return $tags;
	}
}
?>
