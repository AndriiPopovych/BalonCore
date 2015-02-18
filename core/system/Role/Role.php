<?php

/**
 * @author balon
 * @copyright 2014
 */



class Role {
    function getRole() {
        if ($_SESSION['admin']) {
            return true;
        }
        else return false;
    }
    public static function getPage() {
        $path_query = str_replace('//','/',$_SERVER['REQUEST_URI']);
        $path_query = str_replace(array("'","(",")",";"),'',$path_query);
        $path_query = strtolower($path_query);
        $urlParts = explode('/', $path_query);
        $urlParts = array_filter($urlParts);
        if ($urlParts[1] == $_SESSION['localhost_name']) $urlParts[1] = $urlParts[2];
        if (explode("?",urldecode($urlParts[1]))) {
            return explode("?",urldecode($urlParts[1]))[0];
        }
        else {
            return urldecode($urlParts[1]);
        }
    }


    /**
     * This function return part of URI
     * @return array
     */

    public static function getPart() {
        $path_query = str_replace('//','/',$_SERVER['REQUEST_URI']);
        $path_query = preg_replace("/\?.*/","",$path_query);
        $path_query = str_replace(array("'","(",")",";"),'',$path_query);
        $path_query = strtolower($path_query);
        $urlParts = explode('/', $path_query);
        $urlParts = array_filter($urlParts);
        foreach ($urlParts as $key => $url) {
            $urls = explode("?",$url);

            if ($urls[1]) {
                $url = $urls[0];
                $a[$key] =urldecode($url);
                break;
            }
            $a[$key] =urldecode($url);

        }
        $urlParts = array();
        $urlParts = $a;
        //следущая проверка для удобности разработки. тоесть если мы на локальном сервере разрабатываем,
        //то нам нужно удалить имя сервера, и имя папки
        if ($urlParts[1] == $_SESSION['localhost_name']) {
            if ($urlParts[1]) {
                $urlParts = array_splice($urlParts,1);
            }
        }
        else {
            if ($urlParts) {
                //$urlParts = array_splice($urlParts, 1);
            }
        }
        if (is_array($urlParts)) {
            $part = array_values($urlParts);
        }
        if (explode("?",$part[count($part)-1])[1]) {
            $part[count($part)-1] =  explode("?",$part[count($part)-1]);
        }
        return array($part);
    }

    public static function getSite() {
        $path_query = str_replace('//','/',$_SERVER['REQUEST_URI']);
        $pathuser_query = str_replace(array("'","(",")",";"),'',$path_query);
        $urlParts = explode('/', $path_query);
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            $site = "http://". $_SERVER['SERVER_NAME']."/".$urlParts[1]."/";
        }
        else {
            $site = "http://". $_SERVER['SERVER_NAME']."/";
        }
        return $site;
    }

    public static function getHref() {
        $path_query = str_replace('//','/',$_SERVER['REQUEST_URI']);
        $path_query = explode("?",$path_query)[0];
        $path_query = preg_replace("/^\//","",$path_query);
        return $path_query;
    }

}

?>