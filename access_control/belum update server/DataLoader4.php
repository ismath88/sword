<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataLoader4
 *
 * @author avnish.awasthi
 */
	
	class DataLoader4 {
		
		public static function sql($str_sqlName,$ary_param) {
			$int_UBoundAryParam = 5;
			for($i=0;$i<$int_UBoundAryParam;$i++) {
				if(!isset($ary_param[$i])) {
					$ary_param[$i] = false;
				}
			}
			$ary_sql = array(
				"UserGroups" => "SELECT ROLE_ID,DESCRIPTION,ACCESS_ROLE FROM USER_GROUP ORDER BY DESCRIPTION ASC",				
				/*"Staff" => "SELECT ID,STAFF_NO,NAME FROM STAFF WHERE STAFF_NO NOT IN (SELECT STAFF_NO FROM SYSTEM_USER) AND UPPER(STAFF_STATUS)= 'ACTIVE' ORDER BY NAME ASC",*/
                                "Staff" => "SELECT * FROM ((SELECT STAFF_NO,NAME FROM STAFF WHERE UPPER(EMPLOYMENT_STATUS)= 'ACTIVE') MINUS (SELECT STAFF_NO,NAME FROM SYSTEM_USER)) ORDER BY NAME ASC",
				"AreaStates" => "SELECT ID,NAME FROM AREA WHERE UPPER(TYPE) = 'STATE' ORDER BY NAME ASC",
                                "AreaPTTs" => "SELECT ID,NAME FROM AREA WHERE PARENT_ID = '". $ary_param[0] ."' AND UPPER(TYPE) = 'PTT' ORDER BY NAME ASC",
                                "AllAreaPTTs" => "SELECT ID,NAME,DESCRIPTION FROM AREA WHERE UPPER(TYPE) = 'PTT' ORDER BY NAME ASC",
                                "AreaZones" => "SELECT ID,NAME FROM AREA WHERE PARENT_ID = '". $ary_param[0] ."' AND UPPER(TYPE) = 'ZONE' ORDER BY NAME ASC",
				"AreaBuildings" => "SELECT ID,NAME FROM AREA WHERE PARENT_ID = '". $ary_param[0] ."' AND UPPER(TYPE) = 'BUILDING' ORDER BY NAME ASC",
				"GetLastInsertSystemUserId" => "SELECT MAX(ID) AS LAST_INSERT_ID FROM SYSTEM_USER",
                                "CheckSystemUserExist"=>"SELECT COUNT(ID) AS IS_SYSTEM_USER_EXIST FROM SYSTEM_USER WHERE LOGIN ='". $ary_param[0]."'",
                                "GetSystemUserByStaffNo" => "SELECT * FROM SYSTEM_USER WHERE STAFF_NO = '". $ary_param[0] ."'",
                                "GetSystemUserAreaBySystemUserId" => "SELECT AREA.ID,AREA.NAME FROM SYSTEM_USER_AREA LEFT JOIN AREA ON SYSTEM_USER_AREA.AREA_ID = AREA.ID WHERE SYSTEM_USER_ID = '". $ary_param[0] ."'",
                                "GetEmailTemplate" => "SELECT * FROM EMAIL_TEMPLATE",
                                "GetLastInsertEmailDayEndRptId"=>"SELECT MAX(EMAIL_ENDDAYRPT_ID) AS LAST_INSERT_ID FROM EMAIL_ENDDAYRPT",
                                "GetAllEmailEndDayRPT"=>"SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY UPPER(RECIP_NAME) ASC) AS ROWNO,EMAIL_ENDDAYRPT.* FROM EMAIL_ENDDAYRPT) WHERE ROWNO BETWEEN ". $ary_param[0] ." AND ". $ary_param[1] ." ORDER BY UPPER(RECIP_NAME) ASC",
                                "GetEmailEndDayPTTByRptId"=>"SELECT EMAIL_ENDDAY_PTT_ID,EMAIL_ENDDAYRPT_ID,AREA_ID,NAME,DESCRIPTION FROM EMAIL_ENDDAY_PTT LEFT JOIN AREA ON EMAIL_ENDDAY_PTT.AREA_ID = AREA.ID  WHERE EMAIL_ENDDAYRPT_ID= '". $ary_param[0] ."'",
                                "GetAllEmailEndDayRPTById"=>"SELECT * FROM EMAIL_ENDDAYRPT WHERE EMAIL_ENDDAYRPT_ID = ". $ary_param[0],
                                "AllAvailableAreaPTTsByEmailRptId"=>"SELECT ID,NAME,DESCRIPTION FROM AREA WHERE ID NOT IN (SELECT AREA_ID FROM EMAIL_ENDDAY_PTT WHERE EMAIL_ENDDAYRPT_ID=". $ary_param[0] .") AND UPPER(TYPE) = 'PTT' ORDER BY NAME ASC",
                                "CheckEndDayRptEmailAvailability"=>"SELECT * FROM EMAIL_ENDDAYRPT WHERE EMAILADD = '". $ary_param[0]."'",
                                "GetTotalCountEndDayRpt"=>"SELECT COUNT(EMAIL_ENDDAYRPT_ID) AS TOTAL_COUNT_END_DAY_RPT FROM EMAIL_ENDDAYRPT ORDER BY UPPER(RECIP_NAME) ASC",
                                "GetTotalCountRules"=>"SELECT COUNT(ACL_RULES_ID) AS TOTAL_COUNT_RULE FROM ACL_RULES ORDER BY UPPER(RULENAME) ASC",
                                "GetAllRules"=>"SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY UPPER(RULENAME) ASC) AS ROWNO,ACL_RULES.* FROM ACL_RULES) WHERE ROWNO BETWEEN ". $ary_param[0] ." AND ". $ary_param[1] ." ORDER BY UPPER(RULENAME) ASC",
                                "GetLastInsertAclPageId"=>"SELECT MAX(ACL_PAGE_ID) AS LAST_INSERT_ID FROM ACL_PAGE",
                                "GetAllPages"=>"SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY UPPER(PAGENAME) ASC) AS ROWNO,ACL_PAGE.*,(SELECT COUNT(ACL_PAGERULES_ID) FROM ACL_PAGERULES WHERE ACL_PAGERULES.ACL_PAGE_ID = ACL_PAGE.ACL_PAGE_ID) AS TOTAL_ASSIGN_RULES FROM ACL_PAGE) WHERE ROWNO BETWEEN ". $ary_param[0] ." AND ". $ary_param[1] ." ORDER BY UPPER(PAGENAME) ASC",
                                "GetTotalCountPages"=>"SELECT COUNT(ACL_PAGE_ID) AS TOTAL_COUNT_PAGE FROM ACL_PAGE ORDER BY UPPER(PAGENAME) ASC",
                                "GetNotAssignRules"=>"SELECT * FROM ACL_RULES WHERE STATUS=1 AND ACL_RULES_ID NOT IN (SELECT DISTINCT ACL_RULES_ID FROM ACL_PAGERULES WHERE ACL_PAGE_ID = ".$ary_param[0].")",
                                "GetAssignRules"=>"SELECT * FROM ACL_RULES INNER JOIN ACL_PAGERULES ON ACL_RULES.ACL_RULES_ID = ACL_PAGERULES.ACL_RULES_ID WHERE ACL_RULES.STATUS=1 AND ACL_PAGERULES.ACL_PAGE_ID = '".$ary_param[0]."'",
                                "GetAllRulesForPage"=>"SELECT * FROM ACL_RULES WHERE STATUS=1",
                                "GetPageDetailsById"=>"SELECT * FROM ACL_PAGE WHERE ACL_PAGE_ID = '". $ary_param[0] ."'",
                                "GetRulesByPageId"=>"SELECT ACL_RULES.RULENAME,ACL_RULES.RULECODE,ACL_RULES.STATUS FROM ACL_PAGERULES LEFT JOIN ACL_RULES ON ACL_PAGERULES.ACL_RULES_ID = ACL_RULES.ACL_RULES_ID WHERE ACL_PAGE_ID = '". $ary_param[0] ."'",
                                "GetStaffByStaffNo" => "SELECT * FROM STAFF WHERE STAFF_NO = '". $ary_param[0] ."'",
                                "GetInstallerBuildingByStaffNo" => "SELECT AREA.ID,AREA.NAME FROM INSTALLER_BUILDINGID LEFT JOIN AREA ON INSTALLER_BUILDINGID.BUILDING_ID = AREA.ID WHERE INSTALLER_BUILDINGID.PASSCARDNO = '". $ary_param[0] ."'",
                                "GetUserGroupByMenuListId"=>"SELECT DISTINCT(USER_GROUP_MENU_LIST.ROLE_ID),DESCRIPTION,(SELECT COUNT(SYSTEM_USER.ID) FROM SYSTEM_USER WHERE SYSTEM_USER.ROLE_ID=USER_GROUP_MENU_LIST.ROLE_ID) AS TOTAL_USER FROM USER_GROUP_MENU_LIST  LEFT JOIN USER_GROUP ON USER_GROUP_MENU_LIST.ROLE_ID = USER_GROUP.ROLE_ID WHERE USER_GROUP_MENU_LIST.MENU_LIST_ID = '". $ary_param[0] ."'",
                                "GetUserMenuByMenuListId"=>"SELECT DISTINCT(USER_MENU_EXT.STAFF_NO),SYSTEM_USER.NAME,USER_GROUP.DESCRIPTION FROM USER_MENU_EXT LEFT JOIN SYSTEM_USER ON USER_MENU_EXT.STAFF_NO = SYSTEM_USER.STAFF_NO LEFT JOIN USER_GROUP ON SYSTEM_USER.ROLE_ID = USER_GROUP.ROLE_ID WHERE USER_MENU_EXT.MENU_LIST_ID = '". $ary_param[0] ."'"
                               
			);
			return $ary_sql[$str_sqlName];
		}
		
		public static function getData($str_sqlName,$ary_param = array(),$bool_debugMode = false) {
			try {
                           
				$var_results = db::query('',array(self::sql($str_sqlName,$ary_param),'debugMode'=>$bool_debugMode),$str_errMsg);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				else {
					return $var_results;
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		/**
		* @see		ExportEmployeeDetails.php
		*/
		public static function getEmployeeDetails() {
			try {
                                
                                $str_sql = "
					SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY UPPER(NAME) ASC) AS ROWNO,ID,MEMBER_TYPE,EMP_ID,NAME,COMPANY_NAME,STATUS,SKILL_SET,PTT_NAME,ZONE_NAME,BUILDING_NAME  FROM (
                                                (SELECT
							to_char(ID) AS ID,
							'Staff' AS MEMBER_TYPE,
							STAFF_NO AS EMP_ID,
							NAME AS NAME,
							'TM' AS COMPANY_NAME,
							UPPER(EMPLOYMENT_STATUS) AS STATUS,
                                                        (SELECT WM_CONCAT(TECHNOLOGY_SKILL_TYPE||'-'||ACTIVITY_WORK_TYPE) FROM STAFF_SKILL_SET SSS LEFT JOIN SKILL_SET SS ON SSS.SKILL_SET_ID = SS.ID  WHERE STAFF_ID=STAFF.ID)  AS SKILL_SET,
                                                        TTTA.PTT_NAME AS PTT_NAME,
                                                        TTTA.ZONE_NAME AS ZONE_NAME,
                                                        TTTA.BUILDING_NAME AS BUILDING_NAME
						FROM
							STAFF 
                                                LEFT JOIN 
                                                        (
                                                            SELECT TIB.ZONE_NAME,
                                                                TIB.STAFF_ID,
                                                                (SELECT TAP.NAME FROM AREA TAZ LEFT JOIN AREA TAP ON TAZ.PARENT_ID = TAP.ID WHERE TAZ.ID=TIB.ZONE_ID) AS PTT_NAME,
                                                                (SELECT WM_CONCAT(AREA.NAME) FROM TEAM_AREA TTA LEFT JOIN AREA ON AREA_ID = ID LEFT JOIN STAFF SS ON TTA.TEAM_ID = SS.TEAM_ID WHERE AREA.PARENT_ID =TIB.ZONE_ID AND SS.ID=TIB.STAFF_ID) AS BUILDING_NAME
                                                            FROM 
                                                            (
                                                                SELECT    S.ID AS STAFF_ID,
                                                                    PARENT_ID AS ZONE_ID,
                                                                    (SELECT NAME FROM AREA AZ WHERE AZ.ID= AB.PARENT_ID) AS ZONE_NAME 
                                                                    FROM TEAM_AREA TA
                                                                    LEFT JOIN STAFF S ON S.TEAM_ID = TA.TEAM_ID
                                                                    LEFT JOIN  AREA AB ON TA.AREA_ID = AB.ID 
                                                                    GROUP BY PARENT_ID,S.ID
                                                              )  TIB
                                                        ) TTTA ON STAFF.ID =  TTTA.STAFF_ID

                                                )
						
						
						UNION ALL
						
						(SELECT
                                                    ICNO AS ID,
                                                    'Contractor' AS MEMBER_TYPE,
                                                    VENDORID AS EMP_ID,
                                                    NAME AS NAME,
                                                    COMPANYNAME AS COMPANY_NAME,
                                                    UPPER(STATUS) AS STATUS,							
                                                    (SELECT WM_CONCAT(TECHNOLOGY_SKILL_TYPE||'-'||ACTIVITY_WORK_TYPE)  FROM INSTALLER_SKILL_SET  WHERE ICNO=INSTALLER_INFO.ICNO) AS SKILL_SET, 
                                                    TTIB.PTT_NAME AS PTT_NAME,
                                                    TTIB.ZONE_NAME AS ZONE_NAME,
                                                    TTIB.BUILDING_NAME AS BUILDING_NAME
              
						FROM
							INSTALLER_INFO 
                                                LEFT JOIN
                                                (
                                                SELECT TIB.ZONE_NAME,
                                                    TIB.TICNO,
                                                    (SELECT TAP.NAME FROM AREA TAZ LEFT JOIN AREA TAP ON TAZ.PARENT_ID = TAP.ID WHERE TAZ.ID=TIB.ZONE_ID) AS PTT_NAME,
                                                    (SELECT WM_CONCAT(AREA.NAME) FROM INSTALLER_BUILDINGID LEFT JOIN AREA ON BUILDING_ID = ID WHERE AREA.PARENT_ID =TIB.ZONE_ID AND ICNO=TIB.TICNO) AS BUILDING_NAME
                                                FROM 
                                                (
                                                    SELECT ICNO AS TICNO,
                                                        PARENT_ID AS ZONE_ID,
                                                        (SELECT NAME FROM AREA AZ WHERE AZ.ID= AB.PARENT_ID) AS ZONE_NAME 
                                                        FROM INSTALLER_BUILDINGID IB 
                                                        LEFT JOIN  AREA AB ON IB.BUILDING_ID = AB.ID 
                                                        GROUP BY PARENT_ID,ICNO
                                                 )  TIB
                                        )  TTIB ON INSTALLER_INFO.ICNO =  TTIB.TICNO
                                                       
                                 ))) 
				";
				
				$var_results = db::query('',array($str_sql),$str_errMsg);
                               
                                
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				else {
					return $var_results;
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
                
                
                
                
		
                
		/**
		* Get list of Installer's activity for particular day
		* 
		* @param	$str_passCardNo				Staff/Contractor pass card number
		* @param	$dt_showOnlySelectedDate	If supplied, return only activity for the selected date
		* @return	Return total activity for today onwards or return list of activity for the given date
		* @see		ContractorMovement.php, DataUpdater.php
		*/
		public static function getInstallerTask($str_passCardNo,$dt_showOnlySelectedDate = false) {
			try {
				$str_sql = ($dt_showOnlySelectedDate)? "(TRUNC(ACTIVITY_PLANNED_START) - TRUNC(TO_DATE('". $dt_showOnlySelectedDate ."','yyyy-mm-dd hh24:mi:ss')) = 0)" : "TRUNC(ACTIVITY_PLANNED_START) >= TRUNC(TO_DATE('". date('Y-m-d H:i:s') ."','yyyy-mm-dd hh24:mi:ss'))";
				
				$var_results = db::query('',array("SELECT ORDER_NUMBER,ACTIVITY_ID,to_char(ACTIVITY_PLANNED_START,'yyyy-mm-dd hh24:mi:ss') AS ACTIVITY_PLANNED_START,to_char(ACTIVITY_PLANNED_COMPLETION,'yyyy-mm-dd hh24:mi:ss') AS ACTIVITY_PLANNED_COMPLETION,ACTIVITY_TYPE FROM BOOKING_ACTIVITY WHERE TEAM_ID = '". $str_passCardNo ."' AND ". $str_sql ." order by ACTIVITY_PLANNED_START"),$str_errMsg);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				else {
					if($dt_showOnlySelectedDate) {
						return $var_results;
					}
					else {
						return count($var_results);
					}
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
		
		public static function getNextTaskSequenceNo($str_passCardNo,$dt_applied,$str_buildingCode) {
			try {
				$var_results = db::query('',array("SELECT MAX(TASK_SEQUENCE) AS LASTNO FROM TEAM_SCHEDULE_SLOT WHERE TEAM_NAME = '". $str_passCardNo ."' AND BUILDING_ID = '". $str_buildingCode ."' AND TRUNC(SCHEDULE_DATE) = TRUNC(TO_DATE('". $dt_applied ."','yyyy-mm-dd hh24:mi:ss'))"),$str_errMsg);
				if($str_errMsg) {
					throw new errors($str_errMsg);
				}
				else {
					if(is_int($var_results[0]['LASTNO'])) {
						return $var_results[0]['LASTNO'] + 1;
					}
					else {
						return 1;
					}
				}
			}
			catch(errors $e) {
				return $e->message();
			}
		}
                
	}
?>