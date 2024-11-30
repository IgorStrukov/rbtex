<?php defined('int-mag') or die('Access denied');

/* ===Фильтрация входящих данных из админки=== */
function clear_admin($var){
    $var = mysql_real_escape_string($var);
    return $var;
}
/* ===Фильтрация входящих данных из админки=== */


/* ====Каталог - получение массива=== */
function catalog(){
    $query = "SELECT * FROM brands ORDER BY parent_id, brand_name";
    $res = mysql_query($query) or die(mysql_query());
    
    //массив категорий
    $cat = array();
    while($row = mysql_fetch_assoc($res)){
        if(!$row['parent_id']){
            $cat[$row['brand_id']][] = $row['brand_name'];
        }else{
            $cat[$row['parent_id']]['sub'][$row['brand_id']] = $row['brand_name'];
        }
    }
    return $cat;
}
/* ====Каталог - получение массива=== */

/* ===Страницы=== */
function pages(){
    $query = "SELECT page_id, title, position FROM pages ORDER BY position";
    $res = mysql_query($query);
    
    $pages = array();
    while($row = mysql_fetch_assoc($res)){
        $pages[] = $row;
    }
    return $pages;
}
/* ===Страницы=== */

/* ===Отдельная страница=== */
function get_page($page_id){
    $query = "SELECT * FROM pages WHERE page_id = $page_id";
    $res = mysql_query($query);
    
    $page = array();
    $page = mysql_fetch_assoc($res);
    
    return $page;
}
/* ===Отдельная страница=== */

/* ===Редактирование страницы=== */
function edit_page($page_id){
    $title = trim($_POST['title']);
    $keywords = trim($_POST['keywords']);
    $description = trim($_POST['description']);
    $position = (int)$_POST['position'];
    $text = trim($_POST['text']);
    
    if(empty($title)){
        // если нет названия
        $_SESSION['edit_page']['res'] = "<div class='error'>Должно быть название страницы!</div>";
        return false;
    }else{
        $title = clear_admin($title);
        $keywords = clear_admin($keywords);
        $description = clear_admin($description);
        $text = clear_admin($text);
        
        $query = "UPDATE pages SET
                    title = '$title',
                    keywords = '$keywords',
                    description = '$description',
                    position = $position,
                    text = '$text'
                        WHERE page_id = $page_id";
        $res = mysql_query($query) or die(mysql_error());
        
        if(mysql_affected_rows() > 0){
            $_SESSION['answer'] = "<div class='success'>Страница обновлена!</div>";
            return true;
        }else{
            $_SESSION['edit_page']['res'] = "<div class='error'>Ошибка или Вы ничего не меняли!</div>";
            return false;
        }
    }
}
/* ===Редактирование страницы=== */

/* ===Добавление страницы=== */
function add_page(){
    $title = trim($_POST['title']);
    $keywords = trim($_POST['keywords']);
    $description = trim($_POST['description']);
    $position = (int)$_POST['position'];
    $text = trim($_POST['text']);
    
    if(empty($title)){
        // если нет названия
        $_SESSION['add_page']['res'] = "<div class='error'>Должно быть название страницы!</div>";
        $_SESSION['add_page']['keywords'] = $keywords;
        $_SESSION['add_page']['description'] = $description;
        $_SESSION['add_page']['position'] = $position;
        $_SESSION['add_page']['text'] = $text;
        return false;
    }else{
        $title = clear_admin($title);
        $keywords = clear_admin($keywords);
        $description = clear_admin($description);
        $text = clear_admin($text);
        
        $query = "INSERT INTO pages (title, keywords, description, position, text)
                    VALUES ('$title', '$keywords', '$description', $position, '$text')";
        $res = mysql_query($query);
        
        if(mysql_affected_rows() > 0){
            $_SESSION['answer'] = "<div class='success'>Страница добавлена!</div>";
            return true;
        }else{
            $_SESSION['add_page']['res'] = "<div class='error'>Ошибка при добавлении страницы!</div>";
            return false;
        }
    }
}
/* ===Добавление страницы=== */

/* ===Удаление страницы=== */
function del_page($page_id){
    $query = "DELETE FROM pages WHERE page_id = $page_id";
    $res = mysql_query($query);
    
    if(mysql_affected_rows() > 0){
        $_SESSION['answer'] = "<div class='success'>Страница удалена.</div>";
        return true;
    }else{
        $_SESSION['answer'] = "<div class='error'>Ошибка удаления страницы!</div>";
        return false;
    }
}
/* ===Удаление страницы=== */

/* ===Количество новостей=== */
function count_news(){
    $query = "SELECT COUNT(news_id) FROM news";
    $res = mysql_query($query);
    
    $count_news = mysql_fetch_row($res);
    return $count_news[0];
}
/* ===Количество новостей=== */

/* ===Архив новостей=== */
function get_all_news($start_pos, $perpage){
    $query = "SELECT news_id, title, anons, date FROM news ORDER BY date DESC LIMIT $start_pos, $perpage";
    $res = mysql_query($query);
    
    $all_news = array();
    while($row = mysql_fetch_assoc($res)){
        $all_news[] = $row;
    }
    return $all_news;
}
/* ===Архив новостей=== */

/* ===Добавление новости=== */
function add_news(){
    $title = trim($_POST['title']);
    $keywords = trim($_POST['keywords']);
    $description = trim($_POST['description']);
    $anons = trim($_POST['anons']);
    $text = trim($_POST['text']);
    
    if(empty($title)){
        // если нет названия
        $_SESSION['add_news']['res'] = "<div class='error'>Должно быть название новости!</div>";
        $_SESSION['add_news']['keywords'] = $keywords;
        $_SESSION['add_news']['description'] = $description;
        $_SESSION['add_news']['anons'] = $anons;
        $_SESSION['add_news']['text'] = $text;
        return false;
    }else{
        $title = clear_admin($title);
        $keywords = clear_admin($keywords);
        $description = clear_admin($description);
        $anons = clear_admin($anons);
        $text = clear_admin($text);
        $date = date("Y-m-d");
        
        $query = "INSERT INTO news (title, keywords, description, date, anons, text)
                    VALUES ('$title', '$keywords', '$description', '$date', '$anons', '$text')";
        $res = mysql_query($query);
        
        if(mysql_affected_rows() > 0){
            $_SESSION['answer'] = "<div class='success'>Новость добавлена!</div>";
            return true;
        }else{
            $_SESSION['add_news']['res'] = "<div class='error'>Ошибка при добавлении новости!</div>";
            return false;
        }
    }
}
/* ===Добавление новости=== */

/* ===Отдельная новость=== */
function get_news($news_id){
    $query = "SELECT * FROM news WHERE news_id = $news_id";
    $res = mysql_query($query);
    
    $news = array();
    $news = mysql_fetch_assoc($res);
    
    return $news;
}
/* ===Отдельная новость=== */

/* ===Редактирование новости=== */
function edit_news($news_id){
    $title = trim($_POST['title']);
    $keywords = trim($_POST['keywords']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $anons = trim($_POST['anons']);
    $text = trim($_POST['text']);
    
    if(empty($title)){
        // если нет названия
        $_SESSION['edit_news']['res'] = "<div class='error'>Должно быть название новости!</div>";
        return false;
    }else{
        $title = clear_admin($title);
        $keywords = clear_admin($keywords);
        $description = clear_admin($description);
        $date = clear_admin($date);
        $anons = clear_admin($anons);
        $text = clear_admin($text);
        
        $query = "UPDATE news SET
                    title = '$title',
                    keywords = '$keywords',
                    description = '$description',
                    date = '$date',
                    anons = '$anons',
                    text = '$text'
                        WHERE news_id = $news_id";
        $res = mysql_query($query) or die(mysql_error());
        
        if(mysql_affected_rows() > 0){
            $_SESSION['answer'] = "<div class='success'>Новость обновлена!</div>";
            return true;
        }else{
            $_SESSION['edit_news']['res'] = "<div class='error'>Ошибка или Вы ничего не меняли!</div>";
            return false;
        }
    }
}
/* ===Редактирование новости=== */

/* ===Удаление новости=== */
function del_news($news_id){
    $query = "DELETE FROM news WHERE news_id = $news_id";
    $res = mysql_query($query);
    
    if(mysql_affected_rows() > 0){
        $_SESSION['answer'] = "<div class='success'>Новость удалена.</div>";
        return true;
    }else{
        $_SESSION['answer'] = "<div class='error'>Ошибка удаления новости!</div>";
        return false;
    }
}
/* ===Удаление новости=== */

/* ===Информеры - получение массива=== */
function informer(){
    $query = "SELECT * FROM links
                RIGHT JOIN informers ON
                    links.parent_informer = informers.informer_id
                        ORDER BY informer_position, links_position";
    $res = mysql_query($query) or die(mysql_query());
    
    $informers = array();
    $name = ''; // флаг имени информера
    while($row = mysql_fetch_assoc($res)){
        if($row['informer_name'] != $name){ // если такого информера в массиве еще нет
            $informers[$row['informer_id']][] = $row['informer_name']; // добавляем информер в массив
            $informers[$row['informer_id']]['position'] = $row['informer_position'];
            $informers[$row['informer_id']]['informer_id'] = $row['informer_id'];
            $name = $row['informer_name'];
        }
        if($informers[$row['parent_informer']])
        $informers[$row['parent_informer']]['sub'][$row['link_id']] = $row['link_name']; // заносим страницы в информер
    }
    return $informers;
}
/* ===Информеры - получение массива=== */
/* ===Массив информеров для списка=== */
function get_informers(){
    $query = "SELECT * FROM informers";
    $res = mysql_query($query);
    
    $informers = array();
    while($row = mysql_fetch_assoc($res)){
        $informers[] = $row; 
    }
    
    return $informers;
}
/* ===Массив информеров для списка=== */

/* ===Добавление страницы информера=== */
function add_link(){
    $link_name = trim($_POST['link_name']);
    $keywords = trim($_POST['keywords']);
    $description = trim($_POST['description']);
    $parent_informer = (int)$_POST['parent_informer'];
    $links_position = (int)$_POST['links_position'];
    $text = trim($_POST['text']);
    
    if(empty($link_name)){
        // если нет названия
        $_SESSION['add_link']['res'] = "<div class='error'>Должно быть название страницы!</div>";
        $_SESSION['add_link']['keywords'] = $keywords;
        $_SESSION['add_link']['description'] = $description;
        $_SESSION['add_link']['links_position'] = $links_position;
        $_SESSION['add_link']['text'] = $text;
        return false;
    }else{
        $link_name = clear_admin($link_name);
        $keywords = clear_admin($keywords);
        $description = clear_admin($description);
        $text = clear_admin($text);
        
        $query = "INSERT INTO links (link_name, keywords, description, parent_informer, links_position, text)
                    VALUES ('$link_name', '$keywords', '$description', $parent_informer, $links_position, '$text')";
        $res = mysql_query($query) or die(mysql_error());
        if(mysql_affected_rows() > 0){
            $_SESSION['answer'] = "<div class='success'>Страница информера добавлена!</div>";
            return true;
        }else{
            $_SESSION['add_link']['res'] = "<div class='error'>Ошибка при добавлении страницы!</div>";
            return false;
        }            
    }
}
/* ===Добавление страницы информера=== */
/* ===Получение данных страницы информера=== */
function get_link($link_id){
    $query = "SELECT * FROM links WHERE link_id = $link_id";
    $res = mysql_query($query);
    
    $link = array();
    $link = mysql_fetch_assoc($res);
    return $link;
}
/* ===Получение данных страницы информера=== */

/* ===Редактирование страницы информера=== */
function edit_link($link_id){
    $link_name = trim($_POST['link_name']);
    $keywords = trim($_POST['keywords']);
    $description = trim($_POST['description']);
    $parent_informer = (int)$_POST['parent_informer'];
    $links_position = (int)$_POST['links_position'];
    $text = trim($_POST['text']);
    
    if(empty($link_name)){
        // если нет названия
        $_SESSION['edit_link']['res'] = "<div class='error'>Должно быть название страницы!</div>";
        return false;
    }else{
        $link_name = clear_admin($link_name);
        $keywords = clear_admin($keywords);
        $description = clear_admin($description);
        $text = clear_admin($text);
        
        $query = "UPDATE links SET
                    link_name = '$link_name',
                    keywords = '$keywords',
                    description = '$description',
                    parent_informer = $parent_informer,
                    links_position = $links_position,
                    text = '$text'
                        WHERE link_id = $link_id";
        $res = mysql_query($query) or die(mysql_error());
        if(mysql_affected_rows() > 0){
            $_SESSION['answer'] = "<div class='success'>Страница информера обновлена!</div>";
            return true;
        }else{
            $_SESSION['edit_link']['res'] = "<div class='error'>Ошибка при редактировании страницы!</div>";
            return false;
        }
    }
}
/* ===Редактирование страницы информера=== */

/* ===Удаление страницы информера=== */
function del_link($link_id){
    $query = "DELETE FROM links WHERE link_id = $link_id";
    $res = mysql_query($query);
    if(mysql_affected_rows() > 0){
        $_SESSION['answer'] = "<div class='success'>Страница информера удалена!</div>";
    }else{
        $_SESSION['answer'] = "<div class='error'>Ошибка!</div>";
    }
}
/* ===Удаление страницы информера=== */

/* ===Добавление информера=== */
function add_informer(){
    $informer_name = clear_admin(trim($_POST['informer_name']));
    $informer_position = (int)$_POST['informer_position'];
    
    if(empty($informer_name)){
        $_SESSION['add_informer']['res'] = "<div class='error'>У информера должно быть название</div>";
        return false;
    }else{
        $query = "INSERT INTO informers (informer_name, informer_position)
                    VALUES ('$informer_name', $informer_position)";
        $res = mysql_query($query);
        if(mysql_affected_rows() > 0){
            $_SESSION['answer'] = "<div class='success'>Информер добавлен!</div>";
            return true;
        }else{
            $_SESSION['add_informer']['res'] = "<div class='error'>Ошибка добавления информера!</div>";
            return false;
        }
    }
}
/* ===Добавление информера=== */

/* ===Удаление информера=== */
function del_informer($informer_id){
    // удаляем страницы информера
    mysql_query("DELETE FROM links WHERE parent_informer = $informer_id");
    
    // удаляем информер
    $query = "DELETE FROM informers WHERE informer_id = $informer_id";
    $res = mysql_query($query);
    if(mysql_affected_rows() > 0){
        $_SESSION['answer'] = "<div class='success'>Информер удален!</div>";
    }else{
        $_SESSION['answer'] = "<div class='error'>Ошибка удаления информера или статей информера!</div>";
    } 
}
/* ===Удаление информера=== */

/* ===Получение данных информера=== */
function get_informer($informer_id){
    $query = "SELECT * FROM informers WHERE informer_id = $informer_id";
    $res = mysql_query($query);
    
    $informers = array();
    $informers = mysql_fetch_assoc($res);
    return $informers;
}
/* ===Получение данных информера=== */

/* ===Редактирование информера=== */
function edit_informer($informer_id){
    $informer_name = clear_admin(trim($_POST['informer_name']));
    $informer_position = (int)$_POST['informer_position'];
    
    if(empty($informer_name)){
        $_SESSION['edit_informer']['res'] = "<div class='error'>У информера должно быть название</div>";
        return false;
    }else{
        $query = "UPDATE informers SET
                    informer_name = '$informer_name',
                    informer_position = $informer_position
                        WHERE informer_id = $informer_id";
        $res = mysql_query($query);
        if(mysql_affected_rows() > 0){
            $_SESSION['answer'] = "<div class='success'>Информер обновлен!</div>";
            return true;
        }else{
            $_SESSION['edit_informer']['res'] = "<div class='error'>Ошибка обновления информера!</div>";
            return false;
        }
    }
}
/* ===Редактирование информера=== */

/* ===Добавление категории=== */
function add_brand(){
    $brand_name = clear_admin(trim($_POST['brand_name']));
    $parent_id = (int)$_POST['parent_id'];
    
    if(empty($brand_name)){
        $_SESSION['add_brand']['res'] = "<div class='error'>Вы не указали название категории</div>";
        return false;
    }else{
        // проверяем нет ли такой категории на одном уровне
        $query = "SELECT brand_id FROM brands WHERE brand_name = '$brand_name' AND parent_id = $parent_id";
        $res = mysql_query($query);
        if(mysql_num_rows($res) > 0){
            $_SESSION['add_brand']['res'] = "<div class='error'>Категория с таким названием уже есть</div>";
            return false;
        }else{
            $query = "INSERT INTO brands (brand_name, parent_id)
                        VALUES ('$brand_name', $parent_id)";
            $res = mysql_query($query);
            if(mysql_affected_rows() > 0){
                $_SESSION['answer'] = "<div class='success'>Категория добавлена!</div>";
                return true;
            }else{
                $_SESSION['add_brand']['res'] = "<div class='error'>Ошибка при добавлении категории!</div>";
                return false;
            }                        
        }
    }
}
/* ===Добавление категории=== */

/* ===Редактирования бренда=== */
function edit_brand($brand_id){
    $brand_name = clear_admin(trim($_POST['brand_name']));
    $parent_id = (int)$_POST['parent_id'];
    
    if(empty($brand_name)){
        $_SESSION['edit_brand']['res'] = "<div class='error'>Вы не указали название категории</div>";
        return false;
    }else{
        // проверяем нет ли такой категории
        $query = "SELECT brand_id FROM brands WHERE brand_name = '$brand_name' AND parent_id = $parent_id";
        $res = mysql_query($query);
        if(mysql_num_rows($res) > 0){
            $_SESSION['edit_brand']['res'] = "<div class='error'>Категория с таким названием уже есть</div>";
            return false;
        }else{
            $query = "UPDATE brands SET
                        brand_name = '$brand_name',
                        parent_id = $parent_id
                            WHERE brand_id = $brand_id";
            $res = mysql_query($query);
            if(mysql_affected_rows() > 0){
                $_SESSION['answer'] = "<div class='success'>Категория обновлена!</div>";
                return true;
            }else{
                $_SESSION['edit_brand']['res'] = "<div class='error'>Ошибка при редактировании категории!</div>";
                return false;
            }
        }
    }
}
/* ===Редактирования бренда=== */

/* ===Удаление категории=== */
function del_brand($brand_id){
    $query = "SELECT COUNT(*) FROM brands WHERE parent_id = $brand_id";
    $res = mysql_query($query);
    $row = mysql_fetch_row($res);
    if($row[0]){
        $_SESSION['answer'] = "<div class='error'>Категория имеет подкатегории! Удалите сначала их или переместите в другую категорию.</div>";
    }else{
        mysql_query("DELETE FROM goods WHERE goods_brandid = $brand_id");
        mysql_query("DELETE FROM brands WHERE brand_id = $brand_id");
        $_SESSION['answer'] = "<div class='error'>Категория удалена.</div>";
    }
}
/* ===Удаление категории=== */

/* ===Получение кол-ва товаров для навигации=== */
function count_rows($category){
    $query = "(SELECT COUNT(goods_id) as count_rows
                 FROM goods
                     WHERE goods_brandid = $category)
               UNION      
               (SELECT COUNT(goods_id) as count_rows
                 FROM goods 
                     WHERE goods_brandid IN 
                (
                    SELECT brand_id FROM brands WHERE parent_id = $category
                ))";
    $res = mysql_query($query) or die(mysql_error());
    
    while($row = mysql_fetch_assoc($res)){
        if($row['count_rows']) $count_rows = $row['count_rows'];
    }
    return $count_rows;
}
/* ===Получение кол-ва товаров для навигации=== */

/* ===Получение названий для хлебных крох=== */
function brand_name($category){
    $query = "(SELECT brand_id, brand_name FROM brands
                WHERE brand_id = 
                    (SELECT parent_id FROM brands WHERE brand_id = $category)
                )
                UNION
                    (SELECT brand_id, brand_name FROM brands WHERE brand_id = $category)";
    $res = mysql_query($query);
    $brand_name = array();
    while($row = mysql_fetch_assoc($res)){
        $brand_name[] = $row;
    }
    return $brand_name;
}
/* ===Получение названий для хлебных крох=== */

/* ===Получение массива товаров по категории=== */
function products($category, $start_pos, $perpage){
    $query = "(SELECT goods_id, name, img, anons, price, hits, new, sale, date
                 FROM goods
                     WHERE goods_brandid = $category)
               UNION      
               (SELECT goods_id, name, img, anons, price, hits, new, sale, date
                 FROM goods 
                     WHERE goods_brandid IN 
                (
                    SELECT brand_id FROM brands WHERE parent_id = $category
                )
               ) LIMIT $start_pos, $perpage";
    $res = mysql_query($query) or die(mysql_error());
    
    $products = array();
    while($row = mysql_fetch_assoc($res)){
        $products[] = $row;
    }
    
    return $products;
}
/* ===Получение массива товаров по категории=== */


