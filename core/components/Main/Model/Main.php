<?php

namespace Model;

use Balon\Date;
use Balon\DBProc;
use Balon\System\Article;

class Main {

    private $db;

    function __construct()
    {
        $this->db = DBProc::instance();// TODO: comment this when finish
    }

    public function index() {
        $data['slider'] = $this->loadSlider();
        $data['politics'] = $this->loadNewsList(1);
        return $data;
        ///saf
    }

    private function loadSlider() {
        $result = $this->db->select("slider");
        return $result;
    }

    private function loadNewsList($chapter) {
        $article = new Article();
        $result = $article->loadList(["id_chapter" => $chapter]);
        foreach ($result as $key => $val) {
            $date = new \DateTime($val['create_date']);
            $day = $date->format("j");
            $month = Date::getMonth($date->format("n"));
            $time = $date->format("H:i");
            $result[$key]['create_date'] = "$day $month, $time";
        }
        return $result;
    }


}