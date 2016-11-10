<?php
require_once('core/init.php');
$inputs = array(
    'username'      => '',
    'password'      => ''
);
$replaces = array(
    'widget' => '',
    'login_error' => '',
    'user' => '',
    'content' => '',
    'redirect' => $_SERVER['PHP_SELF'],
    'date' =>  date("Y-m-d")
);
$errors = array(
    "fail" => "Bad username/password!",
    "required" => 'All fields must be filled!',
    'loginrequired' => 'You must be logged in to view downloads!',
    "misc" => "There has been an error. Try again!"
);
if(isset($_SESSION['user'])){
    $replaces['widget'] = 'htmls/userinfo.html';
    $replaces['user'] =$_SESSION['user'];

}
else{
    $replaces['widget'] = 'htmls/widget.html';

}
$replaces['content'] = file_get_contents("htmls/contactinfo.html");
$html = file_get_contents('contactus.html');
$html = Builder::buildHTML($replaces,$html);
$output = $html;
echo $output;