<?php defined('int-mag') or die('Access denied'); ?>
	<div class="content-main">
		<div class="leftBar">
			<ul class="nav-left">
				<li><a href="<?=PATH?>admin" class="nav-activ">Основные страницы</a></li>
				<li><a href="?view=informers">Информеры</a></li>
				<li><a href="?view=brands">Основные категории</a></li>
				<li><a href="#">Каталог</a></li>
					<ul class="nav-catalog" id="accordion">

					<?php foreach($cat as $key => $item): ?>
							<?php if(count($item) > 1): // если это родительская категория ?>
								<li class="header_li"><a href="#"><?=$item[0]?></a></li>
							<ul>
							<?php foreach($item['sub'] as $key => $sub): ?>
								<li>- <a class="nav-activ" href="?view=cat&amp;category=<?=$key?>"><?=$sub?></a></li>
							<?php endforeach; ?>
						</ul>
						<?php elseif($item[0]): // если самостоятельная категория ?>
						<li class = 'nol'><a href="?view=cat&amp;category=<?=$key?>"><?=$item[0]?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
					</ul>
						<li><a href="?view=news">Новости</a></li>
						<li><a href="#">Пользователи</a></li>
			</ul>
		</div> <!-- .leftBar -->