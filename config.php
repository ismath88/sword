<?php
/**
 * @author	Harezad
 */
	// Set default time zone for Date()
	date_default_timezone_set("Asia/Kuala_Lumpur");
	
	// Database connection setting
	
	// SIT
	
	/* define("db_host","10.44.11.53");
	define("db_port",1521);
	define("db_user","swift");
	define("db_pass","swift123");
	define("db_schema","swiftdb");
	define("db_type","oracle"); */
	
	// CBJ6
	
	/*
	define("db_host","swift-db-scan.intra.tm");
	define("db_port",1521);
	define("db_user","swift");
	define("db_pass","5w1ft123");
	define("db_schema","SWIFT");
	define("db_type","oracle");
	*/
	
	//DEVSVR
	
	//define("db_host","10.106.132.8");
	define("db_host","10.44.11.65");
	define("db_port",1521);
	define("db_user","swift");
	define("db_pass","5w1ft123");
	define("db_schema","swiftdb");
	define("db_type","oracle");
	

	// Logical Path To System Folder
	define("sys_docRootPath", rtrim($_SERVER["DOCUMENT_ROOT"],'/\\') .'/'. ltrim(dirname($_SERVER['PHP_SELF']),'/\\'));
	
	// List of class's folder to scan
	define("sys_classFolder","core,plugin,query,modules");
	
	// Email Configuration
	define("mail_smtpServer","smtp.tmrnd.com.my"); 	
	define("mail_port",25); 
	define("mail_username","icsi_noreply@tmrnd.com.my"); 
	define("mail_password","staff123"); 
	define("mail_nickname","TM SWIFT");
	define("mail_maxBlastNo",100);
?>