<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
?>
<section class="box_content box_content_half_top">
	<div class="container">
		<h1 class="page_title">Новости</h1>
		<div class="row news_list">
			<?= ListView::widget([
				'dataProvider' => $dataProvider,
				'options' => [],
				'itemView' => function ($model, $key, $index, $widget)
				{
					return $this->render('partial/_news_list_item', [
						'model' => $model
					]);
				},
				'layout' => '{items}',
				'pager' => [
					'maxButtonCount' => 10,
					'prevPageLabel' => Yii::t('form', 'Previous'),
					'nextPageLabel' => Yii::t('form', 'Next'),
				],
			]);
			?>
		</div>
	</div>
</section>
