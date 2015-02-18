<?php
/**
 * Created by PhpStorm.
 * User: {{user}}
 * Date: {{date}}
 * Time: {{time}}
 */

namespace Controller;
use Balon\System;

class {{name}} extends System\Controller{

    function __construct()
    {
        $this->model = new \Model\Main();
        $this->view = new \View\Main();
        // TODO: Implement __construct() method.
    }

    public function index()
    {

    }
} 