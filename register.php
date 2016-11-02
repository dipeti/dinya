<?php
require('core/init.php');
//FUNCTIONS

function validate($inputs, &$errors, &$replaces ,DB $sql){
    foreach ($inputs as $input) {
        if(!$input) {$replaces['register_error'] = $errors['required']; return false;}
    }
    if($inputs['password']!==$inputs['passwordagain']){
        $replaces['register_error'] = $errors['match']; return false;
    }

    if($sql->select('users',['username','=',$inputs['username']])->getRowNum() !== 0){
        $replaces['register_error'] = $errors['username']; return false;
    };
    if($sql->insert('users',[
        'username'  => $inputs['username'],
        'password'  => sha1($inputs['password']),
        'email'     => $inputs['email'],
    ])) return true;
    $replaces['register_error'] = $errors['misc'];
    return false;
}

//*****************************

$sql = DB::getInstance();
$html = file_get_contents('htmls/register.html');
$inputs = array(
    'username'      => '',
    'password'      => '',
    'passwordagain' => '',
    'email'         => ''
 );
$replaces = array(
    'content' => '',
    'username' => '',
    'register_error' => ''
);
$errors = array(
    "match" => "The two passwords must match!",
    "username" => "Username is already taken!",
    "required" => 'All fields must be filled!',
    "misc" => "There has been an error. Try again!"
);
if(Input::exists('success', 'get')){
    $replaces['content'] = 'htmls/register_success.html';
    $html = Builder::buildHTML($replaces,$html);

}
else{

    $inputs = Input::getinputs('post',$inputs,'');
    if(!empty($_POST) && validate($inputs, $errors, $replaces, $sql)){
        Redirect::to('register.php?success');
    }
    else{

    $replaces['content'] = 'htmls/registerform.html';
    $replaces['username'] = $inputs['username'];
       $html=  Builder::buildHTML($replaces,$html);
    }
}
$output = $html;
echo $output;


