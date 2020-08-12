<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">
	<div class="action_buttons">
        <?= Html::a(Yii::t('form', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('form', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('form', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
	<div class="panel-wrapper fixed">
		<div class="panel">
			<div class="title"><h4><?= $this->title; ?></h4></div>
			<div class="content"><?= $model->text; ?></div>
		</div>
	</div>
</div>
