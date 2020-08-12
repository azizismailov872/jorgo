<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdminUsers */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="users-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 100]) ?>
    <?php if($profile): ?>
	<div class="selector">
	<?= $form->field($model, 'AdminUsersGroups')->dropDownList(
		$dataList,
		[
			'prompt'=>Yii::t('form', 'Выберите группу'),
			'style'=>'width:300px;'
		] // options
	);
	
	
	
	DropDownListHelper::getDropDownList($form, $model, 'UserGroups', 'group_id', 'app', [
		'prompt'=>Yii::t('form', 'Выберите группу'),
		'style'=>'width:300px;'
	]); ?>
    </div>
    <?php endif; ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 're_password')->passwordInput(['maxlength' => 32]) ?>
   <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
   </div>
   <?php ActiveForm::end(); ?>
</div>
