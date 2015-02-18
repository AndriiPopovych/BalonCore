<?php
/**
 * Created by PhpStorm.
 * User: Андрій
 * Date: 03.01.2015
 * Time: 0:10
 */

namespace Balon\System;


class Balon {
    function __construct()
    {
        $this->user = "Balon";
        // TODO: Implement __construct() method.
    }

    public function newMVCComponent() {
        if (User::trueDev()) {
            if ($_GET['name']) {
                $name = $_GET['name'];
                if (!is_dir("core/components/$name")) {
                    mkdir("core/components/$name");
                    if (!$_GET['components']) {
                        $this->createController($name);
                        $this->createModel($name);
                        $this->createView($name);
                    }
                    else {
                        $components = explode(",",$_GET['components']);
                        if (is_array($components)) {
                            foreach ($components as $comp) {
                                $methodName = "create".$comp;
                                $this->$methodName($name);
                            }
                        }
                        else {
                            $methodName = "create".$components;
                            $this->$methodName($name);
                        }
                    }
                }
            }
        }
    }

    private function createController($name) {
        $file = file_get_contents("core/system/Balon/template/Controller.tpl");
        $user = $this->user;
        $date = date("d.m.Y");
        $time = date("H:i");
        $file = preg_replace("/{{name}}/",$name,$file);
        $file = preg_replace("/{{user}}/",$user,$file);
        $file = preg_replace("/{{date}}/",$date,$file);
        $file = preg_replace("/{{time}}/",$time,$file);
        mkdir("core/components/$name/Controller");
        file_put_contents("core/components/$name/Controller/$name.php",$file);
    }

    private function createModel($name) {
        $file = file_get_contents("core/system/Balon/template/Model.tpl");
        $user = $this->user;
        $date = date("d.m.Y");
        $time = date("H:i");
        $file = preg_replace("/{{name}}/",$name,$file);
        $file = preg_replace("/{{user}}/",$user,$file);
        $file = preg_replace("/{{date}}/",$date,$file);
        $file = preg_replace("/{{time}}/",$time,$file);
        mkdir("core/components/$name/Model");
        file_put_contents("core/components/$name/Model/$name.php",$file);
    }

    private function createView($name) {
        $file = file_get_contents("core/system/Balon/template/View.tpl");
        $user = $this->user;
        $date = date("d.m.Y");
        $time = date("H:i");
        $file = preg_replace("/{{name}}/",$name,$file);
        $file = preg_replace("/{{user}}/",$user,$file);
        $file = preg_replace("/{{date}}/",$date,$file);
        $file = preg_replace("/{{time}}/",$time,$file);
        mkdir("core/components/$name/View");
        file_put_contents("core/components/$name/View/$name.php",$file);
    }
} 