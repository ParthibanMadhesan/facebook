<?php
 
function sanitizeinput($data){
    return htmlspecialchars(trim($data));
}

function dd($value){
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();


}

?>