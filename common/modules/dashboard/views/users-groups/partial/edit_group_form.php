<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => [
	'class' => 'form-horizontal form-label-left',
	'id' => 'edit-group-form',
	'enableClientValidation' => false,
]]); 
?>
<?= $form->field($editGroupModel, 'edit_group_name', [
	'template' => '<div class="form-group">
		{label}
		<div class="col-sm-8">
			<div class="input-group">
				{input}
				<span class="input-group-btn">
					'.Html::button(Yii::t('form', 'Edit'), ['id'=>'edit-group', 'class' => 'btn btn-success']).'
				</span>
				<span class="input-group-btn">
					'.Html::button(Yii::t('form', 'Delete'), ['id'=>'delete-group', 'class' => 'btn btn-danger']).'
				</span>
			</div>
			<ul class="parsley-errors-list filled" id="parsley-id-5">
				<li class="parsley-required">{error}</li>
			</ul>
		</div>
	</div>',
	'labelOptions' => ['class' => 'col-sm-2 control-label'],
])->dropDownList($usersGroupsList, ['id'=>'users-groups', 'prompt'=>'Выбрать']); ?>
<?php ActiveForm::end(); ?>
