<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("getAPIPrivilegeWhere")) {
	function getAPIListWhere($srcType,$authorCol="creator,author") {
		$whr="";
		if(session_check(false)) {
				if(checkUserRoles($srcType,"Allow Moderation Of All ".toTitle($srcType)) 
					 || $_SESSION['SESS_PRIVILEGE_ID']<=3) {
				} else {
					$authorCol=explode(",",$authorCol);
					foreach($authorCol as $a=>$b) {
						$authorCol[$a]="$b LIKE '%[{$_SESSION['SESS_USER_ID']}]'";
					}
					$whr.=" AND (".implode(" OR ",$authorCol)." OR approved='true')";
				}
		} else {
			$whr.=" AND approved='true'";
		}
		return $whr;
	}
	function extractUserID($text) {
		preg_match_all("/\[([^\]]*)\]/", $text, $matches);
		//printArray($matches);
		if(isset($matches[1][0])) return $matches[1][0];
		else return false;
	}
}
