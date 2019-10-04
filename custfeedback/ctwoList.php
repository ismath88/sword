<?php
	
	class ctwoList extends Dashboard {
		
		protected $headerTitle = "Complaint Type Work Order (CTWO) List";
		
		protected function footer() {
			return "<div style=\"text-align:right\"><input type=\"button\" value=\"Export\" /></div>";
		}
		
		
		public function __toString() {
			if(util::fetch('optbox') != '') {
				return DashboardCommonUI::getAreaNm(util::fetch('optbox'),util::fetch('val'));
			}
			else {
				return parent::__toString();
			}
		}
		
		protected function pdq() {
			
			/*
				Sample:
				-------
				
				array(
				
				1.	array("content to display" , "settings")
				2.	"content to display only"
				)
				
			*/
			
			$str_ordStatusList = "";
			
			try {
				$var_results = db::callSQL('SQL_harezad','Status_F_UNIFY_FULLFILMENT');
				if(!is_array($var_results)) {
					throw new errors($var_results);
				}
				else {
					$str_ordStatusList .= "<option value=''>All</option>";
					for($i=0;$i<count($var_results);$i++) {
						$str_ordStatusList .= "<option>". $var_results[$i]["ACTIVITY_TYPE"] ."</option>";
					}
				}
			}
			catch(errors $e) {
				$this->runScript('alert',array(htmlspecialchars($e->message())));
			}
			
			
			
			
			$ary_x = array(
				"Filter by Status: <select id=\"ACTIVITY_TYPE\" name=\"ACTIVITY_TYPE\">". $str_ordStatusList ."</select>",
				array("Search       : <input type=\"text\" id=\"TEAM_ID\" name=\"TEAM_ID\" value=\"\" />",array("search_like"=>true))
				
				/* "Prod. Type: ". $this->makeSelectBox(),
				"Order Type: ". $this->makeSelectBox(),
				"Activity Status: ". $this->makeSelectBox(),
				"Order ID: ". $this->makeInputBox(),
				"Installer Team: ". $this->makeSelectBox(),
				"Installer Company: ". $this->makeSelectBox(),
				"Service No.: ". $this->makeInputBox(),
				"Date: ". DashboardCommonUI::dateBox('dtx','Please Click here') */
			);
			return $ary_x;
			return array_merge(DashboardCommonUI::pdq_PTTZoneBuild(),$ary_x);
		}
		
		public static function makeInputBox() {
			return "<input type='text' />";
		}
		
		public static function makeSelectBox() {
			return "
				<select>
					<option>All</option>
					<option>Option 1</option>
					<option>Option 2</option>
					<option>Option 3</option>
				</select>
			";
		}
		
		protected function userdefined_js() {
			return 
			DashboardCommonUI::js_PTTZoneBuild(__CLASS__) .
			DashboardCommonUI::js_dateBox() .
			"
			
			";
		}
		
		public static function dateBox() {
			return "<input type=\"text\" class=\"datepicker\" value=\"\" readonly=\"readonly\" required=\"required\" />";
		}
		
		/*protected function realtimeStatus() {
			return array(
				"survey" => "Survey",
				"unjumper" => "Un-jumpering",
				"x" => "Config Server",
				"y" => "Proposed Return",
				"z" => "Payment-approval",
				"a" => "Pending Survey Complete",
				"b" => "Returned Technical",
				"jump" => "Jumpering",
				"speed" => "Speed Config",
				"c" => "Verification",
				"d" => "Post-Complete",
				"e" => "Processing (scheduled)",
				"f" => "Wifi AP Installation",
				"g" => "Design & Assign",
				"h" => "Delayed",
				"i" => "Subscriber Activity",
				"j" => "Returned",
				"k" => "Miss Appt",
				"l" => "Processing (un-scheduled)",
				"m" => "Total Active Order"
			);
		}*/
		
		protected function gridData() {
			try {
				//$var_results = db::callSQL('SQL_harezad','F_UNIFY_FULLFILMENT');
				$var_results = $this->callSQL('SQL_harezad','F_UNIFY_FULLFILMENT');
				
				if(!is_array($var_results)) {
					throw new errors($var_results);
				}
				else {
					//print_r($var_results);
					$var_results["Columns"] = array(
						"ACTIVITY_TYPE"=> "ACTIVITY TYPE",
						"ORDER_NUMBER" => "ORDER NUMBER",
						"EXCHANGE" => "EXCHANGE",
						"RNO_REGION" => "ZONE 1",
						"RNO_REGION2" => "ZONE 2",
						"CABINET_ID" => "CABINET ID",
						"ADDR_INDICATOR" => "ADDR INDICATOR",
						"TEAM_ID" => "TEAM ID",
						"ORDER_TYPE" => "TYPE",
						"PRIORITY" => "PRIORITY",
						"SLOT_START" => "SLOT START DATE",
						"ACTIVITY_PLANNED_START" => "PLANNED START DATE",
						"SEGMENT" => "SEGMENT",
						"CREATED_DATE" => "CREATED DATE",
						"ORDER_STATUS" => "STATUS",
						"CUSTOMER_NAME" => "CUSTOMER NAME",
						"ACTIVITY_NAME" => "ACTIVITY",
						"DP_LOCATION" => "DP LOCATION",
						"DP_TYPE" => "DP TYPE",
						"OP_ERROR_MESSAGE" => "OP ERROR MESSAGE"
					);
					
					return $var_results;
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		/* protected function gridData1() {
			return array(
				"Columns" => array(
					"Status" => "Activity Status",
					"BuildingID" => "Building ID",
					"Zone" => "Zone",
					"PTT" => "PTT",
					"Company" => "Company",
					"Team" => "Installer Team",
					"OrderID" => "Order ID",
					"ProdType" => "Prod. Type",
					"ServiceNo" => "Service No.",
					"CreatedDate" => "Created Date",
					"Jump" => "Jumpering",
					"Switch" => "Switching",
					"Install" => "Installation",
					"Speed" => "Speed Config",
					"Verification" => "Verification"
				),
				0 => array(
					"BuildingID" => "B1",
					"Zone" => "Bangsar",
					"PTT" => "Kuala Lumpur",
					"Company" => "ABC",
					"Team" => "1KLZ",
					"OrderID" => "1-2345",
					"ProdType" => "Unifi",
					"ServiceNo" => "123",
					"CreatedDate" => "21/8/2013",
					"Jump" => "1",
					"Switch" => "2",
					"Install" => "3",
					"Speed" => "4",
					"Verification" => "Admin",
					"Status" => "Active"
				),
				1 => array(
					"BuildingID" => "B2",
					"Zone" => "Bangsar",
					"PTT" => "Kuala Lumpur",
					"Company" => "ABC",
					"Team" => "1KLZ",
					"OrderID" => "1-2345",
					"ProdType" => "Unifi",
					"ServiceNo" => "123",
					"CreatedDate" => "21/8/2013",
					"Jump" => "1",
					"Switch" => "2",
					"Install" => "3",
					"Speed" => "4",
					"Verification" => "Admin",
					"Status" => "Active"
				),
				2 => array(
					"BuildingID" => "B3",
					"Zone" => "Bangsar",
					"PTT" => "Kuala Lumpur",
					"Company" => "ABC",
					"Team" => "1KLZ",
					"OrderID" => "1-2345",
					"ProdType" => "Unifi",
					"ServiceNo" => "123",
					"CreatedDate" => "21/8/2013",
					"Jump" => "1",
					"Switch" => "2",
					"Install" => "3",
					"Speed" => "4",
					"Verification" => "Admin",
					"Status" => "Active"
				),
				3 => array(
					"BuildingID" => "B4",
					"Zone" => "Bangsar",
					"PTT" => "Kuala Lumpur",
					"Company" => "ABC",
					"Team" => "1KLZ",
					"OrderID" => "1-2345",
					"ProdType" => "Unifi",
					"ServiceNo" => "123",
					"CreatedDate" => "21/8/2013",
					"Jump" => "1",
					"Switch" => "2",
					"Install" => "3",
					"Speed" => "4",
					"Verification" => "Admin",
					"Status" => "Active"
				)
			);
		} */
	}
?>