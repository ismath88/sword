<?php
	
	class MenuInfo extends gui {
		
		protected function content() {
			try {
				if(util::fetch("mid") != "") {
					
					$this->import('datagrid.min.css');
					$int_menuID = util::fetch("mid");
					
					$var_results = db::callSQL('SQL_UserMenu','MenuAccessInfo',array($int_menuID));
					
					if(!is_array($var_results)) {
						throw new errors($var_results);
					}
					else {
						
						$str_grp = $str_usa = $str_usd = "";
						
						for($i=0;$i<count($var_results);$i++) {
							$x = "<tr><td>". $var_results[$i]['NAME'] ."</td></tr>";
							switch($var_results[$i]['CAT']) {
								case "-": $str_grp .= $x; break;
								case "0": $str_usd .= $x; break;
								case "1": $str_usa .= $x; break;
							}
						}
						
						return "
							<table class=\"datagrid\">
								<tr>
									<th rowspan=\"2\">Group Access</th>
									<th colspan=\"2\">Individual Access</th>
								</tr>
								<tr>
									<th>Allow</th>
									<th>Deny</th>
								</tr>
								<tr>
									<td><table>". $str_grp ."</table></td>
									<td><table>". $str_usa ."</table></td>
									<td><table>". $str_usd ."</table></td>
								</tr>
							</table>
						";
						
						return $str_tbl ."</tr></table>";
						
						print_r($ary_list);
					}
					
					//return $int_menuID;
				}
				else {
					throw new errors("Undefined Menu ID");
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
	}
?>