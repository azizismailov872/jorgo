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
	<div class="row" id="add-module-container">
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
					<h2><?= Yii::t('form', 'Create module'); ?></h2>
					<div class="nav navbar-right">
						<?= Html::a(Yii::t('form', 'Back'), ['/permissions'], ['class' => 'btn btn-success']) ?>
					</div>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
				<?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'create-module-form',
					'enableClientValidation' => false,
				]]); 
				?>
                <?= $form->field($moduleModel, 'name', [
					'template' => '
					<div class="form-group" id="field_authmodules-name">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textInput(['maxlength' => 100, 'id' => 'create-module', 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
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
	<div class="row" id="delete-module-container">
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
					<h2><?= Yii::t('form', 'Delete module'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content" id="delete-module-wrapper">
					<br />
					<div class="form-group">
						<label class="col-sm-2 control-label" for="delete-role-list"><?= Yii::t('form', 'Delete'); ?></label>
						<div class="col-sm-8">
							<div class="input-group">
								<?= Html::dropDownList('name', null, $moduleList, $options = [
									'id'=>'delete-module-list', 
									'class'=>'form-control',
									'prompt'=>Yii::t('form', 'Select')
								]); 
								?>
								<span class="input-group-btn">
								<?= Html::button(Yii::t('form', 'Delete'), ['id'=>'delete-module', 'class' => 'btn btn-danger']); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="add-permissions-container">
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
					<h2><?= Yii::t('form', 'Create role permission'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
				<?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'create-permissions-form',
					'enableClientValidation' => false,
				]]); 
				?>
				<?= $form->field($permissionsModel, 'role_id', [
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
					])->dropDownList($roleList, ['id'=>'role-list', 'prompt'=>Yii::t('form', 'Select')]);
				?>
                <?= $form->field($permissionsModel, 'permission', [
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
					])->dropDownList($permissionsList, ['id'=>'permissions-list', 'prompt'=>Yii::t('form', 'Select')]);
				?>
				<?= $form->field($permissionsModel, 'module_id', [
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
					])->dropDownList($moduleList, ['id'=>'module-list', 'prompt'=>Yii::t('form', 'Select')]);
				?>
				<?= $form->field($permissionsModel, 'description', [
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
</div>
