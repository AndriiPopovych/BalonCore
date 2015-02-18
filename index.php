<?php

        /**
        * @author balon
        * @copyright 2014
        */


        session_start();
        error_reporting(1);
        require ('core/lib/php/autoloader.php');
        require ('config.php');
        define("DEV_MOD",true);

        $rout = new \Balon\Routing();
        $rout->loadPage();


?>
