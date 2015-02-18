<?php

/**
 * @author balon
 * @copyright 2013
 */

function __autoload($class_name)
{
    if (explode("\\",$class_name)) {
        $array = explode("\\",$class_name);
        if ($array[1]) {
            $namespace = $array[0];
            $class_name = array_pop($array);
        }
    }
    if (file_exists("core/components/" . $class_name . "/" . $class_name . ".php"))
    {
        include_once ("core/components/" . $class_name . "/" . $class_name . ".php");
    }
    if (file_exists("core/components/$class_name/$namespace/$class_name.php")) {
        include_once("core/components/$class_name/$namespace/$class_name.php");
    }
    if (file_exists("core/lib/php/" . $class_name . ".lib.php"))
    {
        include_once ("core/lib/php/" . $class_name . ".lib.php");
    }
    if (file_exists("../core/lib/php/" . $class_name . ".lib.php"))
    {
        include_once ("../core/lib/php/" . $class_name . ".lib.php");
    }
    if (file_exists("../../core/lib/php/" . $class_name . ".lib.php"))
    {
        include_once ("../../core/lib/php/" . $class_name . ".lib.php");
    }
    if (file_exists("core/system/" . $class_name . "/" . $class_name . ".php"))
    {
        include_once ("core/system/" . $class_name . "/" . $class_name . ".php");
    }
    if (file_exists("../core/system/" . $class_name . "/" . $class_name . ".php"))
    {
        include_once ("../core/system/" . $class_name . "/" . $class_name . ".php");
    }
    if (file_exists("../../core/system/" . $class_name . "/" . $class_name . ".php"))
    {
        include_once ("../../core/system/" . $class_name . "/" . $class_name . ".php");
    }
    if (file_exists("../core/components/" . $class_name . "/" . $class_name . ".php"))
    {
        include_once ("../core/components/" . $class_name . "/" . $class_name . ".php");
    }
    if (file_exists("../../core/components/" . $class_name . "/" . $class_name . ".php"))
    {
        include_once ("../../core/components/" . $class_name . "/" . $class_name . ".php");
    }
}

?>