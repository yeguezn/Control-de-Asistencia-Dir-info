<?php

    function autocargar($className){
       
        include 'Controllers/' . $className . '.php';

    }

    spl_autoload_register('autocargar');

?>