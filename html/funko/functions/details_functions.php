<?php
require_once('../../funko/db_page_conn.php');


/*
 * 2019-05-30 
 * 	Updated by: Jay
 * 	Changes: Added exclusive field from database, using exclusive instead of notes in display
 * 
 * */

class details_functions extends db_conn {
	
	private $funko_character_id = 0;
	private $funko_number = 0;
	private $funko_owned = 'No';
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
	private $results = 'Results';
	private $results_color = 'bg-light text-dark';
	
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

    function do_get_details() { return $this->get_funko_by_number(); }
    function do_set_values($incoming_array) { return $this->set_values($incoming_array); }

	/**
	 * Sets POST or GET
	 */
	function set_post() {
			
		if(isset($_POST['funko_number'])) {
			$this->funko_number = $_POST['funko_number'];
		}
		elseif(isset($_GET['funko_number'])) {
			$this->funko_number = $_GET['funko_number'];
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
			$result = $connect->error;
			$debug_result = $result;
		}
		//Successful search
		elseif($connect->affected_rows === 1) {
			$result = $rs->fetch_assoc();
		}
		//no results found
		elseif($rs->num_rows === 0) {
			$result = FALSE;
		}
		//too many results found
		elseif($rs->num_rows > 1) {
			$result = "Too Many Results Found.";
		}
		else {
			$result = "CATCH ALL get funko";
		}
		
		//close connection
		$connect->close();
		
		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("get_funko_by_number() Result: ", $result);
		}
		
		//return result
		return $result;
		
	} //END get_funko_by_number();
	


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
		    if($incoming_array['owned'] === '1') {
			    $this->funko_owned = '<span class="text-success">YES</span>';
		    }
		    else {
			    $this->funko_owned = '<span class="text-danger">NO</span>';
		    }
		}

		if(isset($incoming_array["funko_series"])) {
			$this->funko_series = $this->make_search_url($incoming_array["funko_series"]);
		}
		
		if(isset($incoming_array["funko_character"])) {
			$this->funko_character= $incoming_array["funko_character"];
		}
		
		if(isset($incoming_array["funko_status"])) {
		    if($incoming_array['funko_status'] === '1') {
			    $this->funko_status = 'Yes';
		    }
		    else {
			    $this->funko_status = 'Unknown';
		    }
		}
		
		if(isset($incoming_array["exclusive"])) {
             $this->exclusive = $this->make_url($incoming_array["exclusive"]);
		}
		
		if(isset($incoming_array["notes"])) {
			$this->funko_notes = $incoming_array["notes"];
		}
		
		if(isset($incoming_array["available_date"])) {
			$this->funko_available_date = $incoming_array["available_date"];
		}
		
		if(isset($incoming_array["tags"])) {
			$this->funko_tags = $this->make_tag_search($incoming_array["tags"]);
            
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
            $this->funko_value_source = $this->make_url($incoming_array["value_source"]);
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
            $this->funko_seller = $this->make_url($incoming_array["purchased_from"]);
		}
		
		if(isset($incoming_array["barcode"])) {
			$this->funko_barcode = $incoming_array["barcode"];
		}
		
		if(isset($incoming_array["image"])) {
            $image = '';
			$image = $incoming_array["image"];
            $this->funko_image = <<<img

									<a href="$image" data-toggle="lightbox">
										<img src="$image" class="img-fluid mx-auto d-block">
									</a>

img;
		}
		

		if(isset($incoming_array["ordered_date"])) {
            $date = '';
			if($incoming_array["ordered_date"] === '1970-01-01') {
				$this->funko_ordered_date = '';
			}
			else {
                $date = $incoming_array["ordered_date"];
				$this->funko_ordered_date = '<span class="text-success">' . $date . '</span>';
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
	 * 
	 * @return string
	 */
    function make_url($incoming) {

        $domain = "";
        $source_url = "";

		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("make_url() Incoming: ", $incoming);
		}

        //if multiple urls
        $source_array = explode(",", $incoming);
        foreach($source_array as $value) {
            $value = trim($value);
            //put comma into string
            if(!empty($source_url)) {
                $source_url .= ", ";
            }

            //check if url, create url string
            if(filter_var($value, FILTER_VALIDATE_URL)) {   
                //lets get Domain.com
                $domain = parse_url($value);
                $source_url .= '<a href="' . $value . '">' . $domain['host'] . '</a>';
            }
            else {
                $source_url .= $value;
            }
        }
        //return source_url
        return $source_url;

    }

    /**
	 * 
	 * @return string
	 */
    function make_search_url($incoming) {

        $search_url = 'https://www.jaycroft.com/funko/index.php?search=';
        $series_search = "";

		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("make_search_url() Incoming: ", $incoming);
		}
        
        $series_search = str_replace(" ", "%20", $incoming);

        $search_url .= $series_search;

		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("make_search_url() Result: ", $search_url);
		}

        //put into href
        $search_string = '<a href="' . $search_url . '">' . $incoming . '</a>';

		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("make_search_url() Result: ", $search_string);
		}

        //return string
        return $search_string;

    }

    /**
	 * 
	 * @return string
	 */
    function make_tag_search($incoming) {
        
        $search_url = 'https://www.jaycroft.com/funko/index.php?search=';
        $series_search = "";
        $tag_urls = "";
        $full_url = "";

		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("make_tag_search() Incoming: ", $incoming);
		}

        //if multiple tags
        $source_array = explode(",", $incoming);

        foreach($source_array as $value) {
            $value = trim($value);
            $series_search = str_replace(" ", "%20", $value);
            $full_url = $search_url . $series_search;
            //put into href
            $search_string = '<a href="' . $full_url . '">' . $value . '</a>';
            //put comma into string
            if(!empty($tag_urls)) {
                $tag_urls .= ", ";
            }
            $tag_urls .= $search_string;
        }

		//debug values
		if($this->debug === TRUE) {
			$this->debug_class->write_debug2("make_tag_search() Result: ", $tag_urls);
		}

        //return string
        return $tag_urls;
    }

}
