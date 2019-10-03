<?php
	
	class DelayUI_sample extends Dashboard {
		
		protected $headerTitle = "Delayed Activities";
		
		public function __toString() {
			if(util::fetch('optbox') != '') {
				return DashboardCommonUI::getAreaNm(util::fetch('optbox'),util::fetch('val'));
			}
			else {
				return parent::__toString();
			}
		}
		
		protected function pdq() {
			$ary_pdq = array(
				"JS Test: <input type='textbox' value='Change this value' onchange='javascript:test(this)'>",
				"Date: ". DashboardCommonUI::dateBox("testdtbox",date("d-m-Y"))
			);
			return array_merge(DashboardCommonUI::pdq_PTTZoneBuild(),$ary_pdq);
		}
		
		protected function gridData() {
			try {
				$var_results = db::callSQL('SQL_harezad','AllBuildingList');
				
				if(!is_array($var_results)) {
					throw new errors($var_results);
				}
				else {
					
					for($i=0;$i<count($var_results);$i++) {
						$var_results[$i]["x"] = "<input type='button' value='OK' onclick=\"javascript:alert('". $var_results[$i]["BUILD_NAME"] ."')\" />";
						$var_results[$i]["y"] = "<a href=\"http://www.tmrnd.com.my\" target=\"_new\">Link</a>";
						
						if(($i%3) == 0) {
							foreach($var_results[$i] as $key=>$index) {
								$var_results[$i][$key] = "<b style='color:#ff0000'>". $index ."</b>";
							}
						}
						elseif(($i%5) == 0) {
							foreach($var_results[$i] as $key=>$index) {
								$var_results[$i][$key] = "<b style='color:#00ff00'>". $index ."</b>";
							}
						}
					}
					
					$var_results["Columns"] = array(
						"PTT_NAME" => "PTT",
						"x" => "OK",
						"ZONE_NAME" => "Zone",
						"BUILD_NAME" => "Building",
						"INV_TARGET" => "Inventory",
						"y" => "NO"
					);
					
					return $var_results;
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		protected function userdefined_js() {
			return 
				DashboardCommonUI::js_dateBox() .
				DashboardCommonUI::js_PTTZoneBuild(__CLASS__) .
			"
				function test(obj) {
					alert('New value: '+ obj.value);
				}
			";
		}
	}
?>