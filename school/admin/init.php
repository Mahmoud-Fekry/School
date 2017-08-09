<?php

include '../connect.php';

$func = 'include/functions/';            //functions path
$temp = 'include/template/';           //template path
$css = 'layout/css/';                  //css path
$js = 'layout/js/';                   //js path


// Include the important file
include $func . 'functions.php';
include $temp . 'header.php';

if (!isset($noNavbar))
    include $temp . 'navbar.php';
?>