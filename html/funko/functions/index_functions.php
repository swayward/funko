<?php
require_once('../../funko/db_page_conn.php');

/*
 * 2019-05-30 
 * 	Updated by: Jay
 * 	Changes: Added exclusive field from database, using exclusive instead of notes in display
 *
 * 2025-04-02
 *  Updated by: Jay
 *  Changes: Changed layout slightly. Moved states to be first card.
 * */

class index_functions extends db_conn {
	
	private $ownedCount = 0;
	private $wantedCount = 0;
	private $availableCount = 0;
	private $spent = 0;
	private $average = 0;
	private $percentage = 0;
	private $value = 0;
	
	private $currentDate = '';
	
	private $sort = 'default';
	private $search = '';
	private $search_value = '';
	private $search_results = '';
	
	function get_ownedCount() { return $this->ownedCount; }
	function get_wantedCount() { return $this->wantedCount; }
	function get_availableCount() { return $this->availableCount; }
	function get_spent() { return $this->spent; }
	function get_average() { return $this->average; }
	function get_percentage() { return $this->percentage; }
	function get_value() { return $this->value; }
	
	function get_search() { return $this->search; }
	function get_search_value() { return $this->search_value; }
	function get_search_results() { return $this->search_results; }
	/*
	$ownedCount = $index_functions->get_ownedCount();
	$wantedCount = $index_functions->get_wantedCount();
	$availableCount = $index_functions->get_availableCount();
	$spent = $index_functions->get_spent();
	$average = $index_functions->get_average();
	$percentage = $index_functions->get_percentage();
	$search = $index_functions->get_search();
	$search_value = $index_functions->get_search_value();
	$search_results = $index_functions->get_search_results();
	 */
	
	function __construct() {
		$this->currentDate = date('Ym', strtotime('-6 month'));
	}
	
	/**
	 * Sets POST or GET
	 */
	function set_post() {
			
		if(isset($_POST['search'])) {
			$this->search = $_POST['search'];
			$this->search_value = $_POST['search'];
		}	
		elseif(isset($_GET['search'])) {
			$this->search = $_GET['search'];
			$this->search_value = $_GET['search'];
		}
		
		if(isset($_POST['sort'])) {
			$this->sort = $_POST['sort'];
		}
		elseif(isset($_GET['sort'])) {
			$this->sort = $_GET['sort'];
		}

	}
	
	/**
	 * get everything from mysql table
	 *
	 * @return string
	 */
	function get_content_mysql() {
		
		$results = '';
		
		$db_conn = new db_conn();
		
		//instantiate db class
		$connect = new mysqli($db_conn->db_host, $db_conn->db_user, $db_conn->db_pass, $db_conn->db_name);
		
		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", $connect->connect_error);
			exit();
		}
		
		$sql = "SELECT * FROM funko_character ORDER BY funko_number ASC";
		
		//run the query
		$rs = $connect->query($sql);
		
		//if there was an error
		if($connect->error) {
			$results =  "ERROR: " . $connect->error;
		}
		//Successful select
		elseif($rs->num_rows > 0) {
			$results = [];
			while($row = $rs->fetch_array()) {
				$results[] = $row;
			}
		}
		else {
			$results =  "CATCH ALL: get_content_mysql.";
		}
		
		//close connection
		$connect->close();
		
		//return array
		return $results;
	}
	
	/**
	 *
	 *
	 * @param unknown $incomingArrayToSort
	 * @return string
	 */
	function masterSort($incomingArrayToSort) {
		
		$array_results = '';
		
		//Sort Contents by [3] => Character
		if(($this->sort === 'character_wanted') || ($this->sort === 'character_owned')) {
			//Sort items by Series
			$array_results = $this->sortResultsByCharacter($incomingArrayToSort);
		}
		//sort by available date
		elseif( $this->sort === 'default' || $this->sort === 'all' || $this->sort === 'wanted_older') {
			$array_results = $this->sortResultsByAvailable($incomingArrayToSort);
		}
		// default sort is by [2] => Series
		// (($sort === 'series_wanted') || ($sort === 'series_owned'))
		else {
			$array_results = $this->sortResultsBySeries($incomingArrayToSort);
		}
		
		return $array_results;
	}
	
	
	/******************************************
	 * Sort by Series then Character within Series
	 *
	 * @param array $incomingArrayToSort
	 * @return array
	 */
	function sortResultsBySeries($incomingArrayToSort) {
		
		$seriesArray = [];
		$characterArray = [];
		
		if(is_array($incomingArrayToSort)){
			
			foreach ($incomingArrayToSort as $key => $subArray){
				//get values to sort
				$seriesArray[$key] = $subArray[3];
				$characterArray[$key] = $subArray[4];
			}
			
			array_multisort($seriesArray, SORT_ASC,
					$characterArray, SORT_ASC,
					$incomingArrayToSort);
		}
		
		//	echo "<pre> SortedArray: ";
		//	echo print_r($incomingArrayToSort);
		//	echo "</pre>";
		
		return $incomingArrayToSort;
	}
	
	
	/******************************************
	 * Sort by Character
	 *
	 * @param array $incomingArrayToSort
	 * @return array
	 */
	function sortResultsByCharacter($incomingArrayToSort) {
		
		$characterArray = [];
		
		if(is_array($incomingArrayToSort)){
			
			foreach ($incomingArrayToSort as $key => $subArray){
				//get values to sort
				$characterArray[$key] = $subArray[4];
			}
			
			array_multisort($characterArray, SORT_ASC, $incomingArrayToSort);
		}
		
		//	echo "<pre> SortedArray: ";
		//	echo print_r($incomingArrayToSort);
		//	echo "</pre>";
		
		return $incomingArrayToSort;
	}
	
	
	/******************************************
	 * Sort by Available Date
	 *
	 * @param array $incomingArrayToSort
	 * @return array
	 */
	function sortResultsByAvailable($incomingArrayToSort) {
		
		//echo '<pre>';
		//echo print_r($incomingArrayToSort);
		//echo '</pre>';
		
		$characterArray = [];
		
		if(is_array($incomingArrayToSort)){
			
			foreach ($incomingArrayToSort as $key => $subArray){
				//get values to sort
				$characterArray[$key] = $subArray[7];
			}
			
			array_multisort($characterArray, SORT_ASC, $incomingArrayToSort);
		}
		
		//	echo "<pre> SortedArray: ";
		//	echo print_r($incomingArrayToSort);
		//	echo "</pre>";
		
		return $incomingArrayToSort;
	}
	
	/******************************************
	 *
	 * @param array $incomingArray
	 * @return boolean
	 */
	function getStats($incomingArray) {
		
		//reset values for good measure
		$this->ownedCount = 0;
		$this->wantedCount = 0;
		$this->availableCount = 0;
		$this->spent = 0;
		$this->average = 0;
		$this->percentage = 0;
		$this->value = 0;
		
		foreach($incomingArray as $array) {
						
			$this->availableCount++;
			
			if($array['owned'] === '1') {
				$this->ownedCount++;
				if(floatval($array['purchased_price']) > 0) {
					$this->spent += floatval($array['purchased_price']);
				}
				else {
					$this->spent += floatval(5.95);
				}

                if(floatval($array['value']) > 0) {
					$this->value += floatval($array['value']);
				}
				else {
					$this->value += floatval(5.95);
				}
			}
			else {
				$this->wantedCount++;
			}
			
		} //END foreach
		
		//calculate average
		$this->average = round(($this->spent / $this->ownedCount), 2);
		$this->average = number_format($this->average,2);
		
		$this->percentage = round((($this->ownedCount / $this->availableCount) * 100), 0, PHP_ROUND_HALF_DOWN);
		
		$this->spent = number_format($this->spent,2);

        $this->value = number_format($this->value,2);
		
		return TRUE;
		
	}
	
	/******************************************
	 * This will filter out all we do not want
	 *
	 * @param array $incomingArray
	 * @return array $filteredArray
	 */
	function filterResults($incomingArray) {
		
		$filteredArray = [];
		
		if($this->sort === 'all') {
			return $incomingArray;
		}
		
		foreach($incomingArray as $key => $array) {
			
			
			// If Search value matches any part of the array
			if(!empty($this->search)) {
				$pattern = '/\b' . $this->search . '/i'; //make case insensitive
				//if there is a match, put into $filteredArray
				if(preg_grep($pattern, $array))  {
					$filteredArray[$key] = $array;
				}
			}
			elseif(!empty($this->sort)) {
				// Else if we want all I own
				if(($this->sort === 'character_owned') || ($this->sort === 'series_owned')) {
					if($array['owned'] === '1') {
						$filteredArray[$key] = $array;
					}
				}
				// Else if we want all I need
				elseif(($this->sort === 'character_wanted') || ($this->sort === 'series_wanted')) {
					if($array['owned'] !== '1') {
						$filteredArray[$key] = $array;
					}
				}
				elseif($this->sort === 'default' || $this->sort === 'wanted_older') {
					$splitAvailableDate = ''; //this is the array of matches
					preg_match("/\d{4}-\d{2}/", $array['available_date'], $splitAvailableDate);
					
					if(!empty($splitAvailableDate)) {
						$splitAvailableDate = explode("-", $splitAvailableDate[0]);
						$available_date_compare = intval($splitAvailableDate[0] . $splitAvailableDate[1]);
						
						if($this->sort === 'default') {
							if( (intval($this->currentDate) <= $available_date_compare) && ($array['owned'] !== '1') ) {
								$filteredArray[$key] = $array;
							}
						}
						elseif($this->sort === 'wanted_older') {
							if( (intval($this->currentDate) > $available_date_compare) && ($array['owned'] !== '1') ) {
								$filteredArray[$key] = $array;
							}
						}
					}
				}
			}
			else {
				$filteredArray = $incomingArray;
			}
			
		} //END foreach
		
		
		// lets see if there were no results
		if(empty($filteredArray)) {
			if(!empty($this->search)) {
				$this->search_results = 'No Items Found!';
			}
			$this->search = '';
			$this->sort = 'character_wanted';
			$filteredArray = $this->filterResults($incomingArray);
		}
		
		// lets return filtered array, it is possible to be emtpy
		return $filteredArray;
		
	}
	
	/**
	 * 
	 * @param unknown $array_results
	 * @return string
	 */
	function display_content($array) {
		
		//reset values
		$funko_character_id = '';
		$number = '';
		$own = '';
		$funkoSeries = '';
		$character = '';
		$funko_status = '';
		$exclusive = '';
		$notes = '';
		$available_date = '';
		$tags = '';
		$qty = '';

        $value = '';
        $value_date = '';
        $value_source = '';

		$purchaced_price = '';
		$purchased_date = '';
		$purchased_from = '';

		$barcode = '';
		$image_storage = '';
		$order_date = '';
		$image_http = '';

		
		
		// assign values
		$funko_character_id = $array['funko_character_id'];
		$number = $array['funko_number'];
		if($array['owned'] === '1') {
			$own = '<span class="text-success">Yes</span>';
		}
		else {
			$own = '<span class="text-danger">No</span>';
		}
		
		$funkoSeries = $array['funko_series'];
		
		$character = $array['funko_character'];
		if($array['funko_status'] === '1') {
			$funko_status = '<span class="text-danger">Yes</span>';
		}
		else {
			$funko_status = '';
		}
		$exclusive = $array['exclusive'];
		$notes = $array['notes'];
		$available_date = $array['available_date'];
		$tags = $array['tags'];
		$qty = $array['quantity_owned'];

		$value = $array['value'];
        $value_date = $array['value_date'];
        $value_source = $array['value_source'];

		$purchaced_price = $array['purchased_price'];
		$purchased_date = $array['purchased_date'];
		$purchased_from = $array['purchased_from'];

		$barcode = $array['barcode'];
		$image_storage = $array['image'];


        $display_ordered = '';
		if($array['owned'] !== '1' && $array['ordered_date'] !== '1970-01-01') {
			$order_date = $array['ordered_date'];
            $display_ordered = <<<EOF

							<div class="row">
								<div class="col-xs-4">Ordered: </div>
								<div class="col-xs-8 text-success"> $order_date</div>
							</div>

EOF;
		}

		$display_image = '';
		if(!empty($image_storage)) {
			$display_image = $image_storage;
		}

		
		$content = '';
		$content .= <<<EOF
		
				<div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 mt-2">
				<!-- BEGIN Column -->
					<div class="card border border-primary rounded">
					<!-- BEGIN Panel -->
						<div class="card-header bg-primary text-white">
							<div class="d-flex justify-content-between">
								<div><a href="https://www.jaycroft.com/funko_management/?funko_character_id=$funko_character_id">$funko_character_id</a></div>
								<div><a style="color:white;" href="https://www.jaycroft.com/funko/details.php?funko_number=$number">Details</a></div>
							</div>
						</div>
						
						<div class="card-body" style="min-height:440px; max-height:440px" >
						<!-- BEGIN Panel Body -->
						
							<div class="row">
								<div class="col-xs-4">Own: </div>
								<div class="col-xs-8" id=""> $own</div>
							</div>
							
                            $display_ordered

							<div class="row">
								<div class="col-xs-4">Funko ID: </div>
								<div class="col-xs-8" id=""> $number</div>
							</div>
							
							<div class="row">
								<div class="col-xs-4">Character: </div>
								<div class="col-xs-8" id=""> $character</div>
							</div>
							
							<div class="row">
								<div class="col-xs-4">Series: </div>
								<div class="col-xs-8" id=""> $funkoSeries</div>
							</div>
			
							<div class="row">
								<div class="col-xs-4">UPC: </div>
								<div class="col-xs-8" id=""> $barcode</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<a href="$display_image" data-toggle="lightbox">
										<img style="max-height:240px" src="$display_image" class="img-fluid mx-auto d-block">
									</a>
								</div>
							</div>
										
						<!-- END Panel Body -->
						</div>
					<!-- END Panel -->
					</div>
				<!-- END Column -->
				</div>
										
EOF;
		
		
		return $content;
		
	} //Display Content
	
} //END Class

?>
