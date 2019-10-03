<?php
	
	class UnScheduledOdrUI extends Dashboard {
		
		protected $headerTitle = "Un-scheduled Orders";
		
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
				"Building ID: ". OrderUI::makeSelectBox(),
				"Installer Team: ". OrderUI::makeSelectBox()
			);
		}
		
		protected function gridData() {
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
					"IVR" => "IVR",
					"Dna" => "D & A",
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
					"IVR" => "",
					"Dna" => "",
					"OrderStatus" => "",
					"ApptTeam" => "",
					"Aging" => "",
					"Return" => "1",
					"Rebook" => "2",
					"Source" => "",
					"Type" => "",
					"Action" => "<input type=\"button\" value=\"Book\">"
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
					"IVR" => "",
					"Dna" => "",
					"OrderStatus" => "",
					"ApptTeam" => "",
					"Aging" => "",
					"Return" => "1",
					"Rebook" => "2",
					"Source" => "",
					"Type" => "",
					"Action" => "<input type=\"button\" value=\"Book\">"
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
					"IVR" => "",
					"Dna" => "",
					"OrderStatus" => "",
					"ApptTeam" => "",
					"Aging" => "",
					"Return" => "1",
					"Rebook" => "2",
					"Source" => "",
					"Type" => "",
					"Action" => "<input type=\"button\" value=\"Book\">"
				),
				3 => array(
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
					"IVR" => "",
					"Dna" => "",
					"OrderStatus" => "",
					"ApptTeam" => "",
					"Aging" => "",
					"Return" => "1",
					"Rebook" => "2",
					"Source" => "",
					"Type" => "",
					"Action" => "<input type=\"button\" value=\"Book\">"
				)
			);
		}
	}
?>