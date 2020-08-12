<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => [
	'class' => 'form-horizontal form-label-left',
	'id' => 'edit-menu-category-form',
	'enableClientValidation' => false,
]]); 
?>
<?= $form->field($editMenuCategoryModel, 'edit_menu_category_name', [
	'template' => '<div class="form-group">
		{label}
		<div class="col-sm-8">
			<div class="input-group">
				{input}
				<span class="input-group-btn">
					'.Html::button(Yii::t('form', 'Edit'), ['id'=>'edit-menu-category', 'class' => 'btn btn-success']).'
				</span>
				<span class="input-group-btn">
					'.Html::button(Yii::t('form', 'Delete'), ['id'=>'delete-menu-category', 'class' => 'btn btn-danger']).'
				</span>
			</div>
			<ul class="parsley-errors-list filled" id="parsley-id-5">
				<li class="parsley-required">{error}</li>
			</ul>
		</div>
	</div>',
	'labelOptions' => ['class' => 'col-sm-2 control-label'],
])->dropDownList($menuCategoriesList, ['id'=>'menu-category', 'prompt'=>'Выбрать']); ?>
<?php ActiveForm::end(); ?>
