<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\uploads\models\Files;
?>
<!-- item element -->
<div class="col-xl-4">
	<div class="blog-item">
		<div class="blog-img">
			<?php
				$thumbnail = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
				$dir = Files::getUploadDirUrl($model['id'], $category, true, $dirName = 'origin_image');
				$files = Files::getFilesByParams($model['id'], $category, 'origin_image', $dir);
				$file = (!empty($files)) ? $files[0] : '';
														
				if($file != ''):
					$thumbnail = Files::getThumbnailParams($thumbnail, Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir, $file);
					$file = explode('/', $file);
					$file = end($file);
					
					echo Html::a(Html::img(Url::to('@upload_dir'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file), ['alt'=>$model['title'], 'width'=>((isset($thumbnail['width'])) ? $thumbnail['width'].'px' : ''), 'height'=>((isset($thumbnail['height'])) ? $thumbnail['height'].'px' : '')]), '', ['alt'=> '', 'title'=>'']);
				
				else:
					
					echo Html::a(Html::img(Url::to('@frontend_upload'.DIRECTORY_SEPARATOR.'blog-1.jpg'), ['alt'=> '', 'title'=>'']), ['/news/'.$model['id']], []);
					
				endif;
			?>
		</div>
        <div class="blog-item-wrap">
			<div class="blog-item-title">
				<?= Html::a($model['title'], ['/news/'.$model['id']], []); ?>
           </div>
           <p><?= Html::a($model['short_text'], ['/news/'.$model['id']], []); ?></p>
        </div>
	</div>
</div>
