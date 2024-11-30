<?php
defined('int-mag') or die('Access denied');
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/
session_start();

// подключение модели
require_once MODEL;

// подключение библиотеки функций
require_once 'functions/functions.php';

// получение массива каталога
$cat = catalog(); 

// получение массива информеров
$informers = informer();

// получени массива страниц
$pages = pages();

// получение названия новостей
$news = get_title_news();

// регистрация
if($_POST['reg']){
    registration();
    redirect();
}

// авторизация

if($_POST['auth']){
    authorization();
    if($_SESSION['auth']['user']){
        // если пользователь авторизовался
        echo "<p>Добро пожаловать, {$_SESSION['auth']['user']}</p>";
        exit;
    }else{
        // если авторизация неудачна
        echo $_SESSION['auth']['error'];
        unset($_SESSION['auth']);
        exit;
    }
}

// выход пользователя

    if($_GET['do'] == 'logout'){
    logout();
    redirect();

}

// получение динамичной части шаблона #content
$view = empty($_GET['view']) ? 'hits' : $_GET['view'];

switch($view){
    case('hits'):
        // лидеры продаж
        $eyestoppers = eyestopper('hits');
    break;
    
    case('new'):
        // новинки
        $eyestoppers = eyestopper('new');
    break;
    
    case('sale'):
        // распродажа
        $eyestoppers = eyestopper('sale');
    break;
    
    case('page'):
        // отдельная страница
        $page_id = abs((int)$_GET['page_id']);
        $get_page = get_page($page_id);
    break;

    case('news'):
        // отдельная новость
        $news_id = abs((int)$_GET['news_id']);
        $news_text = get_news_text($news_id);
    break;

    case('archive'):
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

    case('informer'):
        // текст информера
        $informer_id = abs((int)$_GET['informer_id']);
        $text_informer = get_text_informer($informer_id);
    break;
    
    case('cat'):
        // товары категории
        $category = abs((int)$_GET['category']);

                /* =====Сортировка===== */
        // массив параметров сортировки
        // ключи - то, что передаем GET-параметром
        // значения - то, что показываем пользователю и часть SQL-запроса, который передаем в модель
        $order_p = array(
            'pricea' => array('от дешевых к дорогим', 'price ASC'),
            'priced' => array('от дорогих к дешевым', 'price DESC'),
            'datea' => array('по дате добавления - к последним', 'date ASC'),
            'dated' => array('по дате добавления - с последних', 'date DESC'),
            'namea' => array('от А до Я', 'name ASC'),
            'named' => array('от Я до А', 'name DESC')
            );
            
                 $order_get = clear($_GET['order']); // получаем возможный параметр сортировки
           
    
        if(array_key_exists($order_get, $order_p)){
            $order = $order_p[$order_get][0];
            $order_db = $order_p[$order_get][1];
        }else{
        // по умолчанию сортировка по первому элементу массива order_p
            $order = $order_p['namea'][0];
            $order_db = $order_p['namea'][1];
        } 
                
            
/* =====Сортировка===== */

        // параметры для навигации
        $perpage = PERPAGE; // кол-во товаров на страницу
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
        
        $brand_name = brand_name($category); // хлебные крохи
        $products = products($category, $order_db, $start_pos, $perpage); // получаем массив из модели
    break;
    case('addtocart'):
        // добавление в корзину
        $goods_id = abs((int)$_GET['goods_id']);
        addtocart($goods_id);
        
        $_SESSION['total_sum'] = total_sum($_SESSION['cart']);
        
        // кол-во товара в корзине + защита от ввода несуществующего ID товара
        $_SESSION['total_quantity'] = 0;
        foreach($_SESSION['cart'] as $key => $value){
            if(isset($value['price'])){
                // если получена цена товара из БД - суммируем кол-во
                $_SESSION['total_quantity'] += $value['qty'];
            }else{
                // иначе - удаляем такой ID из сессиии (корзины)
                unset($_SESSION['cart'][$key]);
            }
        }
        redirect();
    break;
    case('cart'):
        /* корзина */
        // получение способов доставки
        $dostavka = get_dostavka();
        // пересчет товаров в корзине
        if(isset($_GET['id'], $_GET['qty'])){
            $goods_id = abs((int)$_GET['id']);
            $qty = abs((int)$_GET['qty']);
            
            $qty = $qty - $_SESSION['cart'][$goods_id]['qty'];
            addtocart($goods_id, $qty);
            
            $_SESSION['total_sum'] = total_sum($_SESSION['cart']); // сумма заказа
            
            total_quantity(); // кол-во товара в корзине + защита от ввода несуществующего ID товара
            redirect();
        }
      
       // удаление товара из корзины
        if(isset($_GET['delete'])){
            $id = abs((int)$_GET['delete']);
            if($id){
                delete_from_cart($id);
            }
            redirect();
        }
        
            if($_POST['order']){
            add_order();
            redirect();
        }
    break;
        case('reg'):
        // регистрация
    break;
        case('search'):
        // поиск
        $result_search = search();
        
        // параметры для навигации
        $perpage = 6; // кол-во товаров на страницу
        if(isset($_GET['page'])){
            $page = (int)$_GET['page'];
            if($page < 1) $page = 1;
        }else{
            $page = 1;
        }
        $count_rows = count($result_search); // общее кол-во товаров
        $pages_count = ceil($count_rows / $perpage); // кол-во страниц
        if(!$pages_count) $pages_count = 1; // минимум 1 страница
        if($page > $pages_count) $page = $pages_count; // если запрошенная страница больше максимума
        $start_pos = ($page - 1) * $perpage; // начальная позиция для запроса
        $endpos = $start_pos + $perpage; // до какого товара будет вывод на странице
        if($endpos > $count_rows) $endpos = $count_rows;
        
    break;
    case('filter'):
        // выбор по параметрам
        $startprice = (int)$_GET['startprice'];
        $endprice = (int)$_GET['endprice'];
        $brand = array();
        
        if($_GET['brand']){
            foreach($_GET['brand'] as $value){
                $value = (int)$value;
                $brand[$value] = $value;
            }
        }
        if($brand){
            $category = implode(',', $brand);
        }
        $products = filter($category, $startprice, $endprice);
    break;
    case('product'):
        // отдельный товар
        $goods_id = abs( (int)$_GET['goods_id'] );
        if($goods_id){
            $goods = get_goods($goods_id);
            $brand_name = brand_name($goods['goods_brandid']); // хлебные крошки
        }
    break;
    default:
        // если из адресной строки получено имя несуществующего вида
        $view = 'hits';
        $eyestoppers = eyestopper('hits');
}

/*
if(empty($_GET['view'])){
    $view = 'hits';
}else{
    $view = $_GET['view'];
}
*/

// подключени вида
require_once TEMPLATE.'index.php';

