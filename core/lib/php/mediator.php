<?php

/**
 * @author balon
 * @copyright 2013
 */
session_start();
require ('autoloader.php');
$user = new User;
$array = $_POST['array'];
switch ($_SESSION['what']) {
    case 'edit_rank':
        $user->editRank($array);
        break;
    case 'add_comment':
        $user->addComment($_POST);
        break;
    case 'send_comment':
        $user->sendCommnet($array);
        break;
    case 'forum':
        $user->sendForum($array);
        break;
    case 'online':
        $user->onlineMes ($array);
        break;
}
?>