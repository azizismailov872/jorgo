<?php
use yii\helpers\Html;
?>
<div class="form-group field-menu-category required">
	<div id="field_menu_category_id" class="form-group">
		<label class="control-label col-sm-2" for="inherit-roles"><?= Yii::t('form', 'Menu Category Id'); ?></label>
		<div class="col-sm-8">
		<?= Html::dropDownList('AddMenuForm[menu_category_id]', null, $menuCategoriesList, [
			'id'=>'menu-category',
			'class'=>'form-control', 
		])?>
		</div>
		<ul class="parsley-errors-list filled" id="parsley-id-1">
			<li class="parsley-required"></li>
		</ul>
	</div>
</div>
