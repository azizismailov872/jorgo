<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\modules\uploads\models\Files;

$this->title = $meta_title;
$this->registerMetaTag([
    'name' => 'description',
    'content' => $meta_desc,
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $meta_keys,
]);
?>
<div class="catalog content-bottom">
	<div class="optovik-page content-bottom">
		<?php if($data !== null): ?>
			<div class="banner-full">
				<?php
					$thumbnail = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['origin_image'])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['origin_image'] : [];
					$dir = Files::getUploadDirUrl($data->id, $category, true, $dirName = 'origin_image');
					$files = Files::getFilesByParams($data->id, $category, 'origin_image', $dir);
					$file = (!empty($files)) ? $files[0] : '';
															
					if($file != ''):
						$thumbnail = Files::getThumbnailParams($thumbnail, Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir, $file);
						$file = explode('/', $file);
						$file = end($file);
						
						echo Html::a(Html::img(Url::to('@upload_dir'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file), ['alt'=>$data->title, 'width'=>((isset($thumbnail['width'])) ? $thumbnail['width'].'px' : ''), 'height'=>((isset($thumbnail['height'])) ? $thumbnail['height'].'px' : '')]), '', ['alt'=> '', 'title'=>'']);
					
					else:
						
						echo Html::img(Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'banner-about.jpg'), ['alt' =>  '', 'title' => '']);
						
					endif;
				?>
			</div>
			<h2 class="content-h2"><?= $this->title; ?></h2>
			<div class="optovik_text">
				<?= ($data !== null) ? $data->content : '' ?>
			</div>
			<?php if($url == 'wholesale' && $model !== null): ?>
			<div class="optovik-form">
				<div class="row">
					<div class="col-xl-7 col-12">
						<h3>Получить прайс-лист и специальное предложение</h3>
						<p>Краткая информация о способах оплаты и прочие важные мелочи, что-то вроде: “Удобные фильтры поиска, роскошный ассортимент, приятные цены и оперативная доставка в любую точку мира удовлетворят все ваши потребности!”</p>
						<?php $form = ActiveForm::begin([
							'action' => \Yii::$app->request->BaseUrl.'/purchase-wholesale/create',
							'options' => [
								'id' => 'wholesale',
								'enableClientValidation' => false,
							],
							'fieldConfig' => [
								'options' => [
									'tag' => false,
								],
							],
						]); 
						?>
						<?= $form->field($model, 'customer_name', [
							'template' => '
							<div class="form-group" id="field_name">
								{label}{input}{error}
							</div>',
							'inputOptions' => [
								'placeholder' => Yii::t('form', 'Ведите ФИО'),
								'class' => 'form-control',
							]
							])->textInput(['maxlength' => 100])->label(false); 
						?>
						<?= $form->field($model, 'email', [
							'template' => '
							<div class="form-group" id="field_email">
								{label}{input}{error}
							</div>',
							'inputOptions' => [
								'placeholder' => Yii::t('form', 'Введите ваш e-mail адрес'),
								'class' => 'form-control',
							]
							])->textInput(['maxlength' => 50])->label(false); 
						?>
						<?= $form->field($model, 'phone', [
							'template' => '
							<div class="form-group" id="field_phone">
								{label}{input}{error}
							</div>',
							'inputOptions' => [
								'placeholder' => Yii::t('form', 'Ведите номер телефона'),
								'class' => 'form-control',
							]
							])->textInput(['maxlength' => 50])->label(false); 
						?>
						<?= $form->field($model, 'message', [
							'template' => '
							<div class="form-group" id="field_message">
								{label}{input}{error}
							</div>',
							'inputOptions' => [
								'placeholder' => Yii::t('form', 'Текст комментария к заявке'),
								'class' => 'form-control',
							]
							])->textArea(['rows' => '6'])->label(false); 
						?>
						<?= Html::submitButton(Yii::t('form', 'Send'), ['class' => 'btn btn-primary']); ?>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
