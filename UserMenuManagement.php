<?php

	class UserMenuManagement extends gui {
		
		protected function content() {
			
			$int_userID = util::fetch('uid'); //867;//1488;
			
			if($int_userID != '') {
				
				$this->import('datagrid.min.css');
				
				if(isset($_POST["groupdefault"])) {
					$this->updateMenuSettings($int_userID);
				}
				
				$var_results = db::callSQL('SQL_UserMenu','SysUserInfo',array($int_userID));
				if(!is_array($var_results)) {
					$this->runScript('alert',array(htmlspecialchars($var_results)));
				}
				
				$str_submitBtn = "<div style=\"text-align:right\"><input type=\"reset\" value=\"Cancel\" /> <input type=\"submit\" value=\"Save Changes\" /></div>";
				return "
					<article>
						<header>
							<h1>System User Management</h1>
						</header>
						
						<section>
							<h2>Override User Access</h2>
							
							<p>
								<div>Name : ". $var_results[0]["NAME"] ."</div>
								<div>Staff No. : ". $var_results[0]["STAFF_NO"] ."</div>
								<div>Group Name : ". $var_results[0]["GROUPNM"] ."</div>
							</p>
							
							<hr/>
							
							<form method=\"post\" action=\"?clsid=". __CLASS__ ."&clspar=". urlencode("uid=".$int_userID) ."\" type=\"application/x-www-form-urlencoded\">
								". $str_submitBtn . $this->formatMenuList($int_userID,$var_results[0]["ROLE_ID"]) . $str_submitBtn ."
							</form>
						</section>
					</article>
				";
			}
			else {
				$this->runScript('alert',array('Undefined User ID'));
			}
		}
		
		private function formatMenuList($int_userID,$int_groupID) {
			try {
				
				$var_results = db::callSQL('SQL_UserMenu','GroupMenu',array($int_groupID));
				if(!is_array($var_results)) {
					throw new errors($var_results);
				}
				else {
					$ary_groupMenu = array();
					for($i=0;$i<count($var_results);$i++) {
						array_push($ary_groupMenu,$var_results[$i]["MENU_LIST_ID"]);
					}
				}
				
				$var_results = db::callSQL('SQL_UserMenu','UserOVRMenu',array($int_userID));
				if(!is_array($var_results)) {
					throw new errors($var_results);
				}
				else {
					$ary_individualMenu[0] = $ary_individualMenu[1] = array();
					for($i=0;$i<count($var_results);$i++) {
						$x = count($ary_individualMenu[$var_results[$i]["ACCESS_RIGHT"]]);
						$ary_individualMenu[$var_results[$i]["ACCESS_RIGHT"]][$x] = $var_results[$i]["MENU_LIST_ID"];
					}
				}
				
				$var_results = db::callSQL('SQL_UserMenu','MenuList');
				if(!is_array($var_results)) {
					throw new errors($var_results);
				}
				else {
					
					$ary_section = array();
					$str_row = "";
					
					for($i=0;$i<count($var_results);$i++) {
						if($var_results[$i]['PARENTID'] > 0) {
							$ary_section[$var_results[$i]['PARENTID']][$var_results[$i]['MENU_LIST_ID']] = array($var_results[$i]['DESCRIPTION'],$var_results[$i]['LINK']);
						}
						else {
							$ary_section[$var_results[$i]['MENU_LIST_ID']][0] = array($var_results[$i]['DESCRIPTION'],$var_results[$i]['LINK']);
						}
					}
					
					$i=1;
					
					foreach($ary_section as $index=>$data) {
						if(count($data)>1) {
							$str_row .= "
								<tr>
									<td>". $i ."</td>
									<td style=\"background-color:#ffffcc\" colspan=\"3\">". strtoupper($data[0][0]) ."</td>
								</tr>
							";
							$j=0;
							foreach($data as $id=>$val) {
								if($id>0) {
									$str_checked = in_array($id,$ary_groupMenu)? "checked='checked'" : "";
									$str_chkAllow = in_array($id,$ary_individualMenu[1])? "checked='checked'" : "";
									$str_chkDeny = in_array($id,$ary_individualMenu[0])? "checked='checked'" : "";
									
									$str_row .= "
										<tr>
											<td>". $i ."-". $j ."</td>
											<td><div class=\"rowdata\">". strtoupper($val[0]) ."<i>". $val[1] ."</i></div></td>
											<td><input id=\"". $i.$j ."1\" name=\"groupdefault[]\" value=\"". $id ."\" type=\"checkbox\" onclick=\"javascript:return false;\" ". $str_checked ." /></td>
											<td>
												<input name=\"". $id ."[]\" type=\"radio\" value=\"1\" ". $str_chkAllow ." /> Allow &nbsp;
												<input name=\"". $id ."[]\" type=\"radio\" value=\"0\" ". $str_chkDeny ." /> Deny
											</td>
										</tr>
									";
								}
								$j++;
							}
							$i++;
						}
					}
					
					return "
						<table class=\"datagrid\">
							<tr>
								<th>No.</th>
								<th>Menu</th>
								<th>Group Access</th>
								<th>Individual Access</th>
							</tr>
							". $str_row ."
						</table>
					";
				}
			}
			catch(errors $e) {
				$this->runScript('alert',array(htmlspecialchars($e->message())));
			}
		}
		
		private function updateMenuSettings($int_userID) {
			try {
				
				$var_results = db::query('delete',array('tableName'=>'USER_MENU_EXT','condition'=>"CAST(STAFF_NO AS INTEGER) = ". $int_userID),$str_errMsg,false);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				
				foreach($_POST as $id=>$val) {
					if($id != 'groupdefault') {
						if($val[0] == 1 && in_array($id,$_POST["groupdefault"])) {
						}
						elseif($val[0] == 2 && !in_array($id,$_POST["groupdefault"])) {
						}
						else {
							$ary_data = array(
								"STAFF_NO" => $int_userID,
								"MENU_LIST_ID" => $id,
								"ACCESS_RIGHT" =>  $val[0]
							);
							
							$var_results = db::query('insert',array('tableName'=>'USER_MENU_EXT','fieldWithValue'=>$ary_data),$str_errMsg,false);
							if($str_errMsg) {
								throw new errors($str_errMsg);
							}
						}
					}
				}
				
				$this->runScript('alert',array("Saved"));
			}
			catch(errors $e) {
				$this->runScript('alert',array(htmlspecialchars($e->message())));
			}
		}
		
		protected function css() {
			return "
				.rowdata {text-align:left}
				.rowdata i {display:block; font-size:7pt}
			";
		}
	}
?>