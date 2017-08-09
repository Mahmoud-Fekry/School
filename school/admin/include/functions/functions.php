<?php


/*
**
*** getTitle function v1.0
*** Title function that show page title if the page have variable $pageTitle and show default title for other pages
**
*/

function getTitle() {
    
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    } else {
        echo 'Default';
    }
}




/*
**
*** checkItem function v1.0
*** This function check if item exist in database [this function accept parameters]
*** $item = The item to select 
*** $table = the table to select from
*** $value = the value of $select
**
*/


function checkItem  ($item, $table, $value){
    global $con;
    
    $stmt2 = $con->prepare("Select  $item  From  $table  WHERE $item=? ");
    
    $stmt2->execute(array($value));
    
    $resultNum = $stmt2->rowCount();
    
    return $resultNum;
}
