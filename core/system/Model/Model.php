<?php
/**
 * Created by PhpStorm.
 * User: andrii
 * Date: 07.02.15
 * Time: 17:42
 */

namespace Balon\System;


use Balon\DBProc;

abstract class Model {

    public $db;

    function __construct()
    {
        $this->db = DBProc::instance();
        // TODO: Implement __construct() method.
    }


}