<?php

use common\models\scheme\sns\News;

/**
 * @author isakov.v
 *
 * @var common\models\scheme\sns\News[] $news
 * @var string                          $groupId
 */
$title = News::getTitle($groupId);
?>
<div class="widget-block-header"><?= $title ?></div>
<div class="news-widget-block">
	<?php $prevDate = null; ?>
	<?php foreach ($news as $newsItem): ?>
		<?php if ($prevDate != $newsItem->NEWSDATE): ?>
            <span class="small" style="margin-left: 5px;"><?= \common\components\helpers\OraHelper::dateFromDateTime($newsItem->NEWSDATE) ?></span>
			<?php $prevDate = $newsItem->NEWSDATE ?>
		<?php endif ?>
        <div class="widget-block-content">
            <li><a href="<?= \yii\helpers\Url::to(['news/index', 'id' => $newsItem->ID]) ?>"><?= $newsItem->TITLE ?></a></li>
        </div>
	<?php endforeach ?>
	<? if (count($news) < 10): ?>
        <p>
            <a href="<?= \yii\helpers\Url::to(['news/archive', 'id' => $groupId]) ?>">
                <span class="small pull-right">Все <?= strtolower($title) ?></span>
            </a>
        </p>
	<? endif ?>
</div>