<?php

/**
 * @author balon
 * @copyright 2013
 */


    namespace Balon;

    session_start();
    require ('lib/php/autoloader.php');
    $admin = Admin::instance();
    $array = $_POST;
    if ($_POST['post']) {
        foreach ($_POST['post'] as $key => $value) {
            $array[$value['name']] = $value['value'];
        }
        unset($array['post']);
    }
    $_POST = $_POST['array'];
    if ($_SESSION['what']) {
        $what = $_SESSION['what'];
    }
    elseif ($_POST['what']) {
        $what = $_POST['what'];
    }
    elseif ($array['what']) {
        $what = $array['what'];
    }

    if (!$array['returnedHref'] && $what == "edit"){
        $what = "get_edit_html";
    }


    //!ATANTION

    //$_SESSION['what'] = false;

    // !ATENTION

    switch ($what) {
        case 'remove':
            $admin->remove($_POST);
            break;
        case 'add':
            $admin->add($_POST);
            break;
        case 'get_edit_html':
            $admin->getEditHtmlNew ($_POST);
            $_SESSION['what'] = "edit";
            exit();
            break;
        case 'edit':
            $admin->edit ($array,$_FILES);
            break;
        case 'edit_visibility':
            $admin->edit_visibility($_POST);
            break;
        case 'add_article':
            $admin->add_article($array);
            break;
        case 'position':
            $admin->position($array);
            break;
    }




    ?>