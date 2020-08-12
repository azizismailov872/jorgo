<?php
use yii\helpers\Html;
?>
<div class="form-group field-inherit-roles required">
	<div class="form-group">
		<label class="control-label col-sm-2" for="inherit-roles"><?= Yii::t('form', 'Inherit roles'); ?></label>
		<div class="col-sm-8">
		<?= Html::dropDownList('InheritRoleForm[inherit_roles][]', null, $roleList, [
			'id'=>'inherit-roles', 
			'multiple'=>'multiple',
			'class'=>'form-control', 
		])?>
		</div>
		<ul class="parsley-errors-list filled" id="parsley-id-1">
			<li class="parsley-required"></li>
		</ul>
	</div>
</div>
