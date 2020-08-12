<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
?>
<div class="blog-page content-bottom">
	<h2 class="content-h2">Наши новости</h2>
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'options' => 
		[
			'id' => false,
			'class' => 'row'
		],
		'itemOptions' => 
		[
			'tag' => false
		],
		'itemView' => function ($model, $key, $index, $widget) use ($theme, $category)
		{
			return $this->render('partial/_news_list_item'.$theme, [
				'model' => $model,
				'category' => $category
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
