<?php

try{
    $handler = new PDO("mysql:host=eu-cdbr-west-01.cleardb.com;dbname=heroku_d5b1c5f3db91d1b", 'b42e7445f5957f', '6d2dc498');
}
catch(PDOException $e){
    die($e->getMessage());
}
$handler->exec('CREATE database IF NOT EXISTS dinya');
