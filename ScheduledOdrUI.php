<?php
	
	class ScheduledOdrUI extends Dashboard {
		
		protected $headerTitle = "Scheduled Orders";
		
		protected function footer() {
			return "<div style=\"text-align:right\"><input type=\"button\" value=\"Export\" /></div>";
		}
		
		protected function pdq() {
			return array(
				"PTT: ". OrderUI::makeSelectBox(),
				"Zone: ". OrderUI::makeSelectBox(),
				"Order Type: ". OrderUI::makeSelectBox(),
				"Order ID: ". OrderUI::makeInputBox(),
				"Segment: ". OrderUI::makeSelectBox(),
				"ComboFlag: ". OrderUI::makeSelectBox(), 
				"Service Num: ". OrderUI::makeInputBox(),
				"Building ID: ". OrderUI::makeSelectBox(),
				"Installer Team: ". OrderUI::makeSelectBox(),
				"Date: ". OrderUI::dateBox()
			);
		}
		
		protected function userdefined_js() {
			return "
				var dateToday = new Date();
				
				$(function() {
					$('.datepicker').datepicker({
						
						dateFormat: 'dd-mm-yy',
						firstDay: 1,
						maxDate: '+1Y',
						defaultDate: dateToday
					});
				});
			";
		}
		
		protected function gridData() {
			$str_action = "
				<select>
					<option>-</option>
					<option>Delay</option>
					<option>Rebook</option>
					<option>Propose Return</option>
					<option>Manual Complete</option>
					<option>Re-assign</option>
				</select>
			";
			
			return array(
				"Columns" => array(
					"OrderID" => "Order ID",
					"ServiceNo" => "Service No.",
					"loginid" => "Login ID",
					"OrderType" => "Order Type",
					"Combo" => "Combo",
					"Segment" => "Segment",
					"DAT" => "DAT",
					"ApptDateTime" => "Appt. Date Time",
					"Jumpering" => "Jumpering",
					"SpeedCfg" => "Speed Config",
					"SoftTone" => "Soft Tone",
					"ApptCount" => "Appt. Count",
					"OrderStatus" => "Order Status",
					"ApptTeam" => "Appt. Team",
					"Aging" => "Aging",
					"Return" => "Return Count",
					"Rebook" => "Rebook Count",
					"Source" => "Appt. Source",
					"Type" => "Appt. Type",
					"Action" => "Action"
				),
				0 => array(
					"OrderID" => "XYZ",
					"ServiceNo" => "ABC",
					"loginid" => "ERT",
					"OrderType" => "",
					"Combo" => "",
					"Segment" => "",
					"DAT" => "",
					"ApptDateTime" => "2/9/2013",
					"Jumpering" => "",
					"SpeedCfg" => "533",
					"SoftTone" => "",
					"ApptCount" => "",
					"OrderStatus" => "",
					"ApptTeam" => "",
					"Aging" => "",
					"Return" => "1",
					"Rebook" => "2",
					"Source" => "",
					"Type" => "",
					"Action" => $str_action
				),
				1 => array(
					"OrderID" => "XYZ",
					"ServiceNo" => "ABC",
					"loginid" => "ERT",
					"OrderType" => "",
					"Combo" => "",
					"Segment" => "",
					"DAT" => "",
					"ApptDateTime" => "2/9/2013",
					"Jumpering" => "",
					"SpeedCfg" => "533",
					"SoftTone" => "",
					"ApptCount" => "",
					"OrderStatus" => "",
					"ApptTeam" => "",
					"Aging" => "",
					"Return" => "1",
					"Rebook" => "2",
					"Source" => "",
					"Type" => "",
					"Action" => $str_action
				),
				2 => array(
					"OrderID" => "XYZ",
					"ServiceNo" => "ABC",
					"loginid" => "ERT",
					"OrderType" => "",
					"Combo" => "",
					"Segment" => "",
					"DAT" => "",
					"ApptDateTime" => "2/9/2013",
					"Jumpering" => "",
					"SpeedCfg" => "533",
					"SoftTone" => "",
					"ApptCount" => "",
					"OrderStatus" => "",
					"ApptTeam" => "",
					"Aging" => "",
					"Return" => "1",
					"Rebook" => "2",
					"Source" => "",
					"Type" => "",
					"Action" => $str_action
				)
			);
		}
	}
?>