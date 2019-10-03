<?php
	
	class DataLoader {
		
		public static function sql($str_sqlName,$ary_param) {
			$int_UBoundAryParam = 5;
			for($i=0;$i<$int_UBoundAryParam;$i++) {
				if(!isset($ary_param[$i])) {
					$ary_param[$i] = false;
				}
			}
			$ary_sql = array(
				"ContractorMovement" => "SELECT ID,to_char(DATE_APPLY,'yyyy-mm-dd hh24:mi:ss') as DATE_APPLY,REASON,REMARK,DATE_MOVEMENT,START_TIME,END_TIME FROM CONTRACTOR_MOVEMENT WHERE PASSCARDNO = '". $ary_param[0] ."' AND STATUS = 1 ORDER BY DATE_MOVEMENT DESC,to_date(START_TIME,'HH24:MI') DESC",
				"ContMovementByID" => "SELECT ID, REMARK, PASSCARDNO, WORKER_TYPE, to_char(DATE_MOVEMENT,'yyyy-mm-dd') as DATE_MOVEMENT, to_char(DATE_MOVEMENT,'yyyy-mm-dd') || ' ' || START_TIME as DATE_START_TIME, to_char(DATE_MOVEMENT,'yyyy-mm-dd') || ' ' || END_TIME as DATE_END_TIME FROM CONTRACTOR_MOVEMENT WHERE ID = '". $ary_param[0] ."'",
				"StaffBuildingID" => "SELECT (SELECT NAME FROM AREA WHERE ID = B.BUILDING_ID AND ROWNUM = 1) AS BUILDINGID FROM INSTALLER_BUILDINGID B WHERE B.PASSCARDNO = '". $ary_param[0] ."'", //"StaffBuildingID" => "SELECT AREA.NAME AS BUILDINGID FROM TEAM_AREA, TEAM, STAFF, AREA WHERE TEAM_AREA.AREA_ID = AREA.ID AND TEAM_AREA.TEAM_ID = TEAM.ID AND STAFF.TEAM_ID = TEAM.ID AND STAFF.STAFF_NO='". $ary_param[0] ."'",
				"ContractorBuildingID" => "SELECT (SELECT NAME FROM AREA WHERE ID = B.BUILDING_ID AND ROWNUM < 2) AS BUILDINGID FROM INSTALLER_INFO I INNER JOIN INSTALLER_BUILDINGID B ON I.PASSCARDNO = B.PASSCARDNO WHERE I.PASSCARDNO = '". $ary_param[0] ."'",
				"AMPMSpare_TeamSchNode" => "SELECT AM_SPARE,PM_SPARE FROM TEAM_SCHEDULE_NODE WHERE TEAM_NAME = '". $ary_param[0] ."' And (Trunc(Schedule_Date) - Trunc(To_Date('". $ary_param[1] ."','yyyy-mm-dd hh24:mi:ss'))) = 0",
				"AMPMSpare_schNode" => "SELECT AM_SPARE,PM_SPARE FROM SCHEDULE_NODE WHERE DEADLINE_NAME = '". $ary_param[0] ."' And (Trunc(SCH_DATE) - Trunc(To_Date('". $ary_param[1] ."','yyyy-mm-dd hh24:mi:ss'))) = 0",
				"SystemUsers" => "SELECT ID,STAFF_NO,NAME,(SELECT DESCRIPTION FROM USER_GROUP WHERE ROLE_ID = ROLE_ID AND ROWNUM = 1) AS GROUPNM FROM SYSTEM_USER WHERE ROWNUM <= 10 ORDER BY NAME ASC",
				"SysUserInfo" => "SELECT U.STAFF_NO,U.NAME,U.ROLE_ID,(SELECT DESCRIPTION FROM USER_GROUP WHERE ROLE_ID = U.ROLE_ID AND ROWNUM = 1) AS GROUPNM FROM SYSTEM_USER U WHERE U.ID = ". $ary_param[0],
				"MenuList" => "SELECT * FROM MENU_LIST ". (($ary_param[0] != "")? "WHERE OWNER = ".  $ary_param[0] : "") ." ORDER BY PARENTID,ORDINAL",
				//"UserOvrwMenu" => "SELECT MENU_LIST_ID FROM (SELECT GM.MENU_LIST_ID FROM USER_GROUP_MENU_LIST GM INNER JOIN SYSTEM_USER U ON U.ROLE_ID = GM.ROLE_ID WHERE U.STAFF_NO = '". $ary_param[0] ."' UNION SELECT MENU_LIST_ID FROM USER_MENU_EXT WHERE ACCESS_RIGHT = '1' AND STAFF_NO = '". $ary_param[0] ."') X WHERE NOT MENU_LIST_ID IN (SELECT MENU_LIST_ID FROM USER_MENU_EXT WHERE ACCESS_RIGHT = '0' AND STAFF_NO = '". $ary_param[0] ."')",
				"UserOvrwMenu" => "SELECT G.MENU_LIST_ID,1 AS DBSOURCE FROM SYSTEM_USER U INNER JOIN USER_GROUP_MENU_LIST G ON U.ROLE_ID = G.ROLE_ID WHERE U.ID = ". $ary_param[0] ." AND NOT G.MENU_LIST_ID IN (SELECT MENU_LIST_ID FROM USER_MENU_EXT WHERE ACCESS_RIGHT = '0' AND CAST(STAFF_NO AS INTEGER) = U.ID) UNION SELECT MENU_LIST_ID, 2 AS DBSOURCE FROM USER_MENU_EXT WHERE ACCESS_RIGHT = '1' AND CAST(STAFF_NO AS INTEGER) = ". $ary_param[0],
				//"UserOvrwMenuXML" => "SELECT X.MENU_LIST_ID,M.DESCRIPTION,M.LINK,M.PARENTID FROM (SELECT GM.MENU_LIST_ID FROM USER_GROUP_MENU_LIST GM INNER JOIN SYSTEM_USER U ON U.ROLE_ID = GM.ROLE_ID WHERE U.STAFF_NO = '". $ary_param[0] ."' UNION SELECT MENU_LIST_ID FROM USER_MENU_EXT WHERE ACCESS_RIGHT = '1' AND STAFF_NO = '". $ary_param[0] ."') X INNER JOIN MENU_LIST M ON X.MENU_LIST_ID = M.MENU_LIST_ID WHERE M.OWNER = ". $ary_param[1] ." AND NOT X.MENU_LIST_ID IN (SELECT MENU_LIST_ID FROM USER_MENU_EXT WHERE ACCESS_RIGHT = '0' AND STAFF_NO = '". $ary_param[0] ."') ORDER BY M.PARENTID,M.ORDINAL",
				"UserOvrwMenuXML" => "SELECT MENU_LIST_ID,DESCRIPTION,LINK,PARENTID FROM MENU_LIST WHERE OWNER = ". $ary_param[1] ." AND MENU_LIST_ID IN (SELECT G.MENU_LIST_ID FROM SYSTEM_USER U INNER JOIN USER_GROUP_MENU_LIST G ON U.ROLE_ID = G.ROLE_ID WHERE U.ID = ". $ary_param[0] ." AND NOT G.MENU_LIST_ID IN (SELECT MENU_LIST_ID FROM USER_MENU_EXT WHERE ACCESS_RIGHT = '0' AND CAST(STAFF_NO AS INTEGER) = U.ID) UNION SELECT MENU_LIST_ID FROM USER_MENU_EXT WHERE ACCESS_RIGHT = '1' AND CAST(STAFF_NO AS INTEGER) = ". $ary_param[0] .") ORDER BY PARENTID,ORDINAL",
				"TopBottomMenu" => "SELECT M.MENU_LIST_ID,M.ORDINAL,(SELECT MENU_LIST_ID FROM MENU_LIST WHERE PARENTID=M.PARENTID AND ORDINAL=M.ORDINAL-1) AS TOPMENU,(SELECT MENU_LIST_ID FROM MENU_LIST WHERE PARENTID=M.PARENTID AND ORDINAL=M.ORDINAL+1) AS BOTTOMMENU FROM MENU_LIST M WHERE M.MENU_LIST_ID = ". $ary_param[0],
				"MenuInSameParentID" => "SELECT MENU_LIST_ID,ORDINAL FROM (SELECT M.MENU_LIST_ID,M.ORDINAL FROM MENU_LIST M INNER JOIN MENU_LIST X ON M.PARENTID = X.PARENTID WHERE X.MENU_LIST_ID = ". $ary_param[0] ." ORDER BY M.ORDINAL) W WHERE W.MENU_LIST_ID <> ". $ary_param[0],
				"uploadFile" => "SELECT ATTACHMENT_ID, FILENAME,to_char(DATETIME,'yyyy-mm-dd hh24:mi:ss') as DATETIME,FILESIZE FROM ATTACHMENT WHERE WO_NUMBER = '". $ary_param[0] ."' AND ACTIVITY_ID = '". $ary_param[1] ."' ORDER BY DATETIME DESC",
				"blobData" => "SELECT RAW_FILE,FILENAME FROM ATTACHMENT WHERE ATTACHMENT_ID = '".  $ary_param[0] ."'",
				"PTTList" => "SELECT DISTINCT A.ID,A.NAME,A.DESCRIPTION FROM OPRTDASHBRD_EOD E INNER JOIN AREA A ON E.PTT_ID = A.ID ORDER BY A.DESCRIPTION",
				"PTTZone" => "SELECT X.ZONE_ID,(SELECT DESCRIPTION FROM AREA WHERE ID = X.ZONE_ID) AS ZONENAME,X.BUILDING_ID,(SELECT SUB_AREA FROM AREA WHERE ID = X.BUILDING_ID) AS LMG,X.ACT_TT_AM,X.TOTAL_TT,X.TOTAL_COMPLETED,X.ACT_TT_EOD,X.TT_LESS_8H,X.TT_LESS_16H,X.TT_LESS_24H,X.TT_LESS_2D,X.TT_MORE_5D,X.INV_TARGET,X.VARIANCE,X.TT_LESS_5D FROM OPRTDASHBRD_EOD X WHERE X.PTT_ID = ". $ary_param[0] ." AND TO_CHAR(X.RPT_DATE,'DD/MM/YYYY') = TO_CHAR(SYSDATE,'DD/MM/YYYY')",
				"EmailQueue" => "SELECT EMAIL_BUFFER_ID,RECIPIENT,FULLNAME,SUBJECT,BODYTEXT,ATTACHMENT,to_char(CREATED_DATE,'yyyy-mm-dd hh24:mi:ss') as CREATED_DATE FROM EMAIL_BUFFER WHERE SEND_DATE IS NULL AND ROWNUM < ". $ary_param[0] ." ORDER BY CREATED_DATE ASC",
				"RecipientEoDRpt" => "SELECT R.RECIP_NAME,R.EMAILADD,P.AREA_ID,(SELECT DESCRIPTION FROM AREA WHERE ID = P.AREA_ID) AS PTTNAME FROM EMAIL_ENDDAYRPT R INNER JOIN EMAIL_ENDDAY_PTT P ON R.EMAIL_ENDDAYRPT_ID = P.EMAIL_ENDDAYRPT_ID WHERE R.ENABLE = 1",
				"EmailTemplate" => "SELECT * FROM EMAIL_TEMPLATE WHERE ROWNUM = 1",
				"PTTReportByDate" => "SELECT (SELECT DESCRIPTION FROM AREA WHERE ID = X.ZONE_ID) AS ZONENAME,X.BUILDING_ID,(SELECT DESCRIPTION FROM AREA WHERE ID=X.BUILDING_ID) AS BUILDNAME,(SELECT SUB_AREA FROM AREA WHERE ID = X.BUILDING_ID) AS LMG,X.ACT_TT_AM,X.TOTAL_TT,X.TOTAL_COMPLETED,X.ACT_TT_EOD,X.TT_LESS_8H,X.TT_LESS_16H,X.TT_LESS_24H,X.TT_LESS_2D,X.TT_MORE_5D,X.INV_TARGET,X.VARIANCE,TT_LESS_5D FROM OPRTDASHBRD_EOD X WHERE X.PTT_ID = ". $ary_param[0] ." AND TO_CHAR(X.RPT_DATE,'YYYY-MM-DD') = '". $ary_param[1] ."'",
				"PublicHoliday" => "SELECT TO_CHAR(DATE_HOLIDAY,'YYYY-MM-DD') AS DATE_HOLIDAY,UPPER(DESCRIPTION) AS DESCRIPTION FROM PUBLIC_HOLIDAY WHERE EXTRACT(YEAR FROM DATE_HOLIDAY) = EXTRACT(YEAR FROM SYSDATE) AND UPPER(DESCRIPTION) IN ('CHINESE NEW YEAR DAY 1 (MY)','CHINESE NEW YEAR DAY 2 (MY)','HARI RAYA PUASA DAY 1 (MY)','HARI RAYA PUASA DAY 2 (MY)','HARI RAYA QURBAN DAY 1 (MY)') GROUP BY DESCRIPTION, DATE_HOLIDAY ORDER BY DATE_HOLIDAY,DESCRIPTION",
				"EAIlogByTTNo" => "SELECT EAI_ID,EXT_MSG_ID,EVENT_NAME,TO_CHAR(AUDIT_DATETIME,'yyyy-mm-dd hh24:mi:ss') AS AUDIT_DATETIME,AUDIT_PARAM1,AUDIT_PARAM2,EAI_ENDPOINT FROM EAI_LOG WHERE EXT_MSG_ID = '". $ary_param[0] ."' ORDER BY AUDIT_DATETIME DESC",
				"PTTListByUserID" => "SELECT V.ID,V.NAME,V.DESCRIPTION FROM AREA X INNER JOIN (AREA W INNER JOIN AREA V on W.PARENT_ID = V.ID) ON X.PARENT_ID = W.ID WHERE X.ID IN (SELECT DISTINCT AREA_ID  FROM SYSTEM_USER_AREA WHERE SYSTEM_USER_ID = ". $ary_param[0] .") AND X.DEPLOY_FLAG = 1 GROUP BY V.NAME,V.DESCRIPTION,V.ID",
				"AllBuildingList" => "SELECT V.DESCRIPTION AS PTT_NAME,W.DESCRIPTION AS ZONE_NAME,X.ID AS BUILDING_ID,X.DESCRIPTION AS BUILD_NAME,X.TR_THRESHOLD AS INV_TARGET FROM AREA X INNER JOIN (AREA W INNER JOIN AREA V on W.PARENT_ID = V.ID) ON X.PARENT_ID = W.ID WHERE V.TYPE='PTT' AND X.DEPLOY_FLAG = 1 ORDER BY V.NAME,W.NAME,X.NAME",
				"BuildingListByUID" => "SELECT V.DESCRIPTION AS PTT_NAME,W.DESCRIPTION AS ZONE_NAME,X.ID AS BUILDING_ID,X.DESCRIPTION AS BUILD_NAME,X.TR_THRESHOLD AS INV_TARGET FROM AREA X INNER JOIN (AREA W INNER JOIN AREA V on W.PARENT_ID = V.ID) ON X.PARENT_ID = W.ID WHERE X.ID IN (SELECT DISTINCT AREA_ID FROM SYSTEM_USER_AREA WHERE SYSTEM_USER_ID = ". $ary_param[0] .") AND X.DEPLOY_FLAG = 1 ORDER BY V.NAME,W.NAME,X.NAME",
				"EAIlogByID" => "SELECT * FROM EAI_LOG WHERE EAI_ID = ". $ary_param[0]
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
		* @see		ContractorAuditTrail.php
		*/
		public static function loadConStaffLeaveMovement($str_passCdNo) {
			try {
				$str_sql = "
					SELECT * FROM (
						SELECT
							1 AS SOURCETB,
							TO_CHAR(DATE_CAL,'yyyy-mm-dd hh24:mi:ss') AS START_DATE,
							TO_CHAR(DATE_END,'yyyy-mm-dd hh24:mi:ss') AS DATE_END,
							NULL AS START_TIME,
							NULL AS END_TIME,
							DESC_LEAVE AS REASON,
							STATUS AS REMARKS,
							TO_CHAR(CREATED_DATE,'yyyy-mm-dd hh24:mi:ss') AS CREATED_DATE
						FROM
							WORKINGCALENDAR
						WHERE
							STAFF_NO = '". $str_passCdNo ."'
						UNION ALL
						SELECT
							2 AS SOURCETB,
							TO_CHAR(DATE_MOVEMENT,'yyyy-mm-dd hh24:mi:ss') AS START_DATE,
							TO_CHAR(DATE_MOVEMENT,'yyyy-mm-dd hh24:mi:ss') AS DATE_END,
							START_TIME,
							END_TIME,
							REASON,
							REMARK AS REMARKS,
							TO_CHAR(DATE_APPLY,'yyyy-mm-dd hh24:mi:ss') AS CREATED_DATE
						FROM
							CONTRACTOR_MOVEMENT
						WHERE
							PASSCARDNO = '". $str_passCdNo ."'
					) Z ORDER BY START_DATE DESC
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