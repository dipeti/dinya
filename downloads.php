<?php
require_once('core/init.php');

if (Input::exists('file','get')){
    if(isset($_SESSION['user'])){
    $file =  'files/'.Input::get('get','file','');
    if(file_exists($file)){
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }
        else die('Requested file does not exist...');
    }
    else {Redirect::to($_SERVER['PHP_SELF']); die('You must be logged in!');}
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
    'redirect' => $_SERVER['REQUEST_URI']
);
$errors = array(
    "fail" => "Bad username/password!",
    "required" => 'All fields must be filled!',
    'loginrequired' => 'You must be logged in to view downloads!',
    "misc" => "There has been an error. Try again!"
);

$filenames = array();
$filenames  = array_diff(scandir('files/'),array('..','.'));
$rows = '';
foreach ($filenames as $filename) {
    if (file_exists('files/'.$filename)){
    $rows.= Builder::buildHTML(array('filename' => $filename),file_get_contents('htmls/downloads_link.html'));
    }
}
$fileshtml = Builder::buildHTML(array('files' => $rows), file_get_contents('htmls/downloads_content.html'));
if(isset($_SESSION['user'])){
    $replaces['widget'] = 'htmls/userinfo.html';
    $replaces['user'] =$_SESSION['user'];
    $replaces['content'] = $fileshtml;
}
else{
    $replaces['widget'] = 'htmls/widget.html';
    $replaces['content'] = $errors['loginrequired'];
}
$html = file_get_contents('downloads.html');
$html = Builder::buildHTML($replaces,$html);
$output = $html;
echo $output;