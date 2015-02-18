<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 13.05.14
 * Time: 3:02
 */
//namespace Balon;

class Api
{

    private $cache = "";

    public $db;

    function __construct()
    {
        header('Content-type:application/json;charset=utf-8');
    }

    function __destructor()
    {
        $this->cache->close();
    }


    function get($array = [])
    {
        $db = \Balon\DBProc::instance();
        switch ($array[2]) {
            case "country":
                $country = $db->send_query("SELECT c.name,c.image,COUNT(t.id_tour) as count
                FROM `t_country` as c LEFT JOIN `t_places` as p ON c.id_country = p.id_country
                LEFT JOIN `t_tour` as t ON t.id_place = p.id_place
                GROUP BY c.id_country ORDER BY c.name LIMIT 0,1000");
                foreach ($country as $key => $val) {
                    $country[$key]["image"] = SITE . "lib/image/country/" . $val['image'];
                }
                //$country = $db->select("country",false,false,false,false,[0,500]);
                echo json_encode($country);
                break;
            case "places":
                if ($array[3]) {
                    $sql = "SELECT p.title_place,p.image,COUNT(t.id_tour) as count FROM `t_places` as p
                        LEFT JOIN `t_tour` as t ON t.id_place = p.id_place WHERE p.id_country = $array[3]
                        GROUP BY p.id_place ORDER BY p.title_place LIMIT 0,1000";
                    $places = $db->send_query($sql);
                    foreach ($places as $key => $val) {
                        $places[$key]["image"] = SITE . "lib/image/country/" . $val['image'];
                    }
                } else {
                    $sql = "SELECT p.id_place,p.title_place,p.image,COUNT(t.id_tour) as count FROM `t_places` as p
                        LEFT JOIN `t_tour` as t ON t.id_place = p.id_place
                        GROUP BY p.id_place ORDER BY p.title_place LIMIT 0,1000";
                    $places = $db->send_query($sql);
                }
                echo json_encode($places);
                break;
            case "tour":
                if ($array[3]) {
                    $sql = "SELECT t.id_tour, t.title_tour,t.type,t.time,t.length,
                        t.image, SUM(r.rating) as summa, COUNT(r.id)as count FROM `t_tour` as t
                        LEFT JOIN `t_rating` AS r ON r.id_tour = t.id_tour
                        WHERE t.id_place = $array[3] GROUP BY t.id_tour
                        ORDER BY (SUM(r.rating)/COUNT(r.id)) DESC LIMIT 0,1000";
                    $tours = $db->send_query($sql);
                    foreach ($tours as $key => $val) {
                        $val['image'] = preg_replace("/(.*)\.([a-z]+)$/", "$1_small.$2", $val['image']);
                        $tours[$key]["image"] = SITE . "lib/image/tour/" . $val['image'];
                    }
                    echo json_encode($tours);
                }
                break;
            case "concrete_tour":
                if ($array[3]) {
                    $size = 0;
                    $sql = "SELECT t.id_tour, t.title_tour,t.type,t.description, t.time,t.length,
                        t.image, t.type_price, SUM(r.rating) as summa, COUNT(r.id) as count FROM `t_tour` as t
                        LEFT JOIN `t_rating` AS r ON r.id_tour = t.id_tour
                        WHERE t.id_tour = $array[3] GROUP BY t.id_tour LIMIT 0,1000";
                    $tours = $db->send_query($sql);
                    foreach ($tours as $key => $val) {
                        $tours[$key]["image"] = SITE . "lib/image/tour/" . $val['image'];
                        $size += filesize("lib/image/tour/" . $val['image']) * 0.000001;
                    }
                    $sql = "SELECT * FROM `t_points` as p
                    LEFT JOIN `t_image` as i ON p.id_point = i.id_point
                    WHERE p.id_tour = $array[3]";
                    $array = $db->send_query($sql);
                    $size += filesize("lib/audio/" . $array[0]['audio']) * 0.000001;
                    foreach ($array as $key => $val) {
                        $size += filesize("lib/image/image/" . $val['image']) * 0.000001;
                    }
                    $tours[0]['size'] = $size;
                    echo json_encode($tours[0]);

                }
                break;
            case "point":
                $sql = "SELECT p.*,i.image FROM `t_points` as p
                    LEFT JOIN `t_image` as i ON p.id_point = i.id_point
                    WHERE p.id_point = $array[3]";
                $steps = $db->send_query($sql);
                foreach ($steps as $key => $val) {
                    $steps[$key]['audio'] = SITE . "lib/audio/" . $val['audio'];
                    $image[] = SITE . "lib/image/image/" . $val['image'];
                }
                $steps = $steps[0];
                $steps['image'] = $image;
                echo json_encode($steps);
                break;
            case "next_point":
                $array[3]++;
                $sql = "SELECT * FROM `t_points` as p
                    LEFT JOIN `t_image` as i ON p.id_point = i.id_point
                    WHERE p.id_point = $array[3]";
                $steps = $db->send_query($sql);
                foreach ($steps as $key => $val) {
                    $steps[$key]['audio'] = SITE . "lib/audio/" . $val['audio'];
                    $image[] = SITE . "lib/image/image/" . $val['image'];
                }
                $steps = $steps[0];
                $steps['image'] = $image;
                echo json_encode($steps);
                break;
            case "prev_point":
                $array[3]--;
                $sql = "SELECT * FROM `t_points` as p
                    LEFT JOIN `t_image` as i ON p.id_point = i.id_point
                    WHERE p.id_point = $array[3]";
                $steps = $db->send_query($sql);
                foreach ($steps as $key => $val) {
                    $steps[$key]['audio'] = SITE . "lib/audio/" . $val['audio'];
                    $image[] = SITE . "lib/image/image/" . $val['image'];
                }
                $steps = $steps[0];
                $steps['image'] = $image;
                echo json_encode($steps);
                break;
            case "all_points":
                $points = $db->send_query("SELECT id_point,lan,lat FROM `t_points`
                    WHERE `id_tour` = $array[3]");
                echo json_encode($points);
                break;
            case "user":
                $login = $_GET['login'];
                $pass = md5($_GET['pass']);
                if ($user = $db->select("users", false, ["login" => $login, "pass" => $pass])) {
                    $result['id'] = $user[0]['id'];
                    $result['login'] = $user[0]['login'];
                }
                echo json_encode($result);
                break;
            case "download":
                $tour = $db->select("tour", false, ['id_tour' => $array[3]])[0];
                $points = $db->select("points", false, ['id_tour' => $array[3]]);
                $sql = "";
                $or = "";
                $sql = "SELECT * FROM `t_image` WHERE ";
                if ($points) {
                    foreach ($points as $point) {
                        $sql .= "$or `id_point` = " . $point['id_point'] . " ";
                        $or = " OR ";
                    }
                    $image = $db->send_query($sql);
                    foreach ($image as $key => $val) {
                        $images[$val['id_point']][] = SITE . "lib/image/image/" . $val['image'];
                    }
                    foreach ($points as $key => $point) {
                        $tour['points'][$key]['id'] = $point['id_point'];
                        $tour['points'][$key]['name'] = $point['name'];
                        $tour['points'][$key]['description'] = $point['description'];
                        $tour['points'][$key]['audio'] = SITE . "lib/audio/" . $point['audio'];
                        $tour['points'][$key]['lan'] = $point['lan'];
                        $tour['points'][$key]['lat'] = $point['lat'];
                        $tour['points'][$key]['image'] = $images[$point['id_point']];
                    }
                }
                echo json_encode($tour);
                break;
            case "like":
                if ($db->select("rating",false,["id_user" => $_GET['id_user'],"id_tour" => $_GET['id_tour']])) {
                    echo json_encode(true);
                }
                else {
                    echo json_encode(false);
                }
                break;
        }
    }

    function post($array = [])
    {
        $db = \Balon\DBProc::instance();
        switch ($array[2]) {
            case "user":
                if (!$db->select("users", false, ["login" => $_GET['login']])) {
                    if ($id = $db->insert("users", ["login" => $_GET['login'], "pass" => md5($_GET['pass'])], true)) {
                        echo json_encode($id);
                    }
                    return;
                } else {
                    echo json_encode(false);
                    return;
                }
                break;
            case "like":
                if (!$db->select("rating", false,
                    ["id_user" => $_GET['id_user'], "id_tour" => $_GET['id_tour']])
                ) {
                    if (!$db->insert("rating",
                        ['id_user' => $_GET['id_user'], "id_tour" => $_GET['id_tour'], "rating" => $_GET['rating']])
                    ) {
                        echo json_encode(true);
                    }
                } else {
                    echo json_encode(false);
                }
                break;
        }
    }


}