<?php
require_once('../../funko/db_manager_conn.php');

class edit_funko extends db_manager {
	
	
	private $funko_character_id = 0;
	private $funko_number = 0;
	private $funko_owned = 0;
	private $funko_series = '';
	private $funko_character = '';
	private $funko_status = 0;
	private $exclusive = '';
	private $funko_notes = '';
	private $funko_available_date = '';
	private $funko_tags = '';
	private $funko_quantity = 0;
	private $funko_value = 0.00;
	private $funko_value_date = '';
	private $funko_value_source = '';
	private $funko_price = 0.00;
	private $funko_purchased_date = '';
	private $funko_seller = '';
	private $funko_barcode = 0;
	private $funko_image = '';
	private $funko_ordered_date = '';
	private $insert_date = '';
	private $insert_by = '';
	private $update_date = '';
	private $update_by = '';
	private $clean_array = [];
	private $results = 'result';
	private $results_color = 'bg-light text-dark';

    private $target_dir = "../funko/images/"; //this is the path from funko_management to funko for moving image. Only used in funko_management.
    private $database_dir = "images/"; //this is the path for the database so that the image will display from the funko path

	
    /*
     * getters
     *
     */
	function get_funko_character_id() { return $this->funko_character_id; }
	function get_funko_number() { return $this->funko_number; }
	function get_funko_owned() { return $this->funko_owned; }
	function get_funko_series() { return $this->funko_series; }
	function get_funko_character() { return $this->funko_character; }
	function get_funko_status() { return $this->funko_status; }
	function get_exclusive() { return $this->exclusive; }
	function get_funko_notes() { return $this->funko_notes; }
	function get_funko_available_date() { return $this->funko_available_date; }
	function get_funko_tags() { return $this->funko_tags; }
	function get_funko_quantity() { return $this->funko_quantity; }

	function get_funko_value() { return $this->funko_value; }
	function get_funko_value_date() { return $this->funko_value_date; }
	function get_funko_value_source() { return $this->funko_value_source; }

	function get_funko_price() { return $this->funko_price; }
	function get_funko_purchased_date() { return $this->funko_purchased_date; }
	function get_funko_seller() { return $this->funko_seller; }

	function get_funko_barcode() { return $this->funko_barcode; }
	function get_funko_image() { return $this->funko_image; }

	function get_funko_ordered_date() { return $this->funko_ordered_date; }
	function get_insert_date() { return $this->insert_date; }
	function get_insert_by() { return $this->insert_by; }
	function get_update_date() { return $this->update_date; }
	function get_update_by() { return $this->update_by; }
	function get_results() { return $this->results; }
	function get_results_color() { return $this->results_color; }
	/*
	$funko_number = $edit_funko->get_funko_number();
	$funko_owned = $edit_funko->get_funko_owned();
	$funko_series = $edit_funko->get_funko_series();
	$funko_character = $edit_funko->get_funko_character();
	$funko_status = $edit_funko->get_funko_status();
	$funko_notes = $edit_funko->get_funko_notes();
	$funko_available_date = $edit_funko->get_funko_available_date();
	$funko_tags = $edit_funko->get_funko_tags();
	$funko_quantity = $edit_funko->get_funko_quantity();
	$funko_price = $edit_funko->get_funko_price();
	$funko_ordered = $edit_funko->get_funko_ordered();
	$funko_seller = $edit_funko->get_funko_seller();
	$funko_barcode = $edit_funko->get_funko_barcode();
	$funko_image = $edit_funko->get_funko_image();
	 */
	
	/**
	 * 
	 */
	public function edit_funko_do() {
		
		//DELETE was called
		if(isset($_POST['btn_delete'])) {
			if($this->funko_character_id === 0) {
				$this->results = "Character ID was: $this->funko_character_id";
				$this->results_color = 'bg-danger text-white';
			}
			elseif($this->funko_character_id > 0) {
				$delete_funko = $this->delete_funko();
				if($delete_funko === TRUE) {
					//sucessfully deleted funko
					$this->results = "Character ID was: $this->funko_character_id was deleted";
					$this->results_color = 'bg-success text-white';
					$this->set_post(TRUE); //clear out any assignments
				}
				elseif(is_string($delete_funko)) {
					$this->results = $delete_funko;
					$this->results_color = 'bg-danger text-white';
					$get_funko_by_id = $this->get_funko_by_id();
					if(is_array($get_funko_by_id)) {
						$this->set_values($get_funko_by_id);
					}
					elseif(is_string($get_funko_by_id)) {
						$this->results = $get_funko_by_id;
						$this->results_color = 'bg-danger text-white';
					}
				}
			}
		}
		//SAVE was called
		elseif(isset($_POST['btn_save'])) {
			//INSERT
			if($this->funko_character_id === 0) {
				//insert record
				$insert_funko = $this->insert_funko();
				if(intval($insert_funko)) {
					$this->funko_character_id = $insert_funko;
					$this->results = 'Successful Insert';
					$this->results_color = 'bg-success text-white';
					$get_funko_by_id = $this->get_funko_by_id();
					if(is_array($get_funko_by_id)) {
						$this->set_values($get_funko_by_id);
					}
					elseif(is_string($get_funko_by_id)) {
						$this->results = $get_funko_by_id;
						$this->results_color = 'bg-danger text-white';
					}
				}
				elseif(is_string($insert_funko)) {
					$this->results = $insert_funko;
					$this->results_color = 'bg-danger text-white';
				}
			}
			//UPDATE
			elseif($this->funko_character_id > 0) {
				//update record
				$update_funko = $this->update_funko();
				if($update_funko === TRUE) {
					$this->results = 'Successful Update';
					$this->results_color = 'bg-success text-white';
					$get_funko_by_id = $this->get_funko_by_id();
					if(is_array($get_funko_by_id)) {
						$this->set_values($get_funko_by_id);
					}
					elseif(is_string($get_funko_by_id)) {
						$this->results = $get_funko_by_id;
						$this->results_color = 'bg-danger text-white';
					}
				}
				elseif(is_string($update_funko)) {
					$this->results = $update_funko;
					$this->results_color = 'bg-danger text-white';
				}
			}
		}
        //upload image
        elseif(isset($_POST['btn_upload'])) {
            //reject upload, there is no character id to tie image to
            if($this->funko_character_id === 0) {
				$this->results = 'Please Save Funko First.';
				$this->results_color = 'bg-danger text-white';
            }
            // save image
            elseif($this->funko_character_id > 0) {
                $upload_image = $this->upload_image_do();
            }
        }
		elseif(isset($_POST['btn_clear'])) {
			//values should be reset by set_post
		}
		//Search for funko_character_id
		elseif($this->funko_character_id > 0) {
			$get_funko_by_id = $this->get_funko_by_id();
			if(is_array($get_funko_by_id)) {
				$this->set_values($get_funko_by_id);
			}
			elseif(is_string($get_funko_by_id)) {
				$this->results = $get_funko_by_id;
				$this->results_color = 'bg-danger text-white';
				$this->set_post(TRUE); //clear out any assignments
			}
		}
		
	}
	
	/**
	 * Clean and Set values coming from POST or GET
	 * Clean values go to insert and update
	 * Set values go back to html
	 */
	public function set_post($clear = FALSE) {
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("set_post() POST ", $_POST);
		}
		
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("set_post() GET ", $_GET);
		}
		
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("set_post() FILES ", $_FILES);
		}
		
		$clean_array = [];
		
		// $this->funko_character_id
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_character_id = 0;
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_character_id'] = intval($_POST['funko_character_id']);
			$this->funko_character_id = intval($_POST['funko_character_id']);
		}
		elseif(isset($_POST['search_funko_character_id']) && !empty($_POST['search_funko_character_id'])) {
			$clean_array['funko_character_id'] = intval($_POST['search_funko_character_id']);
			$this->funko_character_id = intval($_POST['search_funko_character_id']);
		}
		elseif(isset($_POST['funko_character_id'])) {
			$clean_array['funko_character_id'] = intval($_POST['funko_character_id']);
			$this->funko_character_id = intval($_POST['funko_character_id']);
		}
		elseif(isset($_GET['funko_character_id'])) {
			$clean_array['funko_character_id'] = intval($_GET['funko_character_id']);
			$this->funko_character_id = intval($_GET['funko_character_id']);
		}
		else {
			$clean_array['funko_character_id'] = 0;
		}
		
		// $this->funko_number 5 digit identification number
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_number = 0;
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_number'] = intval($_POST['funko_number']);
			$this->funko_number = intval($_POST['funko_number']);
		}
		elseif(isset($_POST['funko_number'])) {
			$clean_array['funko_number'] = intval($_POST['funko_number']);
			$this->funko_number = intval($_POST['funko_number']);
		}
		elseif(isset($_GET['funko_number'])) {
			$clean_array['funko_number'] = intval($_GET['funko_number']);
			$this->funko_number = intval($_GET['funko_number']);
		}
		else {
			$clean_array['funko_number'] = 0;
		}
		
		/* funko_owned
		 * 0 = no, not owned
		 * 1 = yes, owned
		 */
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_owned = 0;
		}
		elseif(isset($_POST['btn_delete'])) {
			if(isset($_POST['funko_owned']) && $_POST['funko_owned'] === "true") {
				$clean_array['funko_owned'] = 1;
				$this->funko_owned = 1;
			}
			else {
				$clean_array['funko_owned'] = 0;
				$this->funko_owned = 0;
			}
		}
		elseif(isset($_POST['funko_owned'])) {
			if($_POST['funko_owned'] === "true") {
				$clean_array['funko_owned'] = 1;
				$this->funko_owned = 1;
			}
			else {
				$clean_array['funko_owned'] = 0;
				$this->funko_owned = 0;
			}
		}
		else {
			$clean_array['funko_owned'] = 0;
		}
		
		// funko_series - Aventers Infinity War
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_series = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_series'] = htmlentities($_POST['funko_series']);
			$this->funko_series = htmlentities($_POST['funko_series']);
		}
		elseif(isset($_POST['funko_series'])) {
			$clean_array['funko_series'] = htmlentities($_POST['funko_series']);
			$this->funko_series = htmlentities($_POST['funko_series']);
		}
		else {
			$clean_array['funko_series'] = '';
		}
		
		// funko_character - Hulkbuster
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_character = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_character'] = htmlentities($_POST['funko_character']);
			$this->funko_character = htmlentities($_POST['funko_character']);
		}
		elseif(isset($_POST['funko_character'])) {
			$clean_array['funko_character'] = htmlentities($_POST['funko_character']);
			$this->funko_character = htmlentities($_POST['funko_character']);
		}
		else {
			$clean_array['funko_character'] = '';
		}
		
		/* funko_status
		 * 0 = null, unknown
		 * 1 = vaulted
		 */
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_status = 0;
		}
		elseif(isset($_POST['btn_delete'])) {
			if(isset($_POST['funko_status']) && $_POST['funko_status'] === "vaulted") {
				$clean_array['funko_status'] = 1;
				$this->funko_status = 1;
			}
			else {
				$clean_array['funko_status'] = 0;
				$this->funko_status = 0;
			}
		}
		elseif(isset($_POST['funko_status'])) {
			if($_POST['funko_status'] === "vaulted") {
				$clean_array['funko_status'] = 1;
				$this->funko_status = 1;
			}
			else {
				$clean_array['funko_status'] = 0;
				$this->funko_status = 0;
			}
		}
		else {
			$clean_array['funko_status'] = 0;
		}
		
		// exclusive - funko exclusive to whom - HotTopic
		if(isset($_POST['btn_clear']) || $clear) {
			$this->exclusive = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['exclusive'] = htmlentities($_POST['exclusive']);
			$this->exclusive = htmlentities($_POST['exclusive']);
		}
		elseif(isset($_POST['exclusive'])) {
			$clean_array['exclusive'] = htmlentities($_POST['exclusive']);
			$this->exclusive = htmlentities($_POST['exclusive']);
		}
		else {
			$clean_array['exclusive'] = '';
		}
		
		// funko_notes - anything you wanna say
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_notes = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_notes'] = htmlentities($_POST['funko_notes']);
			$this->funko_notes = htmlentities($_POST['funko_notes']);
		}
		elseif(isset($_POST['funko_notes'])) {
			$clean_array['funko_notes'] = htmlentities($_POST['funko_notes']);
			$this->funko_notes = htmlentities($_POST['funko_notes']);
		}
		else {
			$clean_array['funko_notes'] = '';
		}
		
		// funko_available_date - 2018-11(October)
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_available_date = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_available_date'] = htmlentities($_POST['funko_available_date']);
			$this->funko_available_date = htmlentities($_POST['funko_available_date']);
		}
		elseif(isset($_POST['funko_available_date'])) {
			$clean_array['funko_available_date'] = htmlentities($_POST['funko_available_date']);
			$this->funko_available_date = htmlentities($_POST['funko_available_date']);
		}
		else {
			$clean_array['funko_available_date'] = '';
		}
		
		// funko_tags - Disney, Ironman
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_tags = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_tags'] = htmlentities($_POST['funko_tags']);
			$this->funko_tags = htmlentities($_POST['funko_tags']);
		}
		elseif(isset($_POST['funko_tags'])) {
			$clean_array['funko_tags'] = htmlentities($_POST['funko_tags']);
			$this->funko_tags = htmlentities($_POST['funko_tags']);
		}
		else {
			$clean_array['funko_tags'] = '';
		}
		
		// funko_quantity - numeric
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_quantity = 0;
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_quantity'] = intval($_POST['funko_quantity']);
			$this->funko_quantity = intval($_POST['funko_quantity']);
		}
		elseif(isset($_POST['funko_quantity'])) {
			$clean_array['funko_quantity'] = intval($_POST['funko_quantity']);
			$this->funko_quantity = intval($_POST['funko_quantity']);
		}
		else {
			$clean_array['funko_quantity'] = 0;
		}
		
		// funko_value - float 25.33
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_value = 0.00;
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_value'] = floatval($_POST['funko_value']);
			$this->funko_value = floatval($_POST['funko_value']);
		}
		elseif(isset($_POST['funko_value'])) {
			$clean_array['funko_value'] = floatval($_POST['funko_value']);
			$this->funko_value = floatval($_POST['funko_value']);
		}
		else {
			$clean_array['funko_value'] = 0.00;
		}
		

		
		// value_date - orderd date
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_value_date = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			if(empty($_POST['funko_value_date'])) {
				$clean_array['funko_value_date'] = '1970-01-01';
				$this->funko_value_date = '1970-01-01';
			}
			else {
				$clean_array['funko_value_date'] = $_POST['funko_value_date'];
				$this->funko_value_date = $_POST['funko_value_date'];
			}
		}
		elseif(isset($_POST['funko_value_date'])) {
			if(empty($_POST['funko_value_date'])) {
				$clean_array['funko_value_date'] = '1970-01-01';
				$this->funko_value_date = '1970-01-01';
			}
			else {
				$clean_array['funko_value_date'] = $_POST['funko_value_date'];
				$this->funko_value_date = $_POST['funko_value_date'];
			}
		}
		else {
			$clean_array['value_date'] = '1970-01-01';
		}
		
		// value_source - hobby db
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_value_source = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_value_source'] = htmlentities($_POST['funko_value_source']);
			$this->funko_value_source = htmlentities($_POST['funko_value_source']);
		}
		elseif(isset($_POST['funko_value_source'])) {
			$clean_array['funko_value_source'] = htmlentities($_POST['funko_value_source']);
			$this->funko_value_source = htmlentities($_POST['funko_value_source']);
		}
		else {
			$clean_array['value_source'] = '';
		}
		
		// funko_price - float 25.33
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_price = 0.00;
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_price'] = floatval($_POST['funko_price']);
			$this->funko_price = floatval($_POST['funko_price']);
		}
		elseif(isset($_POST['funko_price'])) {
			$clean_array['funko_price'] = floatval($_POST['funko_price']);
			$this->funko_price = floatval($_POST['funko_price']);
		}
		else {
			$clean_array['funko_price'] = 0.00;
		}
		
		// funko_ordered - orderd date
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_purchased_date = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			if(empty($_POST['funko_purchased_date'])) {
				$clean_array['funko_purchased_date'] = '1970-01-01';
				$this->funko_purchased_date = '1970-01-01';
			}
			else {
				$clean_array['funko_purchased_date'] = $_POST['funko_purchased_date'];
				$this->funko_purchased_date = $_POST['funko_purchased_date'];
			}
		}
		elseif(isset($_POST['funko_purchased_date'])) {
			if(empty($_POST['funko_purchased_date'])) {
				$clean_array['funko_purchased_date'] = '1970-01-01';
				$this->funko_purchased_date = '1970-01-01';
			}
			else {
				$clean_array['funko_purchased_date'] = $_POST['funko_purchased_date'];
				$this->funko_purchased_date = $_POST['funko_purchased_date'];
			}
		}
		else {
			$clean_array['funko_purchased_date'] = '1970-01-01';
		}
		
		// funko_seller - HotTopic
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_seller = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_seller'] = htmlentities($_POST['funko_seller']);
			$this->funko_seller = htmlentities($_POST['funko_seller']);
		}
		elseif(isset($_POST['funko_seller'])) {
			$clean_array['funko_seller'] = htmlentities($_POST['funko_seller']);
			$this->funko_seller = htmlentities($_POST['funko_seller']);
		}
		else {
			$clean_array['funko_seller'] = '';
		}
		
		// funko_barcode - 1234567896
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_barcode = 0;
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_barcode'] = $_POST['funko_barcode'];
			$this->funko_barcode = $_POST['funko_barcode'];
		}
		elseif(isset($_POST['funko_barcode'])) {
			$clean_array['funko_barcode'] = $_POST['funko_barcode'];
			$this->funko_barcode = $_POST['funko_barcode'];
		}
		else {
			$clean_array['funko_barcode'] = 0;
		}
		
		// funko_image
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_image = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_image'] = htmlentities($_POST['funko_image']);
			$this->funko_image = htmlentities($_POST['funko_image']);
		}
		elseif(isset($_POST['funko_image'])) {
			$clean_array['funko_image'] = htmlentities($_POST['funko_image']);
			$this->funko_image = htmlentities($_POST['funko_image']);
		}
		else {
			$clean_array['funko_image'] = '';
		}
		
		// funko_ordered - orderd date
		if(isset($_POST['btn_clear']) || $clear) {
			$this->funko_ordered_date = '';
		}
		elseif(isset($_POST['btn_delete'])) {
			$clean_array['funko_ordered_date'] = $_POST['funko_ordered_date'];
			$this->funko_ordered_date = $_POST['funko_ordered_date'];
		}
		elseif(isset($_POST['funko_ordered_date'])) {
			if(empty($_POST['funko_ordered_date'])) {
				$clean_array['funko_ordered_date'] = '1970-01-01';
				$this->funko_ordered_date = '1970-01-01';
			}
			else {
				$clean_array['funko_ordered_date'] = $_POST['funko_ordered_date'];
				$this->funko_ordered_date = $_POST['funko_ordered_date'];
			}
		}
		else {
			$clean_array['funko_ordered_date'] = '1970-01-01';
		}
		
		// insert_date
		if(isset($_POST['btn_clear']) || $clear) {
			$this->insert_date = '';
		}
		
		// insert_by
		if(isset($_POST['btn_clear']) || $clear) {
			$this->insert_by = '';
		}
		
		// update_date
		if(isset($_POST['btn_clear']) || $clear) {
			$this->update_date = '';
		}
		
		// update_by
		if(isset($_POST['btn_clear']) || $clear) {
			$this->update_by = '';
		}
		
		$this->clean_array = $clean_array;
		
	}
	
	/**
	 * Clean/Set values coming from database
	 * @param array $incoming_array
	 */
	private function set_values($incoming_array) {
		
		if(isset($incoming_array["funko_character_id"])) {
			$this->funko_character_id = $incoming_array["funko_character_id"];
		}
		
		if(isset($incoming_array["funko_number"])) {
			$this->funko_number = $incoming_array["funko_number"];
		}
		
		if(isset($incoming_array["owned"])) {
			$this->funko_owned = $incoming_array["owned"];
		}
		
		if(isset($incoming_array["funko_series"])) {
			$this->funko_series = $incoming_array["funko_series"];
		}
		
		if(isset($incoming_array["funko_character"])) {
			$this->funko_character= $incoming_array["funko_character"];
		}
		
		if(isset($incoming_array["funko_status"])) {
			$this->funko_status= $incoming_array["funko_status"];
		}
		
		if(isset($incoming_array["exclusive"])) {
			$this->exclusive = $incoming_array["exclusive"];
		}
		
		if(isset($incoming_array["notes"])) {
			$this->funko_notes = $incoming_array["notes"];
		}
		
		if(isset($incoming_array["available_date"])) {
			$this->funko_available_date = $incoming_array["available_date"];
		}
		
		if(isset($incoming_array["tags"])) {
			$this->funko_tags = $incoming_array["tags"];
		}
		
		if(isset($incoming_array["quantity_owned"])) {
			$this->funko_quantity = $incoming_array["quantity_owned"];
		}


		
		if(isset($incoming_array["value"])) {
			$this->funko_value = $incoming_array["value"];
		}

		if(isset($incoming_array["value_date"])) {
			if($incoming_array["value_date"] === '1970-01-01') {
				$this->funko_value_date = '';
			}
			else {
				$this->funko_value_date = $incoming_array["value_date"];
			}
		}
		
		if(isset($incoming_array["value_source"])) {
			$this->funko_value_source = $incoming_array["value_source"];
		}



		if(isset($incoming_array["purchased_price"])) {
			$this->funko_price = $incoming_array["purchased_price"];
		}
		
		if(isset($incoming_array["purchased_date"])) {
			if($incoming_array["purchased_date"] === '1970-01-01') {
				$this->funko_purchased_date = '';
			}
			else {
				$this->funko_purchased_date = $incoming_array["purchased_date"];
			}
		}
		
		if(isset($incoming_array["purchased_from"])) {
			$this->funko_seller = $incoming_array["purchased_from"];
		}
		
		if(isset($incoming_array["barcode"])) {
			$this->funko_barcode = $incoming_array["barcode"];
		}
		
		if(isset($incoming_array["image"])) {
			$this->funko_image = $incoming_array["image"];
		}
		
		if(isset($incoming_array["ordered_date"])) {
			if($incoming_array["ordered_date"] === '1970-01-01') {
				$this->funko_ordered_date = '';
			}
			else {
				$this->funko_ordered_date = $incoming_array["ordered_date"];
			}
		}
		
		if(isset($incoming_array["insert_date"])) {
			$this->insert_date = $incoming_array["insert_date"];
		}
		
		if(isset($incoming_array["insert_by"])) {
			$this->insert_by = $incoming_array["insert_by"];
		}
		
		if(isset($incoming_array["update_date"])) {
			$this->update_date = $incoming_array["update_date"];
		}
		
		if(isset($incoming_array["update_by"])) {
			$this->update_by = $incoming_array["update_by"];
		}
		
	}
	
	
	
	
	/**
	 * get_funko_by_number
	 *
	 * @return boolean|string
	 */
	private function get_funko_by_number() {
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("get_funko_by_number() Number: ", $this->funko_number);
		}

		$results = '';
		
		//instantiate db class
		$connect = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		
		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: '%s'\n", $connect->connect_error());
			exit();
		}
		
		//sql
		$sql = "SELECT * FROM funko_character WHERE funko_number = %d";
		$sql_sprintf = sprintf($sql, intval($this->funko_number));
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("get_funko_by_number() query: ", $sql_sprintf);
		}
		
		//run the query
		$rs = $connect->query($sql_sprintf);
		
		//if there was an error
		if($connect->error) {
			$results = $connect->error;
			$debug_results = $results;
		}
		//Successful search
		elseif($connect->affected_rows === 1) {
			$results = $rs->fetch_assoc();
		}
		//no result found
		elseif($rs->num_rows === 0) {
			$results = FALSE;
		}
		//too many result found
		elseif($rs->num_rows > 1) {
			$results = "Too Many result Found.";
		}
		else {
			$results = "CATCH ALL get funko";
		}
		
		//close connection
		$connect->close();
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("get_funko_by_number() Result: ", $results);
		}
		
		//return result
		return $results;
		
	} //END get_funko_by_number();
	
	
	/**
	 * get_funko_by_id
	 *
	 * @return boolean|string
	 */
	private function get_funko_by_id() {
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("get_funko_by_id() ID: ", $this->funko_character_id);
		}
		
		$results = '';
		
		//instantiate db class
		$connect = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		
		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: '%s'\n", $connect->connect_error());
			exit();
		}
		
		//sql
		$sql = "SELECT * FROM funko_character WHERE funko_character_id = %d";
		$sql_sprintf = sprintf($sql, intval($this->funko_character_id));
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("get_funko_by_id() query: ", $sql_sprintf);
		}
		
		//run the query
		$rs = $connect->query($sql_sprintf);
		
		//if there was an error
		if($connect->error) {
			$results = $connect->error;
		}
		//Successful search
		elseif($connect->affected_rows === 1) {
			$results = $rs->fetch_assoc();
		}
		//no result found
		elseif($rs->num_rows === 0) {
			$results = "No records found.";
		}
		//too many result found
		elseif($rs->num_rows > 1) {
			$results = "Too Many result Found.";
		}
		else {
			$results = "CATCH ALL get funko";
		}
		
		//close connection
		$connect->close();
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("get_funko_by_id() Result: ", $results);
		}
		
		//need to clear out existing values in $_POST
		$this->set_post(TRUE);
		
		//return result
		return $results;
		
	} //END get_funko_by_id();
	
	/**
	 * 
	 * @return boolean|string
	 */
	private function update_funko() {
		
		$results = '';
		$incoming_array = $this->clean_array;
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("update_funko() incoming array: ", $incoming_array);
		}
		
		//instantiate db class
		$connect = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		
		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: '%s'\n", $connect->connect_error());
			exit();
		}
		
		//put some variables together
		$funko_character_id = $connect->real_escape_string($incoming_array['funko_character_id']);
		$funko_number = $connect->real_escape_string($incoming_array['funko_number']);
		$owned = $incoming_array['funko_owned'];
		$funko_series = $connect->real_escape_string($incoming_array['funko_series']);
		$funko_character = $connect->real_escape_string($incoming_array['funko_character']);
		$funko_status = $incoming_array['funko_status'];
		$exclusive = $incoming_array['exclusive'];
		$notes = $connect->real_escape_string($incoming_array['funko_notes']);
		$available_date = $connect->real_escape_string($incoming_array['funko_available_date']);
		$tags = $connect->real_escape_string($incoming_array['funko_tags']);
		$quantity_owned = $connect->real_escape_string($incoming_array['funko_quantity']);

		$value = $connect->real_escape_string($incoming_array['funko_value']);
		$value_date = $connect->real_escape_string($incoming_array['funko_value_date']);
		$value_source = $connect->real_escape_string($incoming_array['funko_value_source']);

		$purchased_price = $connect->real_escape_string($incoming_array['funko_price']);
		$purchased_date = $connect->real_escape_string($incoming_array['funko_purchased_date']);
		$purchased_from = $connect->real_escape_string($incoming_array['funko_seller']);

		$barcode = $connect->real_escape_string($incoming_array['funko_barcode']);
		$image = $connect->real_escape_string($incoming_array['funko_image']);
		$ordered_date = $connect->real_escape_string($incoming_array['funko_ordered_date']);
		
		$sql = "UPDATE
						funko_character
					SET
						funko_number = %d
					,	owned = %d
					,	funko_series = '%s'
					,	funko_character = '%s'
					,	funko_status = %d
					,	exclusive = '%s'
					,	notes = '%s'
					,	available_date = '%s'
					,	tags = '%s'
					,	quantity_owned = %d
					,	value = '%s'
					,	value_date = '%s'
					,	value_source = '%s'
					,	purchased_price = '%s'
					,	purchased_date = '%s'
					,	purchased_from = '%s'
					,	barcode = '%s'
					,	image = '%s'
					,	ordered_date = '%s'
					,	update_date = now()
					,	update_by = %d
					WHERE
						funko_character_id = %d";
		
		$sql_sprintf = sprintf($sql
				,	$funko_number
                ,	$owned
                ,	$funko_series
                ,	$funko_character
                ,	$funko_status
				,	$exclusive, $notes
                ,	$available_date
                ,	$tags
                ,	$quantity_owned
                ,	$value
                ,   $value_date
				,	$value_source
                ,   $purchased_price
				,	$purchased_date
                ,	$purchased_from
                ,	$barcode
                ,	$image
                ,	$ordered_date
				,	$this->db_user_id
                ,	$funko_character_id);
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("update_funko() query: ", $sql_sprintf);
		}
		
		//run the query
		$rs = $connect->query($sql_sprintf);
		
		//if there was an error
		if($connect->error) {
			$results = $connect->error;
		}
		//Successful search
		elseif($connect->affected_rows === 1) {
			$results = TRUE;
		}
		//no result found
		elseif($connect->affected_rows === 0) {
			$results = "Unknown Failure for Update Record.";
		}
		else {
			$results = "CATCH ALL update funko.";
		}
		
		//close connection
		$connect->close();
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("update_funko() Result: ", $results);
		}
		
		//return result
		return $results;
		
	} //END update_funko
	
	
	/**
	 * 
	 * @return boolean|string
	 */
	private function insert_funko() {
		
		$results = '';
		$incoming_array = $this->clean_array;
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("insert_funko() incoming array: ", $incoming_array);
		}
		
		
		//instantiate db class
		$connect = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		
		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: '%s'\n", $connect->connect_error());
			exit();
		}
		
		//put some variables together
		$funko_number = $connect->real_escape_string($incoming_array['funko_number']);
		$owned = $connect->real_escape_string($incoming_array['funko_owned']);
		$funko_series = $connect->real_escape_string($incoming_array['funko_series']);
		$funko_character = $connect->real_escape_string($incoming_array['funko_character']);
		$funko_status = $connect->real_escape_string($incoming_array['funko_status']);
		$exclusive =  $connect->real_escape_string($incoming_array['exclusive']);
		$notes = $connect->real_escape_string($incoming_array['funko_notes']);
		$available_date = $connect->real_escape_string($incoming_array['funko_available_date']);
		$tags = $connect->real_escape_string($incoming_array['funko_tags']);
		$quantity_owned = $connect->real_escape_string($incoming_array['funko_quantity']);

		$value = $connect->real_escape_string($incoming_array['funko_value']);
		$value_date = $connect->real_escape_string($incoming_array['funko_value_date']);
		$value_source = $connect->real_escape_string($incoming_array['funko_value_source']);

		$purchased_price = $connect->real_escape_string($incoming_array['funko_price']);
		$purchased_date = $connect->real_escape_string($incoming_array['funko_purchased_date']);
		$purchased_from = $connect->real_escape_string($incoming_array['funko_seller']);

		$barcode = $connect->real_escape_string($incoming_array['funko_barcode']);
		$image = $connect->real_escape_string($incoming_array['funko_image']);
		$ordered_date = $connect->real_escape_string($incoming_array['funko_ordered_date']);
		
		$sql = "INSERT INTO 
						funko_character
					(
						funko_number
                    ,	owned
                    ,	funko_series
					,	funko_character
                    ,	funko_status
					,	exclusive
                    ,	notes
                    ,	available_date
                    ,	tags
					,	quantity_owned
                    ,	value
                    ,   value_date
                    ,   value_source
                    ,   purchased_price
					,	purchased_date
                    ,	purchased_from
					,	barcode
                    ,	image
                    ,	ordered_date
					,	insert_date
                    ,	insert_by
					)
					VALUES
					(
						%d
                    ,	%d
                    ,	'%s'
					,	'%s'
                    ,	%d
					,	'%s'
                    ,	'%s'
                    ,	'%s'
                    ,	'%s'
					,	%d
                    ,   '%s'
                    ,   '%s'
					,	'%s'
                    ,   '%s'
					,	'%s'
                    ,	'%s'
					,	%d
                    ,	'%s'
                    ,	'%s'
					,	now()
                    ,	%d
					)";
		
		$sql_sprintf = sprintf($sql
				,	$funko_number
                ,	$owned
                ,	$funko_series
				,	$funko_character
                ,	$funko_status
				,	$exclusive
                ,	$notes
                ,	$available_date
                ,	$tags
				,	$quantity_owned
                ,   $value
                ,   $value_date
                ,   $value_source
                ,	$purchased_price
				,	$purchased_date
                ,	$purchased_from
				,	$barcode
                ,	$image
                ,	$ordered_date
				,	$this->db_user_id);
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("insert_funko() query: ", $sql_sprintf);
		}
		
		//run the query
		$rs = $connect->query($sql_sprintf);
		
		//if there was an error
		if($connect->error) {
			$results = $connect->error;
		}
		//Successful search
		elseif($connect->affected_rows === 1) {
			$results  = $connect->insert_id;
		}
		//no result found
		elseif($connect->affected_rows === 0) {
			$results = "Unknown Failure for Insert Record.";
		}
		else {
			$results = "CATCH ALL insert funko.";
		}
		
		//close connection
		$connect->close();
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("insert_funko() Result: ", $results);
		}
		
		//return result
		return $results;
		
	} //END insert_funko
	
	
	/**
	 *
	 * @return boolean|string
	 */
	private function delete_funko() {
		
		$results = '';
		$incoming_array = $this->clean_array;
		
		//instantiate db class
		$connect = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		
		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: '%s'\n", $connect->connect_error());
			exit();
		}
		
		//put some variables together
		$funko_character_id = $connect->real_escape_string($incoming_array['funko_character_id']);
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("delete_funko() funko_character_id: ", $funko_character_id);
		}
				
		$sql = "DELETE FROM funko_character WHERE funko_character_id = %d LIMIT 1";
		
		$sql_sprintf = sprintf($sql, $funko_character_id);
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("delete_funko() query: ", $sql_sprintf);
		}
		
		//run the query
		$rs = $connect->query($sql_sprintf);
		
		//if there was an error
		if($connect->error) {
			$results = $connect->error;
		}
		//Successful search
		elseif($connect->affected_rows === 1) {
			$results = TRUE;
		}
		//no result found
		elseif($connect->affected_rows === 0) {
			$results = "Unknown Failure for Update Record.";
		}
		else {
			$results = "CATCH ALL delete funko.";
		}
		
		//close connection
		$connect->close();
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("delete_funko() Result: ", $results);
		}
		
		//return result
		return $results;
		
	} //END delete_funko

    /************************************************************************************************************/
    /************************************************************************************************************/

    /**
	 *
	 * @return boolean|array
	 */
    function clean_image_name() {
  
        //new empty array
        $results = [];

        $string_find = array();
        $string_find[0] = "'";
        $string_find[2] = " ";

        $string_replace = array();
        $string_replace[0] = "";
        $string_replace[2] = "_";

        //get extension from temp file
        $extension = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));

        //get funko_series and replace spaces with underlines
        $funko_series = str_replace("&#039;", "", $this->funko_series);
        $funko_series = str_replace("&amp;", "and", $funko_series);
        $funko_series = str_replace($string_find, $string_replace, $funko_series);
        $funko_series = strtolower($funko_series);

        //get funko_characer_name and replace spaces with underlines
        $funko_character = str_replace("&#039;", "", $this->funko_character);
        $funko_character = str_replace("&amp;", "and", $funko_character);
        $funko_character = str_replace($string_find, $string_replace, $funko_character);
        $funko_character = strtolower($funko_character);

        //append all together with dashes
            //Examples: 9515-ant-man.jpg
        $results['image_name'] = strval($this->funko_number) . '-' . $funko_character . '.' . $extension;

        //create image path: images/funko_series/
            //Examples: captain_america_civil_war
        $results['image_directory'] = $funko_series;

       //debug values
	    if($this->debug === TRUE) {
			$this->debug_class->write_debug2("clean_image_name() result: ", $results);
		}

        //check if values are not empty
        if(empty($results)) {
            //return false
            return False;
        }
        else {
            //return array
            return $results;
        }

    } //END clean_image_name


	/**
	 *
	 * @return boolean
	 */
    function create_directory($clean_image) {
        
        $results = FALSE;

       //debug values
	    if($this->debug === TRUE) {
			$this->debug_class->write_debug2("create_directory() incoming value: ", $clean_image);
		}

        //verify we received an array
        if(!is_array($clean_image)) { return FALSE; }

        //get directory name from image_directory - Examples: captain_america_civil_war
        $directory = $this->target_dir . $clean_image['image_directory']; // ../funko/images/captain_america_civil_war

        //check if directory exists
        if(!is_dir($directory)) {
            if(!mkdir($directory)) {
                $debug_results = 'Falure to create directory: ' . $directory;
                $results = FALSE;
            }
            else {
                $debug_results = 'Sucessfully created directory: ' . $directory;
                $results = TRUE;
            }
        }
        else {
            $debug_results = 'Directory Exists: ' . $directory;
            $results = TRUE;
        }

       //debug values
	    if($this->debug === TRUE) {
			$this->debug_class->write_debug2("create_directory() result: ", $results);
		}

        //return result
        return $results;

    } //END create_directory


	/**
	 *
	 * @return boolean
	 */
    function move_image($clean_image) {
    
        $results = FALSE; //failure

        //debug values
	    if($this->debug === TRUE) {
			$this->debug_class->write_debug2("move_image() incoming values: ", $clean_image);
		}

        //verify we received an array
        if(!is_array($clean_image)) { return FALSE; }

        //get temp file
        $source_image = $_FILES["fileToUpload"]["tmp_name"];

        //create destination adding the extension
            // ../funko/images/captain_america_civil_war/23151-ant-man.jpg
        $destination = $this->target_dir . $clean_image['image_directory'] . '/' . $clean_image['image_name'];

        if(!move_uploaded_file($source_image, $destination)) {
            //failure
            $results = FALSE;
        }
        else {
            //success
            $results = TRUE;
        }

        //debug values
	    if($this->debug === TRUE) {
            $debug_array = [];
            $debug_array['source_image'] = $source_image;
            $debug_array['destination'] = $destination;
			$this->debug_class->write_debug2("move_image() outgoing values: ", $debug_array);
		}

        //return result
        return $results;

    }

    /**
	 * 
	 * @return boolean|string
	 */
	private function update_funko_image($clean_image) {
		
		$results = FALSE;
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("update_funko_image() incoming array: ", $clean_image);
		}
		
        
        //verify we received an array
        if(!is_array($clean_image)) { return FALSE; }

		//instantiate db class
		$connect = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		
		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: '%s'\n", $connect->connect_error());
			exit();
		}
		
		//put some variables together
		$funko_character_id = $connect->real_escape_string($this->funko_character_id);
        $image = $this->database_dir . $clean_image['image_directory'] . '/' . $clean_image['image_name']; // images/captain_america_civil_war/23151-ant-man.jpg
        $image = $connect->real_escape_string($image);

		$sql = "UPDATE
						funko_character
					SET
						image = '%s'
					,	update_date = now()
					,	update_by = %d
					WHERE
						funko_character_id = %d";
		
		$sql_sprintf = sprintf($sql
				,	$image
				,	$this->db_user_id,	$funko_character_id);
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("update_funko_image() query: ", $sql_sprintf);
		}
		
		//run the query
		$rs = $connect->query($sql_sprintf);
		
		//if there was an error
		if($connect->error) {
            $debug_results = $connect->error;
			$results = $connect->error;
		}
		//Successful search
		elseif($connect->affected_rows === 1) {            
			$debug_results = "Successfully Update Record with Image.";
			$results = TRUE;
		}
		//no result found
		elseif($connect->affected_rows === 0) {
			$debug_results = "Unknown Failure for Update Record with Image.";
            $results = FALSE;
		}
		else {
			$debug_results = "CATCH ALL update Funko Image.";
            $results = FALSE;
		}
		
		//close connection
		$connect->close();
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("update_funko_image() Result: ", $debug_results);
		}

   		//return result
       	return $results;

	} //END update_funko_image


	/**
	 *
	 */
    function upload_image_do() {
 
        //debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("upload_image_do() was called for : ", $this->funko_character_id);
		}

            // Check file size
                // not going to do this ether, I know what the size it

            // check file formats
                // nor this. I will do all these if

        // clean image by creating
        $clean_image = $this->clean_image_name();
        if(is_array($clean_image)) {
            //create image directory path or verify it exists
            if($this->create_directory($clean_image) === TRUE) {
                //move image to image directory, send the array with image_name and image_path
                if($this->move_image($clean_image) === TRUE) {

                // Check if file already exists, NAH it will overwrite existing image

                    //update database with path/image
                    if($this->update_funko_image($clean_image) === TRUE) {
                        //success message 
					    $this->results = "Successfully Uploaded Image For: $this->funko_character_id";
					    $this->results_color = 'bg-success text-white';
                        $this->funko_image = $this->database_dir . $clean_image['image_directory'] . '/' . $clean_image['image_name'];
                    }
                    else {
                        //failure to move image to directory
                        $this->results = "Failed To Update Datebase For: $this->funko_character_id";
		                $this->results_color = 'bg-danger text-white';
                    }
                }
                else {
                    //failure to move image to directory
                    $this->results = "Failed To Move Image For: $this->funko_character_id";
		            $this->results_color = 'bg-danger text-white';
                }
            }
            else{
                //Create directory failure
                $this->results = "Failed To Create Directory For: $this->funko_character_id";
		        $this->results_color = 'bg-danger text-white';
            }
        }
        else {
            //array of image name and path failed
            $this->results = "Failed To Create Image Name For: $this->funko_character_id";
            $this->results_color = 'bg-danger text-white';
        }

    } // END upload_image_do
	

} //END Class

?>
