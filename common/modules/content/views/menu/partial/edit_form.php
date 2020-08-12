<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$model->edit_menu_name = $model->name;
$model->edit_menu_url = $model->url;
$form = ActiveForm::begin(['options' => [
	'class' => 'form-horizontal form-label-left',
	'id' => 'update-menu-form',
	'enableClientValidation' => false,
]]); 
?>
<div class="form-group">
	<label class="control-label col-sm-2" for="menu-edit_menu_name"><?= Yii::t('form', 'Menu category'); ?></label>
	<div class="col-sm-8" style="padding-top: 8px; font-weight:bold;">
		<?= $model->category_name; ?>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-2" for="menu-edit_menu_name"><?= Yii::t('form', 'Parent name'); ?></label>
	<div class="col-sm-8" style="padding-top: 8px; font-weight:bold;">
		<?= $model->parent_menu_name; ?>
	</div>
</div>
<?= $form->field($model, 'edit_menu_name', [
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
	])->textInput(['id'=>'update-menu-name', 'maxlength' => 100, 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
?>
<?= $form->field($model, 'edit_menu_url', [
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
	])->textInput(['id'=>'update-menu-url', 'maxlength' => 100, 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
?>
<div class="form-group">
	<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
		<?= Html::submitButton(Yii::t('form', 'Create'), ['id'=>'update-menu', 'class' => 'btn btn-primary']); ?>
	</div>
</div>
<?= $form->field($model, 'id')->hiddenInput(['id'=>'update-menu-id', 'value'=>$id])->label(false) ?>
<?php ActiveForm::end(); ?>
