<?php

defined('int-mag') or die('Access denied');

// домен
define('PATH', 'http://rbtex.ru/');

// модель
define('MODEL', 'model/model.php');

// контроллер
define('CONTROLLER', 'controller/controller.php');

// вид
define('VIEW', 'views/');

// активный шаблон
define('TEMPLATE', VIEW.'int-mag/');

// папка с картинками контента
define('PRODUCTIMG', PATH.'userfiles/');

// сервер БД
define('HOST', 'localhost');

// пользователь
define('USER', 'suvenihi_rbtex');

// пароль
define('PASS', 'Ns123asd8!');



// БД
define('DB', 'suvenihi_rbtex');

// название магазина - title
define('TITLE', 'Интернет магазин сотовых телефонов');

// email администратора
define('ADMIN_EMAIL', 'igstrukov@gmail.com');

// количество товаров на страницу
define('PERPAGE', 9);

// папка шаблонов административной части
define('ADMIN_TEMPLATE', 'templates/');

$bd = mysql_connect(HOST, USER, PASS) or die('No connect to Server');
mysql_select_db(DB, $bd) or die('No connect to DB');
mysql_query("SET NAMES 'UTF8'") or die('Cant set charset'); 