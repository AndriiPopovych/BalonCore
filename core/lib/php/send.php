<?php 

$name = $_POST['name'];
$email = $_POST['email'];
$text = $_POST['text'];

$message = "$name
			<br> $email
			<br> <br> $text";
mail('kolka.koval@yandex.ru', 
       'sadik8.com.ua', 
       "$message", 
       "Content-type:text/html;charset=utf-8");
echo "true";
?>