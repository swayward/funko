<?php
/* *************************************
 * This file hold debug classes
 * 
 * 
 * 
 * 
 * ************************************/
/*

	//debug for classes
	if($this->debug === TRUE) {
		$this->debug_class->write_debug2("create_other_dir() Incoming ", $path_array);
	}
	
	//debug for layout
	if($page_start_functions->is_debug()) {
		$page_start_functions->write_debug2("Post ", $_POST);
	}
	
*/

date_default_timezone_set('America/New_York');

class debug_class {
	
	public $debug = FALSE; 
	public $debug_string = '';
	public $debug_class = NULL;
	public $debug_file = 'debug.html';
	
	function is_debug() { return $this->debug; }
	function set_debug($value) { $this->debug = $value; }

	
	/*************************************************************************************************
	Does debug.hmtl exist
	*************************************************************************************************/
	function debug_file_exist() {
		
		$result = FALSE;
		
		//if file exist return true
		if(file_exists($this->debug_file)) {
			$result = TRUE;
		}
		
		return $result;
		
	}
	
	/*************************************************************************************************
	Delete current debug file. Called as soon as page is called
	Example top of manage/projects.php
	
		require_once('functions/debug.php');
		$debug_class = new debug_class;
		$debug_class->delete_debug();
		$debug_string = '';
	*************************************************************************************************/
	function delete_debug() {

		//delete existing file
		if(!unlink($this->debug_file)) {
			echo '<pre class="debug">COULD NOT DELETE DEBUG FILE.</pre>' . PHP_EOL;
		}

	} //END create_debug() - delete/create debug file

	
	/*************************************************************************************************
	Write to debug.hmtl
	*************************************************************************************************/
	function write_debug2($debug_title, $dump_values) {
		
		$debug_string = $this->debug_string;
		
		$debug_string .= '<pre class="debug">';
		$debug_string .= $debug_title;
		
		if(is_array($dump_values)) {
			ob_start();
			var_dump($dump_values);
			$debug_string .= ob_get_contents();
			ob_end_clean();
		}
		else {
			$debug_string .= $dump_values;
		}
		
		$debug_string .= '</pre>' . PHP_EOL;		

		if(!$file = fopen($this->debug_file, "a")) {
			echo '<div class="debug">COULD NOT OPEN OR CREATE DEBUG FILE.</div>' . PHP_EOL;
		}
		
		if(fwrite($file, $debug_string) === FALSE) {
			echo '<div class="debug">COULD NOT WRITE DEBUG FILE.</div>' . PHP_EOL;
		}
		
		if(!fclose($file)) {
			echo '<div class="debug">COULD NOT CLOSE DEBUG FILE.</div>' . PHP_EOL;
		}

	} //END write_debug($string)
	
} //END class debug
?>
