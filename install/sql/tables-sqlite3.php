<?php
/**
 * Description:
 * This file will create a SQLite3 database.
 */
 function populate_sqlite_db ( $database, $db, $createAdmin=true ) {
   #$c = new SQLite3 ( $database, SQLITE3_OPEN_CREATE );
   $db->query("CREATE TABLE webcal_user (cal_login VARCHAR(25) NOT NULL, cal_passwd VARCHAR(255), cal_lastname VARCHAR(25), cal_firstname VARCHAR(25), cal_is_admin CHAR(1) DEFAULT 'N',cal_email VARCHAR(75) NULL,cal_enabled CHAR(1) DEFAULT 'Y',cal_telephone VARCHAR(50) NULL,cal_address VARCHAR(75) NULL,cal_title VARCHAR(75) NULL,cal_birthday INT,cal_last_login INT, PRIMARY KEY ( cal_login ))");
   if ($createAdmin) {
     $db->query("INSERT INTO webcal_user ( cal_login, cal_passwd, cal_lastname, cal_firstname, cal_is_admin ) VALUES ( 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'Default', 'Y' );");
   }
   $db->query("CREATE TABLE webcal_entry ( cal_id INT NOT NULL, cal_group_id INT NULL, cal_ext_for_id INT NULL, cal_create_by VARCHAR(25) NOT NULL, cal_date INT NOT NULL, cal_time INT NULL, cal_mod_date INT, cal_mod_time INT, cal_duration INT NOT NULL, cal_due_date INT default NULL, cal_due_time INT default NULL, cal_location varchar(100) default NULL, cal_url varchar(100) default NULL, cal_completed INT default NULL, cal_priority INT DEFAULT 5, cal_type CHAR(1) DEFAULT 'E', cal_access CHAR(1) DEFAULT 'P', cal_name VARCHAR(80) NOT NULL, cal_description TEXT, PRIMARY KEY ( cal_id ))");
   $db->query("CREATE TABLE webcal_entry_repeats ( cal_id INT DEFAULT 0 NOT NULL, cal_type VARCHAR(20), cal_end INT, cal_frequency INT DEFAULT 1, cal_days CHAR(7), cal_endtime int(11) default NULL, cal_bymonth varchar(50) default NULL, cal_bymonthday varchar(100) default NULL, cal_byday varchar(100) default NULL, cal_bysetpos varchar(50) default NULL, cal_byweekno varchar(50) default NULL, cal_byyearday varchar(50) default NULL, cal_wkst char(2) default 'MO', cal_count int(11) default NULL, PRIMARY KEY (cal_id))");
   $db->query("CREATE TABLE webcal_entry_repeats_not ( cal_id INT NOT NULL, cal_date INT NOT NULL, cal_exdate INT NOT NULL default '1', PRIMARY KEY ( cal_id, cal_date ))");
   $db->query("CREATE TABLE webcal_entry_user ( cal_id INT DEFAULT 0 NOT NULL, cal_login VARCHAR(25) NOT NULL, cal_status CHAR(1) DEFAULT 'A', cal_category INT DEFAULT NULL, cal_percent INT NOT NULL default '0', PRIMARY KEY ( cal_id, cal_login ))");
   $db->query("CREATE TABLE webcal_entry_ext_user ( cal_id INT DEFAULT 0 NOT NULL, cal_fullname VARCHAR(50) NOT NULL, cal_email VARCHAR(75) NULL, PRIMARY KEY ( cal_id, cal_fullname ))");
   $db->query("CREATE TABLE webcal_user_pref ( cal_login VARCHAR(25) NOT NULL, cal_setting VARCHAR(25) NOT NULL, cal_value VARCHAR(100) NULL, PRIMARY KEY ( cal_login, cal_setting ))");
   $db->query("CREATE TABLE webcal_user_layers ( cal_layerid INT DEFAULT 0 NOT NULL, cal_login VARCHAR(25) NOT NULL, cal_layeruser VARCHAR(25) NOT NULL, cal_color VARCHAR(25) NULL, cal_dups CHAR(1) DEFAULT 'N', PRIMARY KEY ( cal_login, cal_layeruser ))");
   $db->query("CREATE TABLE webcal_site_extras ( cal_id INT DEFAULT 0 NOT NULL, cal_name VARCHAR(25) NOT NULL, cal_type INT NOT NULL, cal_date INT DEFAULT 0, cal_remind INT DEFAULT 0, cal_data TEXT)");
   $db->query("CREATE TABLE webcal_reminders (cal_id INT DEFAULT 0 NOT NULL,cal_date INT DEFAULT 0 NOT NULL,cal_offset INT DEFAULT 0 NOT NULL,cal_related CHAR(1) DEFAULT 'S' NOT NULL,cal_before CHAR(1) DEFAULT 'Y' NOT NULL,cal_last_sent INT DEFAULT NULL,cal_repeats INT DEFAULT 0 NOT NULL,cal_duration INT DEFAULT 0 NOT NULL,cal_times_sent INT DEFAULT 0 NOT NULL,cal_action VARCHAR(12) DEFAULT 'EMAIL' NOT NULL,PRIMARY KEY ( cal_id ))");
   $db->query("CREATE TABLE webcal_group ( cal_group_id INT NOT NULL, cal_owner VARCHAR(25) NULL, cal_name VARCHAR(50) NOT NULL, cal_last_update INT NOT NULL, PRIMARY KEY ( cal_group_id ))");
   $db->query("CREATE TABLE webcal_group_user ( cal_group_id INT NOT NULL, cal_login VARCHAR(25) NOT NULL, PRIMARY KEY ( cal_group_id, cal_login ))");
   $db->query("CREATE TABLE webcal_view ( cal_view_id INT NOT NULL, cal_owner VARCHAR(25) NOT NULL, cal_name VARCHAR(50) NOT NULL, cal_view_type CHAR(1), cal_is_global CHAR(1) DEFAULT 'N' NOT NULL, PRIMARY KEY ( cal_view_id ))");
   $db->query("CREATE TABLE webcal_view_user ( cal_view_id INT NOT NULL, cal_login VARCHAR(25) NOT NULL, PRIMARY KEY ( cal_view_id, cal_login ))");
   $db->query("CREATE TABLE webcal_config ( cal_setting VARCHAR(50) NOT NULL, cal_value VARCHAR(100) NULL, PRIMARY KEY ( cal_setting ))");
   $db->query("CREATE TABLE webcal_entry_log ( cal_log_id INT NOT NULL, cal_entry_id INT NOT NULL, cal_login VARCHAR(25) NOT NULL, cal_user_cal VARCHAR(25) NULL, cal_type CHAR(1) NOT NULL, cal_date INT NOT NULL, cal_time INT NULL, cal_text TEXT, PRIMARY KEY ( cal_log_id ))");
   $db->query("CREATE TABLE webcal_categories ( cat_id INT NOT NULL, cat_owner VARCHAR(25) NULL, cat_name VARCHAR(80) NOT NULL,cat_color VARCHAR(8) NULL, PRIMARY KEY ( cat_id ))");
   $db->query("CREATE TABLE webcal_asst ( cal_boss VARCHAR(25) NOT NULL, cal_assistant VARCHAR(25) NOT NULL, PRIMARY KEY ( cal_boss, cal_assistant ))");
   $db->query("CREATE TABLE webcal_nonuser_cals ( cal_login VARCHAR(25) NOT NULL, cal_lastname VARCHAR(25) NULL, cal_firstname VARCHAR(25) NULL, cal_admin VARCHAR(25) NOT NULL, cal_is_public CHAR(1) DEFAULT 'N' NOT NULL, cal_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY ( cal_login ))");
   $db->query("CREATE TABLE webcal_import ( cal_import_id INT NOT NULL, cal_name VARCHAR(50) NULL, cal_date INT NOT NULL, cal_check_date INT NULL, cal_type VARCHAR(10) NOT NULL, cal_login VARCHAR(25) NULL, cal_md5 VARCHAR(32) NULL, PRIMARY KEY ( cal_import_id ))");
   $db->query("CREATE TABLE webcal_import_data ( cal_import_id INT NOT NULL, cal_id INT NOT NULL, cal_login VARCHAR(25) NOT NULL, cal_import_type VARCHAR(15) NOT NULL, cal_external_id VARCHAR(200) NULL, PRIMARY KEY  ( cal_id, cal_login ))");
   $db->query("CREATE INDEX webcal_import_data_type ON webcal_import_data(cal_import_type)");
   $db->query("CREATE INDEX webcal_import_data_ext_id ON webcal_import_data(cal_external_id)");
   $db->query("CREATE TABLE webcal_report ( cal_login VARCHAR(25) NOT NULL, cal_report_id INT NOT NULL, cal_is_global CHAR(1) DEFAULT 'N' NOT NULL, cal_report_type VARCHAR(20) NOT NULL, cal_include_header CHAR(1) DEFAULT 'Y' NOT NULL, cal_report_name VARCHAR(50) NOT NULL, cal_time_range INT NOT NULL, cal_user VARCHAR(25) NULL, cal_allow_nav CHAR(1) DEFAULT 'Y', cal_cat_id INT NULL, cal_include_empty CHAR(1) DEFAULT 'N', cal_show_in_trailer CHAR(1) DEFAULT 'N', cal_update_date INT NOT NULL, PRIMARY KEY ( cal_report_id ))");
   $db->query("CREATE TABLE webcal_report_template ( cal_report_id INT NOT NULL, cal_template_type CHAR(1) NOT NULL, cal_template_text TEXT, PRIMARY KEY ( cal_report_id, cal_template_type ))");
   $db->query("CREATE TABLE webcal_access_user ( cal_login VARCHAR(25) NOT NULL, cal_other_user VARCHAR(25) NOT NULL, cal_can_view INT NOT NULL DEFAULT '0', cal_can_edit INT NOT NULL DEFAULT '0', cal_can_approve INT NOT NULL DEFAULT '0', cal_can_invite CHAR(1) NOT NULL DEFAULT 'Y', cal_can_email CHAR(1) NOT NULL DEFAULT 'Y', cal_see_time_only CHAR(1) NOT NULL DEFAULT 'N', PRIMARY KEY ( cal_login, cal_other_user ))");
   $db->query("CREATE TABLE webcal_access_function ( cal_login VARCHAR(25) NOT NULL, cal_permissions VARCHAR(64) NOT NULL, PRIMARY KEY ( cal_login ))");
   $db->query("CREATE TABLE webcal_user_template ( cal_login VARCHAR(25) NOT NULL default '', cal_type CHAR(1) NOT NULL default '', cal_template_text text, PRIMARY KEY  (cal_login,cal_type))");
   $db->query("CREATE TABLE webcal_entry_categories (cal_id INT NOT NULL default '0', cat_id INT NOT NULL default '0', cat_order INT NOT NULL default '0', cat_owner VARCHAR(25) default NULL, PRIMARY KEY(cal_id))");
   $db->query("CREATE INDEX webcal_entry_categories_cat_id ON webcal_entry_categories(cat_id)");
   $db->query("CREATE TABLE webcal_blob ( cal_blob_id INT NOT NULL, cal_id INT NULL, cal_login VARCHAR(25) NULL, cal_name VARCHAR(30) NULL, cal_description VARCHAR(128) NULL, cal_size INT NULL, cal_mime_type VARCHAR(50) NULL, cal_type CHAR(1) NOT NULL, cal_mod_date INT NOT NULL, cal_mod_time INT NOT NULL, cal_blob BLOB, PRIMARY KEY ( cal_blob_id ))");
   $db->query("CREATE TABLE webcal_timezones (tzid varchar(100) NOT NULL default '', dtstart varchar(25) default NULL, dtend varchar(25) default NULL, vtimezone text, PRIMARY KEY  ( tzid ))");
   #$c->close ();
}

?>
