<?php
	
	class UserMenuXml {
		
		public function __toString() {
			if(isset($_REQUEST["userid"]) && trim($_REQUEST["userid"]) != "") {
				$int_owner = isset($_REQUEST["owner"])? trim($_REQUEST["owner"]) : 1;
				$str_section = $this->createXML(trim($_REQUEST["userid"]),$int_owner);
			}
			else {
				$str_section = "Error: The requested data is not available.";
			}
			
			$str_section = "<?xml version=\"1.0\" encoding=\"utf-8\"?><menu>". $str_section ."</menu>";
			header('Content-type: application/xml');
			return str_replace(array("\t","\n","\r"),"",$str_section);
		}
		
		private function createXML($userid,$int_menuOwner) {
			try {
				$var_results = db::callSQL('SQL_UserMenu','UserOvrwMenuXML',array($userid,$int_menuOwner));
				
				if(!is_array($var_results)) {
					throw new errors($var_results);
				}
				else {
					
					$ary_section = array();
					$str_section = "";
					
					for($i=0;$i<count($var_results);$i++) {
						if($var_results[$i]['PARENTID'] > 0) {
							$ary_section[$var_results[$i]['PARENTID']][$var_results[$i]['MENU_LIST_ID']] = array($var_results[$i]['DESCRIPTION'],$var_results[$i]['LINK']);
						}
						else {
							$ary_section[$var_results[$i]['MENU_LIST_ID']][0] = array($var_results[$i]['DESCRIPTION'],$var_results[$i]['LINK']);
						}
					}
					
					foreach($ary_section as $index=>$data) {
						if(count($data)>1) {
							$str_submenu = "";
							foreach($data as $id=>$val) {
								if($id != 0) {
									$str_submenu .= "
										<link>
											<label><![CDATA[". $val[0] ."]]></label>
											<hyperlink><![CDATA[". $val[1] ."]]></hyperlink>
										</link>
									";
								}
							}
							$str_section .= "<group name=\"". strtoupper($data[0][0]) ."\" link=\"". $data[0][1] ."\">". $str_submenu ."</group>";
						}
					}
					
					return $str_section;
				}
				
			}
			catch(errors $e) {
				return $e->message();
			}
		}
	}
?>