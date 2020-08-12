<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'dashboard'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'rbac.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="create-rback" id="index-wrapper">
	<div class="row" id="add-role-container">
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
					<h2><?= Yii::t('form', 'Create role'); ?></h2>
					<div class="nav navbar-right">
						<?= Html::a(Yii::t('form', 'Back'), ['/permissions'], ['class' => 'btn btn-success']) ?>
					</div>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
				<?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'create-role-form',
					'enableClientValidation' => false,
				]]); 
				?>
                <?= $form->field($roleModel, 'role', [
					'template' => '
					<div class="form-group" id="field_role">
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
				<?= $form->field($roleModel, 'group_id', [
					'template' => '
					<div class="form-group" id="field_group_id">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-2">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->dropDownList($usersGroupsList, ['id'=>'users-groups', 'prompt'=>'Выбрать']);
				?>
				<?= $form->field($roleModel, 'description', [
					'template' => '
					<div class="form-group" id="field_description">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-3">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
						<?= Html::submitButton(Yii::t('form', 'Create'), ['class' => 'btn btn-primary']); ?>
					</div>
                </div>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="delete-role-container">
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
					<h2><?= Yii::t('form', 'Delete role'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content" id="delete-role-wrapper">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="delete-role-list"><?= Yii::t('form', 'Delete'); ?></label>
						<div class="col-sm-8">
							<div class="input-group">
								<?= Html::dropDownList('group_id', null, $roleList, $options = [
									'id'=>'delete-role-list', 
									'class'=>'form-control',
									'prompt'=>Yii::t('form', 'Select')
								]); 
								?>
								<span class="input-group-btn">
								<?= Html::button(Yii::t('form', 'Delete'), ['id'=>'delete-role', 'class' => 'btn btn-danger']); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="delete-inherit-role-container">
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
					<h2><?= Yii::t('form', 'Delete inherit role'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
				<?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'delete-inherit-role-form',
					'enableClientValidation' => false,
				]]); 
				?>
				<?= $form->field($inheritRoleModel, 'role_id', [
					'template' => '
					<div class="form-group">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->dropDownList($roleList, ['id'=>'delete-inherit-role-id', 'prompt'=>'Выбрать']);
				?>
				<div id="delete-role-list-wrapper">
				<?= $form->field($inheritRoleModel, 'inherit_roles[]', [
					'template' => '
					<div class="form-group">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->dropDownList($roleList, ['id'=>'delete-inherit-roles', 'multiple'=>'multiple']);
				?>
				</div>
				<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
						<?= Html::submitButton(Yii::t('form', 'Delete'), ['class' => 'btn btn-danger']); ?>
					</div>
                </div>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="inherit-role-container">
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
					<h2><?= Yii::t('form', 'Inherit role'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
				<?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'inherit-role-form',
					'enableClientValidation' => false,
				]]); 
				?>
				<?= $form->field($inheritRoleModel, 'role_id', [
					'template' => '
					<div class="form-group">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->dropDownList($roleList, ['id'=>'inherit-role-id', 'prompt'=>'Выбрать']);
				?>
				<div id="role-list-wrapper">
				<?= $form->field($inheritRoleModel, 'inherit_roles[]', [
					'template' => '
					<div class="form-group">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->dropDownList($roleList, ['id'=>'inherit-roles', 'multiple'=>'multiple']);
				?>
				</div>
				<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
						<?= Html::submitButton(Yii::t('form', 'Create'), ['class' => 'btn btn-primary']); ?>
					</div>
                </div>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
