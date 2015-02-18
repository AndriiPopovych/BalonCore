<?php
/**
 * Created by PhpStorm.
 * User: andrii
 * Date: 17.02.15
 * Time: 0:23
 */

namespace Balon\System;


use Balon\DBProc;

class Article {

    public $table = "news";

    private $db;

    function __construct()
    {
        // TODO: Implement __construct() method.
    }

    function loadList($filter) {
        $this->db = DBProc::instance();
        $result = $this->db->select($this->table,false,
            [
                array_keys($filter)[0] => array_values($filter)[0]
            ], 'create_date', false, [0,4]
        );
        return $result;
    }

}