<?php
/**
 * Created by PhpStorm.
 * User: Balon
 * Date: 17.02.2015
 * Time: 01:44
 */

namespace Model;
use Balon\Cache;
use Balon\DBProc;
use Balon\System;

class News extends System\Model{

    function __construct()
    {
        parent::__construct();
        // TODO: Implement __construct() method.
    }

    public function loadNews($id)
    {
        $cache = Cache::instance();
        $cache->incrementViews("news",$_GET['id']);
        $sql = "SELECT n.*,c.`name` FROM `t_news` AS n LEFT JOIN `t_chapter` AS c
          ON n.`id_chapter` = c.`id` WHERE n.`id` = ".$_GET['id'];
        $result = $this->db->send_query($sql);
        //print_r ($result);
        //$result = $this->db->select("news",false,['id' => $id]);
        foreach ($result as $key => $val) {
            $date = new \DateTime($val['create_date']);
            $day = $date->format("j");
            $month = \Balon\Date::getMonth($date->format("n"));
            $time = $date->format("H:i");
            $result[$key]['create_date'] = "$day $month, $time";
        }
        $result[0]['views'] = $cache->get("news",$_GET['id'])['views'];
        //print_r ($result);
        return $result[0];
    }

    public function loadList($id) {
        $cache = Cache::instance();
        $result = $this->db->select("news",[false],
            [
                "id_chapter" => $id
            ], "create_date",false,[0,10]);
        echo "ok";
        echo "<pre>";
        print_r ($result);
        echo "</pre>";
        foreach ($result as $key => $val) {
            $array[] = $val['id'];
        }
        $views = $cache->get("news",$array);
        foreach ($result as $key => $val) {
            // тут може бути кількість коментарів
            $result[$key]['text'] = mb_substr($result[$key]['text'],0,250,'UTF-8')."...";
            $result[$key]['views'] = $views[$val['id']]['views'];
            $date = new \DateTime($val['create_date']);
            $day = $date->format("j");
            $month = \Balon\Date::getMonth($date->format("n"));
            $time = $date->format("H:i");
            $result[$key]['time'] = "$time";
            if (date("j") == $date->format("j")
                && $date->format("n") == date('n')
                && $date->format("Y") == date('Y')) {
                $results['сьогодні'][] = $result[$key];
            }
            elseif (date("j") - $date->format("j") == 1
                && $date->format("n") == date('n')
                && $date->format("Y") == date('Y')) {
                $results['вчора'][] = $result[$key];
            }
            else {
                $results["$day $month"][] = $result[$key];
            }
        }
        return $results;
    }
}