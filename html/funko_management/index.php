<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


/**********************************
Required Pages
***********************************/
include_once('functions/edit_funko_functions.php');
$edit_funko = new edit_funko;

$edit_funko->set_debug(TRUE);

//delete debug file if file exists and debug = TRUE
if($edit_funko->is_debug() && $edit_funko->debug_file_exist()) {
	$edit_funko->delete_debug();
}

/*********************
Get Content
*********************/

//get values from POST or GET or FILES
$edit_funko->set_post();

$edit_funko->edit_funko_do();

$funko_character_id = $edit_funko->get_funko_character_id();
$funko_number = $edit_funko->get_funko_number();
$funko_owned = $edit_funko->get_funko_owned();
$funko_series = $edit_funko->get_funko_series();
$funko_character = $edit_funko->get_funko_character();
$funko_status = $edit_funko->get_funko_status();
$exclusive = $edit_funko->get_exclusive();
$funko_notes = $edit_funko->get_funko_notes();
$funko_available_date = $edit_funko->get_funko_available_date();
$funko_tags = $edit_funko->get_funko_tags();
$funko_quantity = $edit_funko->get_funko_quantity();

$funko_value = $edit_funko->get_funko_value();
$funko_value_date = $edit_funko->get_funko_value_date();
$funko_value_source = $edit_funko->get_funko_value_source();

$funko_price = $edit_funko->get_funko_price();
$funko_purchased_date = $edit_funko->get_funko_purchased_date();
$funko_seller = $edit_funko->get_funko_seller();
$funko_barcode = $edit_funko->get_funko_barcode();
$funko_image = $edit_funko->get_funko_image();
$funko_ordered_date = $edit_funko->get_funko_ordered_date();

$insert_date = $edit_funko->get_insert_date();
$insert_by = $edit_funko->get_insert_by();
$update_date = $edit_funko->get_update_date();
$update_by = $edit_funko->get_update_by();

$results = $edit_funko->get_results();
$results_color = $edit_funko->get_results_color();

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
Content goes here
********************/
include 'layout/edit_funko.html';


/********************
Close the page
********************/
include 'layout/page_close.html';

