<?php

/**/
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**********************************
Set Variables
***********************************/
$content = '';


/**********************************
Required Pages
***********************************/
//page start
include_once 'functions/page_start_functions.php';
$page_functions = new page_functions;

$page_functions->set_debug(FALSE);

//delete debug file if file exists and debug = TRUE
if($page_functions->is_debug() && $page_functions->debug_file_exist()) {
	$page_functions->delete_debug();
}


$add_headers = <<<ah

		<script src="js/lightbox-master/ekko-lightbox.js"></script>
		<link href="js/lightbox-master/ekko-lightbox.css" rel="stylesheet">

ah;

$closing_scripts = <<<cs

		<script>
			$(document).on('click', '[data-toggle="lightbox"]', function(event) {
				event.preventDefault();
				$(this).ekkoLightbox();
			});
		</script>

cs;

/*********************
Get Content

//example:
include_once('functions/news_functions.php');
$news_functions = new news_functions;
$pinned_news = $news_functions->display_news(1);
$other_news = $news_functions->display_news(0);
*********************/

//get content
include_once 'functions/details_functions.php';
$details_functions = new details_functions;

$details_functions->set_debug(FALSE);

$details_functions->set_post();

$mysql_results = $details_functions->do_get_details();

if(is_array($mysql_results)) {
    //set values for display
    $details_functions->do_set_values($mysql_results);
}


$funko_character_id = $details_functions->get_funko_character_id();
$funko_number = $details_functions->get_funko_number();
$funko_owned = $details_functions->get_funko_owned();

$funko_series = $details_functions->get_funko_series();
$funko_character = $details_functions->get_funko_character();

$funko_vaulted = $details_functions->get_funko_status();

$exclusive = $details_functions->get_exclusive();

$funko_notes = $details_functions->get_funko_notes();
$funko_available_date = $details_functions->get_funko_available_date();
$funko_tags = $details_functions->get_funko_tags();
$funko_quantity = $details_functions->get_funko_quantity();

$funko_value = $details_functions->get_funko_value();
$funko_value_date = $details_functions->get_funko_value_date();
$funko_value_source = $details_functions->get_funko_value_source();

$funko_price = $details_functions->get_funko_price();
$funko_purchased_date = $details_functions->get_funko_purchased_date();
$funko_seller = $details_functions->get_funko_seller();

$funko_barcode = $details_functions->get_funko_barcode();
$funko_image = $details_functions->get_funko_image();
$funko_ordered_date = $details_functions->get_funko_ordered_date();

$insert_date = $details_functions->get_insert_date();
$insert_by = $details_functions->get_insert_by();
$update_date = $details_functions->get_update_date();
$update_by = $details_functions->get_update_by();

/*********************
h2 page-description
*********************/
$page_description = "";

/*******************
HTML Header tags
*******************/
//title tag
$title_tag = "";

//keywords
$meta_keywords = "";

$meta_description = "";


/********************
open the page
********************/
include 'layout/page_open.html';


/********************
Display Content here

from Get Content section above
echo $pinned_news;
echo $display_project_news;
echo $other_news;
********************/
include 'layout/details.html';


/********************
Close the page
********************/
include 'layout/page_close.html';

