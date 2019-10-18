<?php

use frontend\widgets\CityWidget;

?>
<div class="row">
	<div class="col-md-2 col-sm-12">
		<a href="/"><img src="/images/logo_500.png" alt="Всероссийская сеть Билетур" height="45"></a>
	</div>
	<div class="col-md-7 col-sm-12" style="padding-top: 18px;">
		<input type="text" class="biletur-text-input" placeholder="Поиск" style="margin-left: 20px;">
	</div>
	<div class="col-md-2 col-sm-12">
		<div class="pull-right text-center call-center-block visible-lg visible-md visible-sm visible-xs" style="display: inline-block;">
			<a href="http://www.biletur.ru/news/shnews.asp?id=5634">
				Контактный Центр<br>
				<span class="glyphicon glyphicon-phone-alt"></span> <strong>8-800-200-66-66</strong><br>
				звонки по России – бесплатно<br>
			</a>
		</div>
	</div>
</div>
<?= CityWidget::widget() ?>

