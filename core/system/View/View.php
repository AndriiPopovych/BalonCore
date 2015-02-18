<?php
/**
 * Created by PhpStorm.
 * User: Андрій
 * Date: 02.01.2015
 * Time: 23:48
 */

namespace Balon\System;


abstract class View {

    function __construct()
    {
        // TODO: Implement __construct() method.
    }

    public function loadContent($params = []) {
        include_once ("./view/content.php");
    }

    protected function loadHead($params = []) {
        if (file_exists("./view/head.php")) {
            include_once("./view/head.php");
        }
    }

    protected function loadFooter($params = []) {
        if (file_exists("./view/footer.php")) {
            include_once("./view/footer.php");
        }
    }

    protected function loadHeader($params = []) {
        if (file_exists("./view/header.php")) {
            include_once("./view/header.php");
        }
    }

    protected function loadMenu($params = []) {
        if (file_exists("./view/menu.php")) {
            include_once("./view/menu.php");
        }
    }

    protected function loadSidebar($params = []) {
        if (file_exists("./view/sidebar.php")) {
            include_once("./view/sidebar.php");
        }
    }

    protected function loadModal($params = []) {
        if (file_exists("./view/modal.php")) {
            include_once("./view/modal.php");
        }
        elseif (file_exists("./view/none_element.php")) {
            include_once("./view/none_element.php");
        }
    }
}