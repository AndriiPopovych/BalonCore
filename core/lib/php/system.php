<?php 
session_start();
if ($_POST['what']) {
    $_SESSION['what'] = $_POST['what'];
}
if ($_POST['id']) {
    $_SESSION['id'] = $_POST['id'];
}

?>