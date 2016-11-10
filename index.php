<?php
require_once "core/init.php";
function login($inputs, $errors, &$replaces){
    $inputs = Input::getinputs('post',$inputs,'');
    if(!empty($_POST) && validate($inputs, $errors, $replaces)){

            Redirect::to(Input::get('get','redirect','index')); die();
    }
}
function validate($inputs, &$errors, &$replaces){

    foreach ($inputs as $input) {
        if(!$input) {$replaces['login_error'] = $errors['required']; return false;}
    }
    $user =  new User();
    if($user ->logIn($inputs['username'],$inputs['password'])) return true;
    $replaces['login_error'] = $errors['fail']; return false;

}
function logout(){
    session_destroy();
    Redirect::to(Input::get('get','redirect','index'));
}
$output = "";
$action = "";
$sql = DB::getInstance();
$html = file_get_contents('index.html');
$inputs = array(
    'username'      => '',
    'password'      => ''


);
$replaces = array(
    'widget' => '',
    'login_error' => '',
    'user' => '',
    'redirect' => $_SERVER['REQUEST_URI']

);
$errors = array(
    "fail" => "Bad username/password!",
    "required" => 'All fields must be filled!',
    "misc" => "There has been an error. Try again!"
);
if(Input::exists('action', 'get')){
    $action = Input::get('get', 'action','');
    switch ($action){
        case 'login': login($inputs, $errors, $replaces); break;
        case 'logout': logout(); break;

    }
}
if(isset($_SESSION['user'])){
    $replaces['widget'] = 'htmls/userinfo.html';
    $replaces['user'] =$_SESSION['user'];
}
else{
    $replaces['widget'] = 'htmls/widget.html';}

$html = Builder::buildHTML($replaces,$html);
$output = $html;
echo $output;








