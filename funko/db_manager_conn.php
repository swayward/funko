<?php
/* ********************
 * This page is called by any display page that needs to
 * read only from database
 */
require_once '../../debug.php';

class db_manager extends debug_class {
	
	//default variables
	var $result = "";
	protected $db_host = '';
	protected $db_user = '';
	protected $db_pass = '';
	protected $db_name = '';
	protected $db_user_id = 99999;
	
	function __construct() {
		
		$this->debug_class = new debug_class;
		
		if($this->is_debug() === TRUE) {
			
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
			
			$this->db_host = 'localhost';
			$this->db_user = 'funkowritedev';
			$this->db_pass = 'xxxxxxxxxxxxxxxxxxxx';
			$this->db_name = 'funko_dev';
			
		}
		else {
			
			$this->db_host = 'localhost';
			$this->db_user = 'funkowrite';
			$this->db_pass = 'xxxxxxxxxxxxxxxxxxxx';
			$this->db_name = 'funko';
			
		}
	}
	
} //END class mysql_class
?>
