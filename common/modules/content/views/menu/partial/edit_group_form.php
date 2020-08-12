<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => [
	'class' => 'form-horizontal form-label-left',
	'id' => 'edit-menu-form',
	'enableClientValidation' => false,
]]); 
?>
<?= $form->field($editMenuModel, 'edit_menu_name', [
	'template' => '<div class="form-group">
		{label}
		<div class="col-sm-8">
			<div class="input-group">
				{input}
				<span class="input-group-btn">
					'.Html::button(Yii::t('form', 'Edit'), ['id'=>'edit-menu', 'class' => 'btn btn-success']).'
				</span>
				<span class="input-group-btn">
					'.Html::button(Yii::t('form', 'Delete'), ['id'=>'delete-menu', 'class' => 'btn btn-danger']).'
				</span>
				<span class="input-group-btn">
					'.Html::button(Yii::t('form', 'Publish'), ['id'=>'publish-menu', 'publish'=>'1', 'class' => 'btn btn-primary']).'
				</span>
			</div>
			<ul class="parsley-errors-list filled" id="parsley-id-5">
				<li class="parsley-required">{error}</li>
			</ul>
		</div>
	</div>',
	'labelOptions' => ['class' => 'col-sm-2 control-label'],
])->dropDownList($menuList, ['id'=>'menu-list', 'prompt'=>'Выбрать']); ?>
<?php ActiveForm::end(); ?>
