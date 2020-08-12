<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$model->edit_menu_category_name = $model->name;
$form = ActiveForm::begin(['options' => [
	'class' => 'form-horizontal form-label-left',
	'id' => 'update-group-form',
	'enableClientValidation' => false,
]]); 
?>
<?= $form->field($model, 'edit_menu_category_name', [
	'template' => '<div class="form-group">
		{label}
        <div class="col-sm-8">
			<div class="input-group">
				{input}
				<span class="input-group-btn">
					'.Html::button(Yii::t('form', 'Update'), ['id'=>'update-menu-category', 'class' => 'btn btn-primary']).'
				</span>
			</div>
			<ul class="parsley-errors-list filled" id="parsley-id-5">
				<li id="error_group_name" class="parsley-required">{error}</li>
			</ul>
		</div>
	</div>',
	'labelOptions' => ['id'=>'group_name_label', 'class' => 'col-sm-2 control-label'],
	])->textInput(['id'=>'update-menu-category-name', 'maxlength' => 100]); 
?>
<?= $form->field($model, 'id')->hiddenInput(['id'=>'update-menu-category-id', 'value'=>$id])->label(false) ?>
<?php ActiveForm::end(); ?>
