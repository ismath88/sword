<?php
	
	class MenuManagement extends gui {
		
		public function __toString() {
			if(util::fetch('delmenuid') != '') {
				return DataUpdater::deleteMenu(util::fetch('delmenuid'));
			}
			elseif(util::fetch('move') != '') {
				return DataUpdater::reorganizeMenu(util::fetch('move'),util::fetch('dir'));
			}
			else {
				return parent::__toString();
			}
		}
		
		protected function content() {
			
			$this->import('datagrid.min.css');
			$this->importPlugin('colorbox/jquery.colorbox.js');
			$this->importPlugin('colorbox/colorbox.css');
			$this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');
			$this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
			
			$int_menuCat = (util::fetch("menucat") != "")? util::fetch("menucat") : 1;
			
			if(isset($_POST['menuname'])) {
				$bool_check = DataUpdater::updateSystemMenu($int_menuCat,$_POST,$str_rtnErrMessage);
				if($str_rtnErrMessage) {
					$this->runScript('alert',array(htmlspecialchars($str_rtnErrMessage)));
				}
			}
			
			$str_menuList = $this->loadMenu($int_totalSection,$int_menuCat);
			
			return "
				<article>
					<header>
						<h1>Menu Management</h1>
					</header>
					<section>
						<h2>System Menu - ". (($int_menuCat == 1)? "Portal" : "Mobile Device Management (MDM)") ."</h2>
						<p style=\"text-align:right\">
							<button class=\"btn5s\" onclick=\"javascript:location.replace('?clsid=". __CLASS__ ."&clspar=". urlencode("menucat=". (($int_menuCat == 1)? 2 : 1)) ."')\">Show ". (($int_menuCat == 1)? "MDM" : "Portal") ." Menu</button>
							<button class=\"btn5 inline\" href=\"#inline_content\" onclick=\"javascript:setFormData(". $int_totalSection .")\">Add New Section</button>
						</p>
						<div id=\"accordion\">". $str_menuList ."</div>
					</section>
					<div style='display:none'>
						<div id='inline_content' style='padding:10px; background:#fff;'>
							<form method=\"post\" action=\"?clsid=". __CLASS__ ."&clspar=". urlencode("menucat=". $int_menuCat) ."\" onsubmit=\"javascript:return formChecker()\" type=\"application/x-www-form-urlencoded\">
								<h2>Add/Update Menu</h2>
								<table id=\"tblfrm\">
									<tr>
										<td>Menu Name</td>
										<td><input type=\"text\" id=\"menuname\" name=\"menuname\" value=\"\" required=\"required\" /></td>
									</tr>
									<tr>
										<td>Hyperlink</td>
										<td><textarea id=\"hyperlink\" name=\"hyperlink\"></textarea></td>
									</tr>
									<tr>
										<td colspan=\"2\" style=\"text-align:right\"><button type=\"submit\" class=\"btn5save\">Save</button></td>
									</tr>
								</table>
								<input type=\"hidden\" id=\"mid\" name=\"mid\" value=\"\" />
								<input type=\"hidden\" id=\"mpid\" name=\"mpid\" value=\"\" />
								<input type=\"hidden\" id=\"nxno\" name=\"nxno\" value=\"\" />
								<input type=\"hidden\" id=\"secno\" name=\"secno\" value=\"". ((isset($_REQUEST['secno']) && $_REQUEST['secno'] != '')? $_REQUEST['secno'] : 0) ."\" />
							</form>
						</div>
					</div>
				</article>
			";
		}
		
		private function loadMenu(&$int_totalSection = 0,$int_menuCat) {
			try {
				$var_results = DataLoader::getData("MenuList",array($int_menuCat));//self::testData();
				if(is_array($var_results)) {
					$ary_section = array();
					for($i=0;$i<count($var_results);$i++) {
						if($var_results[$i]['PARENTID'] > 0) {
							$ary_section[$var_results[$i]['PARENTID']][$var_results[$i]['MENU_LIST_ID']] = array($var_results[$i]['DESCRIPTION'],$var_results[$i]['LINK']);
						}
						else {
							$ary_section[$var_results[$i]['MENU_LIST_ID']][0] = array($var_results[$i]['DESCRIPTION'],$var_results[$i]['LINK']);
						}
					}
					
					$int_totalSection = count($ary_section)+1;
					$str_section = "";
					$z = 0;
					foreach($ary_section as $index=>$data) {
						$str_submenu = ""; $i=1;
						foreach($data as $id=>$val) {
							if($id != 0) {
								$str_submenu .= "
									<tr>
										<td>". $i.".</td>
										<td style=\"text-align:left\">". $val[0] ."</td>
										<td style=\"text-align:left\">". $val[1] ."</td>
										<td><button class=\"btn5edit inline\" href=\"#inline_content\" onclick=\"javascript:setFormData(0,". $id .",false,this)\">Edit Sub Menu</button></td>
										<td><button class=\"btn5moveup\" onclick=\"javascript:chgOrder(". $id .",1)\">Move Up</button></td>
										<td><button class=\"btn5movedn\" onclick=\"javascript:chgOrder(". $id .",2)\">Move Down</button></td>
										<td><button class=\"btn5del\" onclick=\"javascript:delMenu(". $id .")\">Delete Sub Menu</button></td>
										<td><button class=\"btn5info xbtn\" href=\"?clsid=MenuInfo&clspar=". urlencode("mid=". $id) ."\">Who's accessing this menu</button></td>
									</tr>
								";
								$i++;
							}
						}
						$str_submenu = ($str_submenu != "")? "<table class=\"datagrid\"><tr><th>No.</th><th>Sub Menu Name</th><th>Hyperlink</th><th>Edit</th><th colspan=\"2\">Change Order</th><th>Del.</th><th>Info</th></tr>". $str_submenu ."</table>" : "";
						$str_section .= "
							<h3 id=\"sec_". $index ."\" onclick=\"javascript:$('#secno').val(". $z .");\">
								<table>
									<tr>
										<td><b>". strtoupper($data[0][0]) ."</b></td>
										<td class=\"actBtn\" rowspan=\"2\">
											<button class=\"btn5edit inline\" href=\"#inline_content\" onclick=\"javascript:setFormData(0,". $index .",false,false,'sec_". $index ."')\">Edit section</button>
											<button class=\"btn5moveup\" onclick=\"javascript:chgOrder(". $index .",1)\">Change order: Move Up</button>
											<button class=\"btn5movedn\" onclick=\"javascript:chgOrder(". $index .",2)\">Change order: Move Down</button>
											<button class=\"btn5del\" onclick=\"javascript:delMenu(". $index .")\">Delete Section</button>
										</td>
									</tr>
									<tr>
										<td><i>". $data[0][1] ."</i></td>
									</tr>
								</table>
							</h3>
							<div>
								<p>
									<button class=\"btn5 inline\" href=\"#inline_content\" onclick=\"javascript:setFormData(". $i .",false,". $index .");$('#secno').val(". $z .");\">Add new sub menu</button>
									". $str_submenu ."
								</p>
							</div>
						";
						$z++;
					}
					//print_r($ary_section);
					return $str_section;
				}
				else {
					throw new errors($var_results);
				}
			}
			catch(errors $e) {
				$this->runScript('alert',array(htmlspecialchars($e->message())));
			}
		}
		
		protected function css() {
			return "
				h3 table {
					width:100%;
				}
				h3 table td i {
					font-size:8pt;
				}
				h3 table td.actBtn {
					text-align:right;
				}
				#tblfrm {
					background-color:#f0f0f0;
				}
				#tblfrm td {
					padding:5px;
					vertical-align:top;
				}
				textarea {
					width:300px;
					height:200px;
				}
			";
		}
		
		
		protected function js() {
			return "
				$(function() {
					$(\".inline\").colorbox({inline:true, width:\"50%\"});
					$(\"#accordion\").accordion({
						collapsible: true,
						heightStyle: \"content\",
						active: ". ((isset($_REQUEST['secno']) && $_REQUEST['secno'] != '')? $_REQUEST['secno'] : 0) ."
					});
					$(\".btn5s\").button({icons: {primary: \"ui-icon-folder-open\"}});
					$(\".btn5\").button({icons: {primary: \"ui-icon-circle-plus\"}});
					$(\".btn5save\").button({icons: {primary: \"ui-icon-circle-check\"}});
					$(\".btn5edit\").button({icons: {primary: \"ui-icon-pencil\"}, text: false});
					$(\".btn5del\").button({icons: {primary: \"ui-icon-trash\"}, text: false});
					$(\".btn5moveup\").button({icons: {primary: \"ui-icon-arrowthick-1-n\"}, text: false});
					$(\".btn5movedn\").button({icons: {primary: \"ui-icon-arrowthick-1-s\"}, text: false});
					$(\".btn5info\").button({icons: {primary: \"ui-icon-help\"}, text: false});
					$(\".xbtn\").colorbox({width:\"70%\", height:\"80%\", iframe:true});
				});
				function delMenu(int_menuID) {
					try {
						if(confirm('Are you sure you want to delete the selected record and it associated data?')) {
							actBtnEvt.ajaxSend('" . __CLASS__ . "','delmenuid='+ int_menuID,function(){
								if(actBtnEvt.xmlHttpComObj.readyState == 4) {
									if(actBtnEvt.xmlHttpComObj.responseText == 'ok') {
										alert('Deleted!');
										location.replace(window.location.href +'&secno='+ $('#secno').val());
									}
									else {
										alert(actBtnEvt.xmlHttpComObj.responseText);
									}
								}
							});
						}
					}
					catch(e) {
						alert(e.message);
					}
				}
				function formChecker() {
					try {
						if(actBtnEvt.trim($('#menuname').val()) != '') {
							return true;
						}
						else {
							throw('Oops! Something went wrong. Please check your input.');
						}
					}
					catch(e) {
						if(e.message) {
							alert(e.message);
						}
						else {
							alert(e);
						}
						return false;
					}
				}
				function setFormData(int_nextNo,int_mid,int_mpid,obj,str_secNm) {
					try {
						$('#nxno,#mid,#mpid,#menuname,#hyperlink').val('');
						$('#nxno').val(int_nextNo);
						if(int_mid) {
							$('#mid').val(int_mid);
						}
						if(int_mpid) {
							$('#mpid').val(int_mpid);
						}
						if(obj) {
							$('#menuname').val(obj.parentNode.parentNode.getElementsByTagName('td')[1].innerHTML);
							$('#hyperlink').val(obj.parentNode.parentNode.getElementsByTagName('td')[2].innerHTML);
						}
						if(str_secNm) {
							var objx = document.getElementById(str_secNm);
							$('#menuname').val(objx.getElementsByTagName('b')[0].innerHTML);
							$('#hyperlink').val(objx.getElementsByTagName('i')[0].innerHTML);
						}
					}
					catch(e) {
						alert(e.message);
					}
				}
				function chgOrder(int_menuID,int_direction) {
					try {
						actBtnEvt.ajaxSend('" . __CLASS__ . "','move='+ int_menuID +'&dir='+ int_direction,function(){
							if(actBtnEvt.xmlHttpComObj.readyState == 4) {
								if(actBtnEvt.xmlHttpComObj.responseText == 'ok') {
									/*location.replace(window.location.href);*/
									location.replace(window.location.href +'&secno='+ $('#secno').val());
								}
								else {
									alert(actBtnEvt.xmlHttpComObj.responseText);
								}
							}
						});
					}
					catch(e) {
						alert(e.message);
					}
				}
			";
		}
		
		public static function testData() {
			return array(
				0 => array(
					"MENU_LIST_ID" => 1,
					"PARENTID" => 0,
					"DESCRIPTION" => "Dashboard",
					"LINK" => "http://swift.tmrnd.com.my:8080/SWIFT3/Dashboard"
				),
				1 => array(
					"MENU_LIST_ID" => 2,
					"PARENTID" => 1,
					"DESCRIPTION" => "TT List UNIFI/BAU",
					"LINK" => "http://swift.tmrnd.com.my:8080/SWIFT3/TTList"
				),
				2 => array(
					"MENU_LIST_ID" => 3,
					"PARENTID" => 0,
					"DESCRIPTION" => "Inventory",
					"LINK" => "http://swift.tmrnd.com.my:8080/SWIFT3/Inventory"
				),
				3 => array(
					"MENU_LIST_ID" => 4,
					"PARENTID" => 0,
					"DESCRIPTION" => "Workforce",
					"LINK" => "xxx"
				),
				4 => array(
					"MENU_LIST_ID" => 5,
					"PARENTID" => 1,
					"DESCRIPTION" => "Search TT Number",
					"LINK" => "/TTList/Search"
				),
				5 => array(
					"MENU_LIST_ID" => 6,
					"PARENTID" => 3,
					"DESCRIPTION" => "Device Status",
					"LINK" => "xxx"
				)
			);
		}
	}
?>