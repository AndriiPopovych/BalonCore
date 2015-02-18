<?php
/**
 * Created by PhpStorm.
 * User: Balon
 * Date: 17.02.2015
 * Time: 01:44
 */

namespace Controller;
use Balon\System;

class News extends System\Controller{



    function __construct()
    {
        $this->array = ["politics" => 1,"world" => 2,"economic" => 3,"culture" => 4,"sport" => 5];
        $this->model = new \Model\News();
        $this->view = new \View\News();
        // TODO: Implement __construct() method.
    }

    public function index()
    {

    }

    public function loadNews($id) {
        if (!empty($_GET['id'])) {
            $data = $this->model->loadNews($id);
            $this->view->loadNews($data);
        }
        else {
            $data = $this->model->loadList($id);
            $this->view->loadList($data);
        }
    }

    function __call($name, $args) {
        if ($this->array[$name]) {
            $this->loadNews($this->array[$name]);
        }
        else {
            echo "404 ERROR";
            //TODO: Дописати щоб перенаправляло на сторінку 404
        }
    }
} 