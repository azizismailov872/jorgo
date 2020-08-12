<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

if($model !== null)
{
	$this->title = $model->meta_title;
	$this->registerMetaTag([
		'name' => 'description',
		'content' => $model->meta_description,
	]);
	$this->registerMetaTag([
		'name' => 'keywords',
		'content' => $model->meta_keywords,
	]);
	$this->params['breadcrumbs'][] = $this->title;
}
$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<section class="box_content box_content_half_top">
	<div class="container">
		<h1 class="page_title"><?= $model->title; ?></h1>
		<div class="clearfix page_content">
			<?= $model->text; ?>
		</div>		
		<div class="social_share">
			<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
			<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
			<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,pinterest,viber,whatsapp,skype,telegram"></div>
		</div>
	</div>
</section>
