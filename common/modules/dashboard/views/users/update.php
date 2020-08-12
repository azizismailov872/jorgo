<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'dashboard'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'user.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="users-create" id="index-wrapper">
	<div class="row" id="edit-user-container">
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
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'update-user-form',
					'action' => 'update-user',
					'enableClientValidation' => false,
				]]); 
				?>
                <?= $form->field($model, 'username', [
					'template' => '
					<div class="form-group" id="field_username">
						{label}
                        <div class="col-md-6 col-sm-6 col-xs-12">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-5">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<?= $form->field($model, 'email', [
					'template' => '
					<div class="form-group" id="field_email">
						{label}
                        <div class="col-md-6 col-sm-6 col-xs-12">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-5">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<?= $form->field($model, 'password', [
					'template' => '
					<div class="form-group" id="password">
						{label}
                        <div class="col-md-6 col-sm-6 col-xs-12">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-5">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
					])->passwordInput(['maxlength' => 32]) 
				?>
				<?= $form->field($model, 're_password', [
					'template' => '
					<div class="form-group" id="re_password">
						{label}
                        <div class="col-md-6 col-sm-6 col-xs-12">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-5">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
					])->passwordInput(['maxlength' => 32]) 
				?>
				<?= $form->field($model, 'id')->hiddenInput(['id'=>'update-user-id', 'value'=>$model->id])->label(false) ?>
				<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<?= Html::submitButton(Yii::t('form', 'Update'), ['class' => 'btn btn-primary']); ?>
					</div>
                </div>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
