<?php
	
	class DataUpdater {
		
		public static function addEmailToQueue($ary_mailList,&$str_errMsgRtn = false) {
			try {
				if(count($ary_mailList)>0) {
					
					$var_results = DataLoader::getData('EmailTemplate');
					
					if(!is_array($var_results)) {
						throw new errors($var_results);
					}
					elseif(count($var_results)<1) {
						throw new errors("Template email has not been set");
					}
					else {
						$str_subject = $var_results[0]["TITLE"];
						$str_content = $var_results[0]["CONTENT"];
					}
					
					foreach($ary_mailList as $eml=>$val) {
						$ary_data = array(
							"RECIPIENT" => $eml,
							"FULLNAME" => $val[0],
							"SUBJECT" => str_replace(array("<date>"),array(date("l, F jS Y")),$str_subject),
							"BODYTEXT" => str_replace(array("<name>","\n"),array($val[0],"<br>"),$str_content),
							"CREATED_DATE" => date("Y-m-d H:i:s")
						);
						
						$str_attachment = "";
						
						for($i=1;$i<count($val);$i++) {
							$str_attachment .= $val[$i] . (($i < (count($val)-1))? "|" : "");
						}
						
						$ary_data["ATTACHMENT"] = substr($str_attachment,0,-1);
						
						$var_results = db::query('insert',array('tableName'=>'EMAIL_BUFFER','fieldWithValue'=>$ary_data),$str_errMsg,false);
						if($str_errMsg) {
							throw new errors($str_errMsg);
						}
					}
					
					return true;
				}
				else {
					throw new errors("Email recipient list is empty");
				}
			}
			catch(errors $e) {
				$str_errMsgRtn = $e->message();
				return false;
			}
		}
		
		public static function markEmailSent($int_bufferID,&$str_errMsgRtn = false) {
			try {
				$ary_data = array(
					'SEND_DATE' => date("Y-m-d H:i:s")
				);
				
				$var_results = db::query('update',array('tableName'=>'EMAIL_BUFFER','fieldWithValue'=>$ary_data,'condition'=>"EMAIL_BUFFER_ID = ". $int_bufferID),$str_errMsg,false);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				return true;
			}
			catch(errors $e) {
				$str_errMsgRtn = $e->message();
				return false;
			}
		}
		
		public static function saveMenuData($int_menuID,$POST,&$str_errMsgRtn = false) {
			try {
				if($int_menuID != '') {
					
				}
				else {
					$ary_data = array(
						"DESCRIPTION" => $POST["menuname"],
						"LINK" => $POST["hyperlink"],
						"PARENTID" => $POST["menupost"]
					);
					
					$var_results = db::query('insert',array('tableName'=>'MENU_LIST','fieldWithValue'=>$ary_data),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
					
				}
				
				return $int_menuID;
			}
			catch(errors $e) {
				$str_errMsgRtn = $e->message();
				return false;
			}
		}
		
		public static function insertContractorMovement($ary_GET,$ary_POST,&$str_errMessage = false) {
			try {
				
				/* Reformat momement date to YYYY-MM-DD*/
				$ary_rearrageDtPicker = explode("-",$ary_POST['datepicker']);
				$ary_POST['datepicker'] = $ary_rearrageDtPicker[2] ."-". $ary_rearrageDtPicker[1] ."-". $ary_rearrageDtPicker[0];
				/****/
				
				$dt_today =  date('Y-m-d H:i:s');
				$dt_applied = date('Y-m-d H:i:s',strtotime($ary_POST['datepicker']));
				$str_guidNovaStyle = util::create_guid();
				
				/* Get list of Installer's activities for the applied date */
				$var_results = DataLoader::getInstallerTask($ary_GET['passNo'],$dt_applied);
				
				if(is_array($var_results)) {
					
					/* If got activity for that applied day, check for overlapping slot */
					if(count($var_results)>0) {
						
						$bool_isOverlapped = false;
						
						for($i=0;$i<count($var_results);$i++) {
							$int_slotStart = strtotime($var_results[$i]['ACTIVITY_PLANNED_START']);
							$int_slotEnd = strtotime($var_results[$i]['ACTIVITY_PLANNED_COMPLETION']);
							
							$int_moveStart = strtotime(date('Y-m-d H:i:s',strtotime($ary_POST['datepicker'] .' '. $ary_POST['starttime'])));
							$int_moveEnd = strtotime(date('Y-m-d H:i:s',strtotime($ary_POST['datepicker'] .' '. $ary_POST['endtime'])));
			
							if(($int_slotStart <= $int_moveStart && $int_moveStart <= $int_slotEnd) || ($int_slotStart <= $int_moveEnd && $int_moveEnd <= $int_slotEnd)) {
								$bool_isOverlapped .= str_replace("Movement","Unavailability",$var_results[$i]['ACTIVITY_TYPE']) ." (". (($var_results[$i]['ACTIVITY_TYPE'] != "Movement")? "Order No.: ". $var_results[$i]['ORDER_NUMBER'] ." - " : "") . date('h:i a',strtotime($var_results[$i]['ACTIVITY_PLANNED_START'])) ." - ". date('h:i a',strtotime($var_results[$i]['ACTIVITY_PLANNED_COMPLETION'])) ."), ";
							}
							elseif(($int_moveStart <= $int_slotStart && $int_slotStart <= $int_moveEnd) || ($int_moveStart <= $int_slotEnd && $int_slotEnd <= $int_moveEnd)) {
								$bool_isOverlapped .= str_replace("Movement","Unavailability",$var_results[$i]['ACTIVITY_TYPE']) ." (". (($var_results[$i]['ACTIVITY_TYPE'] != "Movement")? "Order No.: ". $var_results[$i]['ORDER_NUMBER'] ." - " : "") . date('h:i a',strtotime($var_results[$i]['ACTIVITY_PLANNED_COMPLETION'])) ."), ";
							}
						}
						
						/* If the movement time got overlapped with other slot, throw error message. Terminate process. */
						if($bool_isOverlapped) {
							throw new errors("Overlapped with slot: ". $bool_isOverlapped,false);
						}
					}
					
					/* Insert movement into database*/
					$ary_data = array(
						"DATE_APPLY" => $dt_today,
						"REASON" => $ary_POST['reason'],
						"REMARK" => trim($ary_POST['remarks']),
						"STATUS" => 1,
						"WORKER_TYPE" => $ary_GET['worker'],
						"PASSCARDNO" => $ary_GET['passNo'],
						"DATE_MOVEMENT" => $dt_applied,
						"START_TIME" => $ary_POST['starttime'],
						"END_TIME" => $ary_POST['endtime'],
						"ID" => $str_guidNovaStyle
					);
					
					$var_results = db::query('insert',array('tableName'=>'CONTRACTOR_MOVEMENT','fieldWithValue'=>$ary_data),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
					
					/* Create movement activity in BOOKING_ACTIVITY table*/
					$ary_data = array(
						"ORDER_NUMBER" => $str_guidNovaStyle,
						"ACTIVITY_ID" => $str_guidNovaStyle,
						"TEAM_ID" => $ary_GET['passNo'],
						"ACTIVITY_TYPE" => "Movement",
						"ACTIVITY_STATUS" => "Approved",
						"ACTIVITY_ASSIGN_DATE" => $dt_today,
						"ACTIVITY_PLANNED_START" => date('Y-m-d H:i:s',strtotime($ary_POST['datepicker'] .' '. $ary_POST['starttime'])),
						"ACTIVITY_PLANNED_COMPLETION" => date('Y-m-d H:i:s',strtotime($ary_POST['datepicker'] .' '. $ary_POST['endtime'])),
					);
					
					$var_results = db::query('insert',array('tableName'=>'BOOKING_ACTIVITY','fieldWithValue'=>$ary_data),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
					
					/* Calculate time used in AM & PM slot*/
					$ary_time = self::calcTimeUsed($ary_POST['datepicker'] ." ". $ary_POST['starttime'],$ary_POST['datepicker'] ." ". $ary_POST['endtime']);
					if(is_array($ary_time)) {
						$int_timeUsedAM = $ary_time[0];
						$int_timeUsedPM = $ary_time[1];
					}
					else {
						throw new errors($ary_time);
					}
					
					/* Check Installer's slot time spare for given date */
					$var_results = DataLoader::getData("AMPMSpare_TeamSchNode",array($ary_GET['passNo'],$dt_applied));
					if(!is_array($var_results)) {
						throw new errors($var_results);
					}
					
					/* Get Installer's building code*/
					if($ary_GET['worker'] == 1) {
						$var_results1 = DataLoader::getData("StaffBuildingID",array($ary_GET['passNo']));
					}
					elseif($ary_GET['worker'] == 2) {
						$var_results1 = DataLoader::getData("ContractorBuildingID",array($ary_GET['passNo']));
					}
					
					if(!is_array($var_results1)) {
						throw new errors($var_results1);
					}
					
					if(count($var_results)>0) {
						/* Update spare time in TEAM_SCHEDULE_NODE*/
						$ary_data = array(
							"AM_SPARE" => ($var_results[0]['AM_SPARE'] - $int_timeUsedAM),
							"PM_SPARE" => ($var_results[0]['PM_SPARE'] - $int_timeUsedPM)
						);
						$var_results = db::query('update',array('tableName'=>'TEAM_SCHEDULE_NODE','fieldWithValue'=>$ary_data,'condition'=>"TEAM_NAME = '". $ary_GET['passNo'] ."' and BUILDING_ID = '". $var_results1[0]['BUILDINGID'] ."' and TRUNC(SCHEDULE_DATE) = TRUNC(TO_DATE('". $dt_applied ."','yyyy-mm-dd hh24:mi:ss'))"),$str_errMsg,false);
						if($str_errMsg) {
							throw new errors($str_errMsg);
						}
					}
					else {
						/* Create new record in TEAM_SCHEDULE_NODE*/
						$ary_data = array(
							"TEAM_NAME" => $ary_GET['passNo'],
							"SCHEDULE_DATE" => $dt_applied,
							"AM_SPARE" => (240 - $int_timeUsedAM),
							"PM_SPARE" => (360 - $int_timeUsedPM),
							"BUILDING_ID" => $var_results1[0]['BUILDINGID']
						);
						$var_results = db::query('insert',array('tableName'=>'TEAM_SCHEDULE_NODE','fieldWithValue'=>$ary_data),$str_errMsg,false);
						if($str_errMsg) {
							throw new errors($str_errMsg);
						}
					}
					
					/* Get next task sequence number*/
					$var_results2 = DataLoader::getNextTaskSequenceNo($ary_GET['passNo'],$dt_applied,$var_results1[0]['BUILDINGID']);
					if(!is_int($var_results2)) {
						throw new errors($var_results2);
					}
					
					/* Insert new record into TEAM_SCHEDULE_SLOT*/
					$ary_data = array(
						"TEAM_NAME" => $ary_GET['passNo'],
						"SCHEDULE_DATE" => $dt_applied,
						"TASK_SEQUENCE" => $var_results2,
						"TASK_PRECEDENCE" => (($int_timeUsedAM==0)? 1 : 0),
						"ACTIVITY_ID" => $str_guidNovaStyle,
						"BUILDING_ID" => $var_results1[0]['BUILDINGID']
					);
					
					$var_results = db::query('insert',array('tableName'=>'TEAM_SCHEDULE_SLOT','fieldWithValue'=>$ary_data),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
					
					/* If applied date is not on the same day, update/insert into schedule_node*/
					if(util::dateDiff($dt_applied,$dt_today)>0) {
						
						$var_results = DataLoader::getData("AMPMSpare_schNode",array($ary_GET['passNo'],$dt_applied));
						if(!is_array($var_results)) {
							throw new errors($var_results);
						}
						
						if(count($var_results)>0) {
							/* Update spare time in SCHEDULE_NODE*/
							$ary_data = array(
								"AM_SPARE" => ($var_results[0]['AM_SPARE'] - $int_timeUsedAM),
								"PM_SPARE" => ($var_results[0]['PM_SPARE'] - $int_timeUsedPM)
							);
							$var_results = db::query('update',array('tableName'=>'SCHEDULE_NODE','fieldWithValue'=>$ary_data,'condition'=>"DEADLINE_NAME = '". $ary_GET['passNo'] ."' and BUILDING_ID = '". $var_results1[0]['BUILDINGID'] ."' and TRUNC(SCH_DATE) = TRUNC(TO_DATE('". $dt_applied ."','yyyy-mm-dd hh24:mi:ss'))"),$str_errMsg,false);
							if($str_errMsg) {
								throw new errors($str_errMsg);
							}
						}
						else {
							/* Create new record in SCHEDULE_NODE*/
							$ary_data = array(
								"DEADLINE_NAME" => $ary_GET['passNo'],
								"SCH_DATE" => $dt_applied,
								"AM_SPARE" => (240 - $int_timeUsedAM),
								"PM_SPARE" => (360 - $int_timeUsedPM),
								"BUILDING_ID" => $var_results1[0]['BUILDINGID']
							);
							$var_results = db::query('insert',array('tableName'=>'SCHEDULE_NODE','fieldWithValue'=>$ary_data),$str_errMsg,false);
							if($str_errMsg) {
								throw new errors($str_errMsg);
							}
						}
						
						/* Insert new record into SCHEDULE_SLOT*/
						$ary_data = array(
							"ORDER_NUMBER" => "MOVEMENT",
							"TASK_PRECEDENCE" => (($int_timeUsedAM==0)? 1 : 0),
							"ORDER_TYPE" => "Movement",
							"WORKTIME" => ($int_timeUsedAM + $int_timeUsedPM),
							"DEADLINE_NAME" => $ary_GET['passNo'],
							"ACTIVITY_ID" => $str_guidNovaStyle
						);
						
						$var_results = db::query('insert',array('tableName'=>'SCHEDULE_SLOT','fieldWithValue'=>$ary_data),$str_errMsg,false);
						if($str_errMsg) {
							throw new errors($str_errMsg);
						}
					}
					
					return true;
					
				}
				else {
					throw new errors($var_results);
				}
			}
			catch(errors $e) {
				$str_errMessage = $e->message();
			}
		}
		
		public static function cancelContractorMovement($str_guid) {
			try {
				/* Load movement record */
				$var_results1 = DataLoader::getData("ContMovementByID",array($str_guid));
				if(!is_array($var_results1)) {
					throw new errors($var_results1);
				}
				else {
					
					/* Set status = 0. Add CANCELLED in-front of remarks text*/
					$ary_data = array(
						"STATUS" => 0,
						"REMARK" => "[CANCELLED] ". $var_results1[0]['REMARK']
					);
					
					$var_results = db::query('update',array('tableName'=>'CONTRACTOR_MOVEMENT','fieldWithValue'=>$ary_data,'condition'=>"ID = '". $str_guid ."'"),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
					
					/* Delete slot from BOOKING_ACTIVITY*/
					$var_results = db::query('delete',array('tableName'=>'BOOKING_ACTIVITY','fieldWithValue'=>$ary_data,'condition'=>"ACTIVITY_ID = '". $str_guid ."'"),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
					
					/* Get total AM/PM used */
					$ary_time = self::calcTimeUsed($var_results1[0]['DATE_START_TIME'],$var_results1[0]['DATE_END_TIME']);
					if(is_array($ary_time)) {
						$int_timeUsedAM = $ary_time[0];
						$int_timeUsedPM = $ary_time[1];
					}
					else {
						throw new errors($ary_time);
					}
					
					if($var_results1[0]['WORKER_TYPE'] == 1) {
						$var_results2 = DataLoader::getData("StaffBuildingID",array($var_results1[0]['PASSCARDNO']));
					}
					else {
						$var_results2 = DataLoader::getData("ContractorBuildingID",array($var_results1[0]['PASSCARDNO']));
					}
					if(!is_array($var_results2) || count($var_results2) < 1) {
						throw new errors($var_results2);
					}
					
					/* Add back deducted minutes in TEAM_SCHEDULE_NODE */
					$var_results = DataLoader::getData("AMPMSpare_TeamSchNode",array($var_results1[0]['PASSCARDNO'],$var_results1[0]['DATE_MOVEMENT']));
					if(!is_array($var_results) || count($var_results) < 1) {
						throw new errors($var_results);
					}
					else {
						$ary_data = array(
							"AM_SPARE" => ($var_results[0]['AM_SPARE'] + $int_timeUsedAM),
							"PM_SPARE" => ($var_results[0]['PM_SPARE'] + $int_timeUsedPM)
						);
						$var_results = db::query('update',array('tableName'=>'TEAM_SCHEDULE_NODE','fieldWithValue'=>$ary_data,'condition'=>"TEAM_NAME = '". $var_results1[0]['PASSCARDNO'] ."' and BUILDING_ID = '". $var_results2[0]['BUILDINGID'] ."' and TRUNC(SCHEDULE_DATE) = TRUNC(TO_DATE('". $var_results1[0]['DATE_MOVEMENT'] ."','yyyy-mm-dd hh24:mi:ss'))"),$str_errMsg,false);
						if($str_errMsg) {
							throw new errors($str_errMsg);
						}
					}
					
					/* Remove slot TEAM_SCHEDULE_SLOT*/
					$var_results = db::query('delete',array('tableName'=>'TEAM_SCHEDULE_SLOT','condition'=>"ACTIVITY_ID = '". $str_guid ."'"),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
					
					/* Update back spare time in SCHEDULE_NODE*/
					$var_results = DataLoader::getData("AMPMSpare_schNode",array($var_results1[0]['PASSCARDNO'],$var_results1[0]['DATE_MOVEMENT']));
					if(!is_array($var_results)) {
						throw new errors($var_results);
					}
					else {
						if(count($var_results)>0) {
							$ary_data = array(
								"AM_SPARE" => ($var_results[0]['AM_SPARE'] + $int_timeUsedAM),
								"PM_SPARE" => ($var_results[0]['PM_SPARE'] + $int_timeUsedPM)
							);
							$var_results = db::query('update',array('tableName'=>'SCHEDULE_NODE','fieldWithValue'=>$ary_data,'condition'=>"DEADLINE_NAME = '". $var_results1[0]['PASSCARDNO'] ."' and BUILDING_ID = '". $var_results2[0]['BUILDINGID'] ."' and TRUNC(SCH_DATE) = TRUNC(TO_DATE('". $var_results1[0]['DATE_MOVEMENT'] ."','yyyy-mm-dd hh24:mi:ss'))"),$str_errMsg,false);
							if($str_errMsg) {
								throw new errors($str_errMsg);
							}
							/* Remove slot SCHEDULE_SLOT*/
							$var_results = db::query('delete',array('tableName'=>'SCHEDULE_SLOT','condition'=>"ACTIVITY_ID = '". $str_guid ."'"),$str_errMsg,false);
							if($str_errMsg) {
								throw new errors($str_errMsg);
							}
						}
					}
					
					return "ok";
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		public static function calcTimeUsed($dt_start, $dt_end) {
			try {
				$int_startTimeInMiliScnd = strtotime($dt_start);
				$int_endTimeInMiliScnd = strtotime($dt_end);
				$int_lowerBoundTime = strtotime(date('Y-m-d 9:30',strtotime($dt_start)));
				$int_upperBoundTime = strtotime(date('Y-m-d 19:30',strtotime($dt_start)));
				$int_midPointTime = strtotime(date('Y-m-d 13:30',strtotime($dt_start)));
				
				if(($int_startTimeInMiliScnd >= $int_lowerBoundTime && $int_startTimeInMiliScnd <= $int_midPointTime) && ($int_endTimeInMiliScnd >= $int_lowerBoundTime && $int_endTimeInMiliScnd <= $int_midPointTime)) {
					$int_timeUsedAM = abs(ceil(($int_endTimeInMiliScnd - $int_startTimeInMiliScnd)/60));
					$int_timeUsedPM = 0;
				}
				elseif(($int_startTimeInMiliScnd >= $int_lowerBoundTime && $int_startTimeInMiliScnd <= $int_midPointTime) && ($int_endTimeInMiliScnd >= $int_midPointTime && $int_endTimeInMiliScnd <= $int_upperBoundTime)) {
					$int_timeUsedAM = abs(ceil(($int_midPointTime - $int_startTimeInMiliScnd)/60));
					$int_timeUsedPM = abs(ceil(($int_endTimeInMiliScnd - $int_midPointTime)/60));
				}
				elseif(($int_startTimeInMiliScnd >= $int_midPointTime && $int_startTimeInMiliScnd <= $int_upperBoundTime) && ($int_endTimeInMiliScnd >= $int_midPointTime && $int_endTimeInMiliScnd <= $int_upperBoundTime)) {
					$int_timeUsedAM = 0;
					$int_timeUsedPM = abs(ceil(($int_endTimeInMiliScnd - $int_startTimeInMiliScnd)/60));
				}
				else {
					throw new errors("Failed to calculate spare time");
				}
				return array($int_timeUsedAM,$int_timeUsedPM);
			}
			catch(errors $e) {
				return $e->message;
			}
		}
		
		public static function deleteMenu($int_menuID) {
			try {
				
				$ary_menuSameParentID = array();
				
				$var_results = DataLoader::getData("MenuInSameParentID",array($int_menuID));
				if(!is_array($var_results)) {
					throw new errors($var_results);
				}
				else {
					$ary_menuSameParentID = $var_results;
				}
				
				$var_results = db::query('delete',array('tableName'=>'USER_GROUP_MENU_LIST','condition'=>"MENU_LIST_ID = ". $int_menuID),$str_errMsg,false);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				$var_results = db::query('delete',array('tableName'=>'MENU_LIST','condition'=>"MENU_LIST_ID = ". $int_menuID ." or PARENTID = ". $int_menuID),$str_errMsg,false);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				
				for($i=0;$i<count($ary_menuSameParentID);$i++) {
					$var_results = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>array('ORDINAL'=>($i+10)),'condition'=>'MENU_LIST_ID = '. $ary_menuSameParentID[$i]["MENU_LIST_ID"]),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
				}
				for($i=0;$i<count($ary_menuSameParentID);$i++) {
					$var_results = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>array('ORDINAL'=>($i+1)),'condition'=>'MENU_LIST_ID = '. $ary_menuSameParentID[$i]["MENU_LIST_ID"]),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
				}
				
				return "ok";
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		public static function updateSystemMenu($int_menuCat,$ary_POST,&$str_errMessage = false) {
			try {
				$int_menuID = $ary_POST['mid'];
				$int_menuPID = $ary_POST['mpid'];
				$bool_toInsert = true;
				
				$ary_data = array(
					"DESCRIPTION" => $ary_POST['menuname'],
					"LINK" => $ary_POST['hyperlink'],
					"ORDINAL" => $ary_POST['nxno'],
					"OWNER" => $int_menuCat
				);
				
				if($int_menuID == '' && $int_menuPID == '') {
					$ary_data["PARENTID"] = 0;
					$bool_toInsert = true;
				}
				elseif($int_menuID == '' &&  $int_menuPID != '') {
					$ary_data["PARENTID"] = $ary_POST['mpid'];
					$bool_toInsert = true;
				}
				elseif($int_menuID != '' &&  $int_menuPID == '') {
					$bool_toInsert = false;
					unset($ary_data["ORDINAL"]);
				}
				
				if($bool_toInsert) {
					$var_results = db::query('insert',array('tableName'=>'MENU_LIST','fieldWithValue'=>$ary_data),$str_errMsg,false);
				}
				else {
					$var_results = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>$ary_data,'condition'=>'MENU_LIST_ID='.$int_menuID),$str_errMsg,false);
				}
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				
				return true;
			}
			catch(errors $e) {
				$str_errMessage = $e->message();
				return false;
			}
		}
		
		public static function reorganizeMenu($int_menuID,$int_direction) {
			try {
				$var_results = DataLoader::getData("TopBottomMenu",array($int_menuID));
				if(is_array($var_results)) {
					if(count($var_results)>0) {
						if($int_direction == 1) {
							if($var_results[0]["TOPMENU"] != "") {
								$int_tempNo = ($var_results[0]["ORDINAL"]+10-1);
								$var_results1 = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>array('ORDINAL'=> $int_tempNo),'condition'=>'MENU_LIST_ID = '. $var_results[0]["MENU_LIST_ID"]),$str_errMsg1,false);
								if($str_errMsg1) {
									throw new errors($str_errMsg1);
								}
								$var_results1 = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>array('ORDINAL'=>($var_results[0]["ORDINAL"])),'condition'=>'MENU_LIST_ID = '. $var_results[0]["TOPMENU"]),$str_errMsg1,false);
								if($str_errMsg1) {
									throw new errors($str_errMsg1);
								}
								$var_results1 = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>array('ORDINAL'=>($int_tempNo-10)),'condition'=>'MENU_LIST_ID = '. $var_results[0]["MENU_LIST_ID"]),$str_errMsg1,false);
								if($str_errMsg1) {
									throw new errors($str_errMsg1);
								}
								return "ok";
							}
							else {
								throw new errors("Cannot move any further",false);
							}
						}
						else {
							if($var_results[0]["BOTTOMMENU"] != "") {
								$int_tempNo = ($var_results[0]["ORDINAL"]+10+1);
								$var_results1 = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>array('ORDINAL'=> $int_tempNo),'condition'=>'MENU_LIST_ID = '. $var_results[0]["MENU_LIST_ID"]),$str_errMsg1,false);
								if($str_errMsg1) {
									throw new errors($str_errMsg1);
								}
								$var_results1 = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>array('ORDINAL'=>($var_results[0]["ORDINAL"])),'condition'=>'MENU_LIST_ID = '. $var_results[0]["BOTTOMMENU"]),$str_errMsg1,false);
								if($str_errMsg1) {
									throw new errors($str_errMsg1);
								}
								$var_results1 = db::query('update',array('tableName'=>'MENU_LIST','fieldWithValue'=>array('ORDINAL'=>($int_tempNo-10)),'condition'=>'MENU_LIST_ID = '. $var_results[0]["MENU_LIST_ID"]),$str_errMsg1,false);
								if($str_errMsg1) {
									throw new errors($str_errMsg1);
								}
								return "ok";
							}
							else {
								throw new errors("Cannot move any further",false);
							}
						}
					}
					else {
						throw new errors("Undefined menu id ". $int_menuID);
					}
				}
				else {
					throw new errors($var_results);
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		public static function updateUserMenuExt($ary_POST,&$str_errMessage = false) {
			try {
				if(util::fetch('uid') != '') {
					print_r($ary_POST);
					return false;
					$str_staffNo = $ary_POST["staffno"];
					$str_sql = "";
					
					$var_results = db::query('delete',array('tableName'=>'USER_MENU_EXT','condition'=>"CAST(STAFF_NO AS INTEGER) = ". $str_staffNo),$str_errMsg,false);
					if($str_errMsg) {
						throw new errors($str_errMsg);
					}
					
					for($i=0;$i<count($ary_POST["changesBox"]);$i++) {
						if($ary_POST["changesBox"][$i] != "") {
							
							$ary_boxData = explode("_",$ary_POST["changesBox"][$i]);
							
							$ary_data = array(
								"STAFF_NO" => $str_staffNo,
								"MENU_LIST_ID" => $ary_boxData[0],
								"ACCESS_RIGHT" => $ary_boxData[1]
							);
							
							$var_results = db::query('insert',array('tableName'=>'USER_MENU_EXT','fieldWithValue'=>$ary_data),$str_errMsg,false);
							if($str_errMsg) {
								throw new errors($str_errMsg);
							}
						}
					}
					
					return true;
				}
				else {
					throw new errors('Undefined user id');
				}
			}
			catch(errors $e) {
				$str_errMessage = $e->message();
				return false;
			}
		}
		
		public static function deleteUserMenuExt($int_userID) {
			try {
				$var_results = db::query('delete',array('tableName'=>'USER_MENU_EXT','condition'=>"CAST(STAFF_NO AS INTEGER) = ". $int_userID),$str_errMsg,false);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				else {
					return "ok";
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		public static function deleteUploadeFile($str_guid) {
			try {
				$var_results = db::query('delete',array('tableName'=>'ATTACHMENT','condition'=>"ATTACHMENT_ID = '". $str_guid ."'"),$str_errMsg,false);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				else {
					return "ok";
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		public static function saveChangesInvTarget($int_areaID,$int_val,&$str_errMsgRtn = false) {
			try {
				$var_results = db::query('update',array('tableName'=>'AREA','fieldWithValue'=>array('TR_THRESHOLD'=>$int_val),'condition'=>"ID = ". $int_areaID),$str_errMsg,false);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				return true;
			}
			catch(errors $e) {
				$str_errMsgRtn = $e->message();
				return false;
			}
		}
	}
?>