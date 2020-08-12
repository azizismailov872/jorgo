<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\uploads\models\Files;

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
<div class="catalog content-bottom">
	<div class="optovik-page content-bottom">
		<div class="banner-full">
			<?php
				$thumbnail = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['origin_image'])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['origin_image'] : [];
				$dir = Files::getUploadDirUrl($model->id, $category, true, $dirName = 'origin_image');
				$files = Files::getFilesByParams($model->id, $category, 'origin_image', $dir);
				$file = (!empty($files)) ? $files[0] : '';
														
				if($file != ''):
					$thumbnail = Files::getThumbnailParams($thumbnail, Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir, $file);
					$file = explode('/', $file);
					$file = end($file);
					
					echo Html::a(Html::img(Url::to('@upload_dir'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file), ['alt'=>$model->title, 'width'=>((isset($thumbnail['width'])) ? $thumbnail['width'].'px' : ''), 'height'=>((isset($thumbnail['height'])) ? $thumbnail['height'].'px' : '')]), '', ['alt'=> '', 'title'=>'']);
				
				else:
					
					echo Html::img(Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'banner-about.jpg'), ['alt' =>  '', 'title' => '']);
					
				endif;
			?>
		</div>
		<h2 class="content-h2"><?= ($model !== null) ? $model->title : ''; ?></h2>
		<div class="optovik_text">
			<?= ($model !== null) ? $model->text : '' ?>
		</div>
	</div>
</div>
