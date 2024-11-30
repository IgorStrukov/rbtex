<?php defined('int-mag') or die('Access denied'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(94742016, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        ecommerce:"dataLayer"
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/94742016" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?=TEMPLATE?>css/style.css" />
<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<script type="text/javascript" src="<?=TEMPLATE?>js/functions.js"></script>
<script type="text/javascript" src="<?=TEMPLATE?>js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?=TEMPLATE?>js/jquery-ui-1.8.22.custom.min.js"></script>
<script type="text/javascript" src="<?=TEMPLATE?>js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?=TEMPLATE?>js/workscripts.js"></script>
<script type="text/javascript"> var query = '<?=$_SERVER['QUERY_STRING']?>';</script>
<title>RBTEX</title>
</head>

<body>
<div class="main">
	<div class="header">
		<a href="<?=PATH?>"><img class="logo" src="<?=TEMPLATE?>images/logo.jpg" alt="Интернет магазин сотовых телефонов" /></a>
		<img class="slogan" src="<?=TEMPLATE?>images/slogan.jpg" alt="Интернет-магазин Сотовых телефонов" />
		<div class="head-contact">
		<p><strong>Телефон:</strong><br />
		<span>8 (800) 700-00-01</span></p>
		<p><strong>Режим работы:</strong><br />
		Будние дни: с 9:00 до 18:00<br />  
		Суббота, Воскресенье - выходные</p>  
		</div>
		<form method="get" action="">
		<ul class="search-head">
		    <input type="hidden" name="view" value="search" />
			<li><input type="text" name="search" id="quickquery" placeholder="Что вы хотите купить?" /></li>
				<script type="text/javascript">
					 //<![CDATA[
					  placeholderSetup('quickquery');
					  //]]>
				</script>
			<li><input class="search-btn" type="image" src="<?=TEMPLATE?>images/searc-btn.jpg" /></li>
		</ul>
		</form>	
	</div>
	<ul class="menu">
		<li><a href="<?=PATH?>">Главная</a></li>
        <?php if($pages): ?>
            <?php foreach($pages as $item): ?>
                <li><a href="?view=page&amp;page_id=<?=$item['page_id']?>"><?=$item['title']?></a></li>
            <?php endforeach; ?>
        <?php endif; ?>
	</ul>