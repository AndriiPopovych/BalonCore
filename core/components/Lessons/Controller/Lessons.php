<?php
/**
 * Created by PhpStorm.
 * User: Balon
 * Date: 12.02.2015
 * Time: 14:17
 */

namespace Controller;
use Balon\System;

class Lessons extends System\Controller{

    function __construct()
    {
        $this->model = new \Model\Main();
        $this->view = new \View\Main();
        // TODO: Implement __construct() method.
    }

    public function index()
    {
        $val = [
            "D" => 36.6,
            "R" => 63.4,
        ];
        $table = [
            "CH4" => 0.35,
            "C2H6" => 0.73,
            "C3H8" => 37.51,

        ];
        $val['y`D1'] = $table['CH4']/$val['D'];
        $val['y`D2'] = $table['C2H6']/$val['D'];
        $val['y`D2'] = $table['C2H6']/$val['D'];
        echo "<pre>";
        print_r ($val);
    }
}

?>
