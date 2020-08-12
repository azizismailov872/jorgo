<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'content.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
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
						<?= Html::a(Yii::t('form', 'Back'), ['/content/static-content'], ['class' => 'btn btn-success']) ?>
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
				<?= $form->field($model, 'name', [
					'template' => '
					<div class="form-group" id="field_price">
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
				<div class="form-wysywig">
				<?= $form->field($model, 'content', [
					'template' => '
					<div class="form-group" id="field_price">
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
						'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=static-content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
					],
				]); 
				?>
				</div>
				<?/*= Html::a(Yii::t('form', 'Без wysywig'), '#', ['class' => 'no-wysywig']) ?>
				<div class="form-no-wysywig" style="display: none;">
					<?= $form->field($model, 'content_no_wysywig', [
						'template' => '
						<div class="form-group" id="field_price">
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
					<?= $form->field($model, 'content_no_wysywig_on')->hiddenInput(['value'=>0, 'class'=>'content-no-wysywig-on'])->label(false) ?>
				</div>
				<?php */ ?>
				<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
						<?= Html::submitButton(Yii::t('form', 'Create'), ['class' => 'btn btn-primary']); ?>
					</div>
                </div>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
