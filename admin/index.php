<?php

// запрет прямого обращения
define('int-mag', TRUE);
/* ini_set('display_errors', 1);
error_reporting(E_ALL);*/
session_start();

// подключение файла конфигурации
require_once '../config.php';

// подключение файла функций пользовательской части
require_once '../functions/functions.php';

// подключение файла функций административной части
require_once 'functions/functions.php';

// получение массива каталога
$cat = catalog(); 

// получение динамичной части шаблона #content
$view = empty($_GET['view']) ? 'pages' : $_GET['view'];

switch($view){
    case('pages'):
        // страницы
        $pages = pages(); 
    break;

    case('informers'):
        // информеры
        $informers = informer();
    break;

    case('edit_page'):
        // редактирование страницы
        $page_id = (int)$_GET['page_id'];
        $get_page = get_page($page_id);
        
        if($_POST){
            if(edit_page($page_id)) redirect('?view=pages');
                else redirect();
        }
    break;
    case('add_page'):
        if($_POST){
            if(add_page()) redirect('?view=pages');
                else redirect();
        }
    break;
    case('del_page'):
        $page_id = (int)$_GET['page_id'];
        del_page($page_id);
        redirect();
    break;
        case('news'):
        // Новости
       // все новости (архив новостей)
        // параметры для навигации
        $perpage = 2; // кол-во товаров на страницу
        if(isset($_GET['page'])){
            $page = (int)$_GET['page'];
            if($page < 1) $page = 1;
        }else{
            $page = 1;
        }
        $count_rows = count_news(); // общее кол-во новостей
        $pages_count = ceil($count_rows / $perpage); // кол-во страниц
        if(!$pages_count) $pages_count = 1; // минимум 1 страница
        if($page > $pages_count) $page = $pages_count; // если запрошенная страница больше максимума
        $start_pos = ($page - 1) * $perpage; // начальная позиция для запроса
        
        $all_news = get_all_news($start_pos, $perpage);
    break;
    case('add_news'):
           if($_POST){
            if(add_news()) redirect('?view=news');
                else redirect();
        }
    break;
    case('edit_news'):
        $news_id = (int)$_GET['news_id'];
        $get_news = get_news($news_id);
        if($_POST){
            if(edit_news($news_id)) redirect('?view=news');
                else redirect();
        }
    break;
    case('del_news'):
        $news_id = (int)$_GET['news_id'];
        del_news($news_id);
        redirect();
    break;
     case('add_link'):
        $informer_id = (int)$_GET['informer_id'];
        $informers = get_informers(); // список информеров
        if($_POST){
            if(add_link()) redirect('?view=informers');
                else redirect();
        }
    break;
    case('edit_link'):
        $link_id = (int)$_GET['link_id'];
        $informers = get_informers(); // список информеров
        $get_link = get_link($link_id);
        if($_POST){
            if(edit_link($link_id)) redirect('?view=informers');
                else redirect();
        }
    break;
    
    case('del_link'):
        $link_id = (int)$_GET['link_id'];
        del_link($link_id);
        redirect();
    break;
    case('add_informer'):
        if($_POST){
            if(add_informer()) redirect('?view=informers');
                else redirect();
        }
    break;
    
    case('del_informer'):
        $informer_id = (int)$_GET['informer_id'];
        del_informer($informer_id);
        redirect();
    break;
    
        case('edit_informer'):
        $informer_id = (int)$_GET['informer_id'];
        $get_informer = get_informer($informer_id);
        if($_POST){
            if(edit_informer($informer_id)) redirect('?view=informers');
                else redirect();
        }
    break;
    case('brands'):
    break;
    case('add_brand'):
        if($_POST){
            if(add_brand()) redirect('?view=brands');
                else redirect();
        }
    break;
    case('edit_brand'):
        $brand_id = (int)$_GET['brand_id'];
        $cat_name = $cat[$brand_id][0];
        if($_POST){
            if(edit_brand($brand_id)) redirect('?view=brands');
                else redirect();
        }
    break;
    
    case('del_brand'):
        $brand_id = (int)$_GET['brand_id'];
        del_brand($brand_id);
        redirect();
    break;
    case('cat'):
        $category = (int)$_GET['category'];
        
        /*pagination*/
        $perpage = 6;
        if(isset($_GET['page'])){
            $page = (int)$_GET['page'];
            if($page < 1) $page = 1;
        }else{
            $page = 1;
        }
        $count_rows = count_rows($category); // общее кол-во товаров
        $pages_count = ceil($count_rows / $perpage); // кол-во страниц
        if(!$pages_count) $pages_count = 1; // минимум 1 страница
        if($page > $pages_count) $page = $pages_count; // если запрошенная страница больше максимума
        $start_pos = ($page - 1) * $perpage; // начальная позиция для запроса
        /*pagination*/
        
        $brand_name = brand_name($category); // хлебные крохи
        $products = products($category, $start_pos, $perpage); // получаем массив из модели
    break;
    default:
    // если из адресной строки получено имя несуществующего вида
    $view = 'pages'; 
    $pages = pages(); 
}

// HEADER
include ADMIN_TEMPLATE.'header.php';

// LEFTBAR
include ADMIN_TEMPLATE.'leftbar.php';

// CONTENT
include ADMIN_TEMPLATE.$view.'.php';

