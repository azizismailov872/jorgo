<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'uploads.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'content.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);
?>
<div class="users-create" id="index-wrapper">
	<div class="row" id="add-group-container">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<?php if(Yii::$app->session->hasFlash('success')): ?>
			<div id="alert-success" class="alert alert-success alert-dismissible fade in hidden_element" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				<div class="alert_message"></div>
            </div>
            <?php endif; ?>
            <?php if(Yii::$app->session->hasFlash('error')): ?>
			<div id="alert-danger" class="alert alert-danger alert-dismissible fade in hidden_element" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <div class="alert_message"></div>
            </div>
            <?php endif; ?>
			<div class="x_panel">
				<div class="x_title">
					<h2><?= $title; ?></h2>
					<div class="nav navbar-right">
						<?= Html::a(Yii::t('form', 'Back'), ['/static-content'], ['class' => 'btn btn-success']) ?>
					</div>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'add-content-form',
					'action' => 'create',
					'enableClientValidation' => false,
				]]); 
				?>
				<div class="form-group">
					<div class="form-group">
						<label class="control-label col-sm-2" for="content-content"><?= Yii::t('form', 'Menu'); ?></label>
                        <div class="col-sm-8">
							<?= $model->menu->name; ?>
						</div>
					</div>
				</div>
				<?= $form->field($model, 'title', [
					'template' => '
					<div class="form-group" id="field_title">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<?= $form->field($model, 'content', [
					'template' => '
					<div class="form-group" id="field_content">
						{label}
						<div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->widget(CKEditor::className(),[
					'editorOptions' => [
						'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
						'inline' => false, //по умолчанию false
						'language' => 'ru',
						'extraPlugins' => 'image',
						'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
					],
				]); 
				?>
				<?= $form->field($model, 'meta_title', [
					'template' => '
					<div class="form-group" id="field_meta_title">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<?= $form->field($model, 'meta_description', [
					'template' => '
					<div class="form-group" id="field_meta_description">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textArea(['rows' => '6', 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<?= $form->field($model, 'meta_keywords', [
					'template' => '
					<div class="form-group" id="field_meta_keywords">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textArea(['rows' => '6', 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<div class="form-group" style="margin-bottom:20px;">
					<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-2">
						<?= FileUpload::widget([
							'model' => $model,
							'attribute' => 'image',
							'url' => ['item/image-upload', 'id' => $model->id, 'category' => $category, 'upload_type' => 'tmp'], // your url, this is just for demo purposes,
							'options' => ['accept' => 'image/*'],
							'clientOptions' => [
								'maxFileSize' => 2000000
							],
							// Also, you can specify jQuery-File-Upload events
							// see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
							'clientEvents' => [
								'fileuploaddone' => 'function(e, data) 
								{
									var item_id = ($("div.file_wrap").length > 0) ? $("div.file_wrap").length : 0;
									file_upload(data, item_id);
								}',
								'fileuploadfail' => 'function(e, data) 
								{
									console.log(e);
									console.log(data);
								}',
							],
						]); ?>
						<?= \yii\base\View::render(
						'//../../common/modules/uploads/views/file-list/list-file',
							[
								'category'=>$category,
								'id'=>$model->id,
								'thumbnail'=>$thumbnail = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [],
								'tmp'=>true,
							]
						);
						?>
					</div>
                </div>
				<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
						<?= Html::submitButton(Yii::t('form', 'Update'), ['class' => 'btn btn-primary']); ?>
					</div>
                </div>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
