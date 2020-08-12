<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\uploads\models\Files;
?>
<!-- item element -->
<div class="col-md-4 news_list_item">
	<div class="news_list_item_title">
		<a href="news-single.php"><?= $model['title']; ?></a>
	</div>
	<div class="news_list_item_text"><?= $model['short_text']; ?></div>
	<div class="news_list_item_link">
		<?= Html::a('More info', ['/news/'.$model['id']], []); ?>
	</div>
</div>
