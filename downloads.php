<?php
require_once('core/init.php');

if (Input::exists('file','get')){
    $file =  'files/'.Input::get('get','file','');
    if(file_exists($file)){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
$inputs = array(
    'username'      => '',
    'password'      => ''
);
$replaces = array(
    'widget' => '',
    'login_error' => '',
    'user' => '',
    'content' => '',
    'file' => file_get_contents('htmls/downloads_link.html'),
    'filename' => 'prog3_tools.pdf',
    'redirect' => $_SERVER['REQUEST_URI']
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
    $replaces['content'] = file_get_contents('htmls/downloads_content.html');
}
else{
    $replaces['widget'] = 'htmls/widget.html';
    $replaces['content'] = $errors['loginrequired'];
}
$html = file_get_contents('downloads.html');
$html = Builder::buildHTML($replaces,$html);
$output = $html;
echo $output;