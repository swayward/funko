<?php
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
include_once 'functions/index_functions.php';
$index_functions = new index_functions;

$index_functions->set_post();

$mysql_results = $index_functions->get_content_mysql();

$array_results = '';
if(is_array($mysql_results)) {
	$array_results = [];
	$index_functions->getStats($mysql_results);
	$array_results = $index_functions->filterResults($mysql_results);
	$array_results =  $index_functions->masterSort($array_results);
}

if(is_array($array_results)) {
	foreach($array_results as $array) {
		$content .= $index_functions->display_content($array);
	}
}

//need these values for layout/index.html
$ownedCount = $index_functions->get_ownedCount();
$wantedCount = $index_functions->get_wantedCount();
$availableCount = $index_functions->get_availableCount();
$spent = $index_functions->get_spent();
$average = $index_functions->get_average();
$percentage = $index_functions->get_percentage();
$value = $index_functions->get_value();


$search = $index_functions->get_search();
$search_value = $index_functions->get_search_value();
$search_results = $index_functions->get_search_results();

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
include 'layout/index.html';


/********************
Close the page
********************/
include 'layout/page_close.html';

