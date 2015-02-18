<?php

    /**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 01.05.14
 * Time: 3:25
 *
 * @property mixed _instance
*/

namespace Balon;

class Cache {

    private $cache;

    final private function __construct() {
        //$this->db = DBProc::instance();
        //$this->cache = new Memcache();
        ///  $this->cache->addServer("unix:///home/plcb/.system/memcache/socket", 11211, 30);
    }

    static function instance () {
        static $instance;
        if (isset($instance)) {
            return $instance;
        }
        $instance = new static;
        return $instance;
    }

    function setCacheSqlQuery($sql,$table,$value) {
        return false;
        $result = $this->cache->get("sql_query");
        $queries = json_decode($result,true);
        if ($queries) {
            foreach ($queries as $key => $value) {
                if ($value['query'] == $sql) {
                    $queries[$key['value']] = $value;
                    //$results = json_decode($value['value'],true);
                }
            }
        }
    }

    function getCacheSqlQuery($sql) {
        return false;
        $result = $this->cache->get("sql_query");
        $queries = json_decode($result,true);
        if ($queries) {
            foreach ($queries as $key => $value) {
                if ($value['query'] == $sql) {
                    $results = json_decode($value['value'],true);
                }
            }
        }
        return $results;
    }



    function incrementViews($file,$id) {
        if (!file_exists("cache/$file.json")) {
            //?????
            file_put_contents("cache/$file.json",json_encode([$id => ["views" => 1, "comments" => 0]]));
        }
        else {
            $content = file_get_contents("cache/$file.json");
            $content = $this->getCache($file);
            @$content[$id]->views++;
            $this->setCache($file,$content);
        }
    }



    function incrementComment($file,$id) {
        if (!file_exists("cache/$file.json")) {
            file_put_contents("cache/$file.json",json_encode([$id => ["views" => 1, "comments" => 1]]));
        }
        else {
            $content = file_get_contents("cache/$file.json");
            $content = $this->getCache($file);
            $content[$id]->comments++;
            $this->setCache($file,$content);
        }
    }

    function deincrementComment($file,$id) {
        if (!file_exists("cache/$file.json")) {
            file_put_contents("cache/$file.json",json_encode([$id => ["views" => 1, "comments" => 1]]));
        }
        else {
            $content = file_get_contents("cache/$file.json");
            $content = $this->getCache($file);
            $content[$id]->comments--;
            $this->setCache($file,$content);
        }
    }

    function getInfo($file,$id) {
        $array = $this->getCache($file);
        return [$array[$id]->views,$array[$id]->comments];
    }

    function refresh($val){
        //$val = "bloggers";
        $array = $this->db->select($val,false,false,"id",false);
        $this->setCache($val,$array);

    }

    function refreshAll() {
        $dir = FileSystem::fileList("cache");
        foreach ($dir as $key => $name) {
            $this->refresh(explode(".",$name)[0]);
        }
    }

    static public function setCache($val,$array) {
        $array = json_encode($array);
        $file = $_SERVER['DOCUMENT_ROOT']."/cache/".trim($val).".json";
        file_put_contents($file,$array);
    }


    static public function get($file,$number) {
        if (file_exists("cache/$file.json"))
            $array = file_get_contents("cache/$file.json");
        $array = json_decode($array,true);
        if (is_array($number)) {
            foreach ($number as $key => $val) {
                $result[$val] = $array[$val];
            }
        }
        else {
            $result = $array[$number];
        }
        return $result;

    }

    static public function getCache($val,$where = "") {
        //get file
        if (!file_exists("cache/$val.json"))
            file_put_contents("cache/$val.json","");
        $file = file_get_contents("cache/$val.json");
        $new_arr = json_decode($file);
        if ($new_arr) {
            foreach ($new_arr as $key=> $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        if ($where) {
                            $part = explode(";", $where);
                            if ($part[1]) {
                                foreach ($part as $num => $value) {
                                    $wheres = explode("=", $value);
                                    if (trim($k) == trim($wheres[0]) && trim($val->$k) == trim($wheres[1])) {
                                        $true = true;
                                    }
                                }
                            } else {
                                $wheres = explode("=", $where);
                                if (trim($k) == trim($wheres[0]) && trim($val->$k) == trim($wheres[1])) {
                                    $true = true;
                                }
                            }
                        }

                        $n_arr[$key][$k] = $val->$k;
                    }
                    if ($true) {
                        //$res_where[$key] = $n_arr[$key];
                        $res_where[] = $n_arr[$key];
                        $true = false;
                    }
                }
                else {
                    $n_arr[$key] = $val;
                }
            }
            if ($where) {
                //костыль, бо заїбався
                if ($wheres[0] == "views") {
                    $res_where[0] = end($res_where);
                }
                return $res_where;
            }
        }
        return $n_arr;
    }

    function __get($value) {
        if ($value == "db") {
            $this->$value = DBProc::instance();
            return $this->$value;
        }
    }


} 