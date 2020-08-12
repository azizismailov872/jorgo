<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="menu-create" id="index-wrapper">
	<div class="row" id="add-menu-container">
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
                <div class="x_content" id="add-menu-form-wrapper">
					<br />
					<?php $form = ActiveForm::begin(['options' => [
						'class' => 'form-horizontal form-label-left',
						'id' => 'add-menu-form',
						'enableClientValidation' => false,
					]]); 
					?>
					<div class="form-group">
						<label class="control-label col-sm-2" for="menu-edit_menu_name"><?= Yii::t('form', 'Category'); ?></label>
						<div class="col-sm-8" style="padding-top: 8px; font-weight:bold;">
							<?= $model->category_name; ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="menu-edit_menu_name"><?= Yii::t('form', 'Parent menu'); ?></label>
						<div class="col-sm-8" style="padding-top: 8px; font-weight:bold;">
							<?= $model->parent_menu_name; ?>
						</div>
					</div>
					<?= $form->field($model, 'name', [
						'template' => '
						<div class="form-group" id="field_menu_name">
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
					<?= $form->field($model, 'url', [
						'template' => '
						<div class="form-group" id="field_menu_url">
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
					<?=$form->field($model, 'status', [
					'template' => '
					<div class="form-group" id="field_menu_status">
						{label}
						<div class="col-sm-8">
							<div class="checkbox">
								{input}
							</div>
							<ul class="parsley-errors-list filled" id="parsley-id-1">
								<li class="parsley-required">{error}</li>
							</ul>
						</div>
					</div>',
					'labelOptions' => ['class' => 'form-control col-md-8 col-xs-10'],
					])->checkbox(['class' => 'flat', 'uncheck' => '0', 'checked' => '1'])->label(Yii::t('form', 'Status'), ['class' => 'col-md-2 col-sm-2 col-xs-10 control-label']);?>
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
							<?= Html::submitButton(Yii::t('form', 'Update'), ['class' => 'btn btn-primary']); ?>
						</div>
					</div>
					<?= $form->field($model, 'id')->hiddenInput(['id'=>'update-menu-id', 'value'=>$id])->label(false) ?>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
