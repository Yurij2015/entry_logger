<?php
require("rb.php");
R::setup('mysql:host=localhost;port=3306;dbname=entry_logger', 'root', '');

//try{
//    $db = new PDO('mysql:host=localhost;port=3306;dbname=entry_logger', 'root', '');
//} catch(PDOException $e){
//    echo $e->getmessage();
//}

$image_url = "";
$index = $_GET['file'] ?? "";
if ($index === "index1") {
    $image_url = 'https://cdn.educba.com/academy/wp-content/uploads/2020/03/Embed-PHP-in-HTML.jpg';
}
if ($index === "index2") {
    $image_url = 'https://www.chandigarhhelp.com/wp-content/uploads/2020/01/Top-PHP-Training-Institutes-in-Chandigarh.jpg';
}

header('Content-Type: image/jpeg');
readfile($image_url);

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$ip_adress = $_SERVER['REMOTE_ADDR'];
$view_date = date("Y-m-d H:i:s");
$page_url = $index;
$view_count = 1;

$find_store_data = R::findOne('storeddata', ' ip_address = ? AND user_agent = ? AND page_url = ? ', [
    [$ip_adress, PDO::PARAM_STR],
    [$user_agent, PDO::PARAM_STR],
    [$page_url, PDO::PARAM_STR],
]);

if ($find_store_data) {
    update($find_store_data->id, $view_date, $find_store_data->view_count);
} else {
    safe($user_agent, $ip_adress, $view_date, $page_url, $view_count);
}

function update($id, $view_date, $view_count)
{
    $storeddata = R::load('storeddata', $id);
    $storeddata->view_date = $view_date;
    $storeddata->view_count = $view_count + 1;
    R::store($storeddata);
}

function safe($user_agent, $ip_adress, $view_date, $page_url, $view_count)
{
    $storeddata = R::dispense('storeddata');
    $storeddata->ip_address = $ip_adress;
    $storeddata->user_agent = $user_agent;
    $storeddata->view_date = $view_date;
    $storeddata->page_url = $page_url;
    $storeddata->view_count = $view_count;
    R::store($storeddata);
}




