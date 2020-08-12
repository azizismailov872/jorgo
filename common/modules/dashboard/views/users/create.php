<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'dashboard'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'user.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="users-create" id="index-wrapper">
	<div class="row" id="add-group-container">
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
					<h2><?= Yii::t('form', 'Create group'); ?></h2>
					<div class="nav navbar-right">
						<?= Html::a(Yii::t('form', 'Back'), ['/users'], ['class' => 'btn btn-success']) ?>
					</div>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'add-group-form',
					'action' => 'create-user',
					'enableClientValidation' => false,
				]]); 
				?>
                <?= $form->field($addGroupModel, 'group_name', [
					'template' => '<div class="form-group">
						{label}
                        <div class="col-sm-8">
							<div class="input-group">
								{input}
								<span class="input-group-btn">
									'.Html::submitButton(Yii::t('form', 'Create'), ['class' => 'btn btn-primary']).'
								</span>
							</div>
							<ul class="parsley-errors-list filled" id="parsley-id-1">
								<li class="parsley-required">{error}</li>
							</ul>
                        </div>
					</div>',
					'labelOptions' => ['class' => 'col-sm-2 control-label'],
				])->textInput(['maxlength' => 100, 'id' => 'create-user']); ?>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="edit-group-container">
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
					<h2><?= Yii::t('form', 'Edit and delete group'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content" id="edit-group-form-wrapper">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'edit-group-form',
					'action' => 'create-user',
					'enableAjaxValidation' => true,
					'enableClientValidation' => false,
				]]); 
				?>
                <?= $form->field($editGroupModel, 'edit_group_name', [
					'template' => '<div class="form-group" id="field_edit_group_name">
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
							<ul class="parsley-errors-list filled" id="parsley-id-1">
								<li class="parsley-required">{error}</li>
							</ul>
                        </div>
					</div>',
					'labelOptions' => ['class' => 'col-sm-2 control-label'],
				])->dropDownList($usersGroupsList, ['id'=>'users-groups', 'prompt'=>'Выбрать']); ?>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="create-user-container">
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
					<h2><?= Yii::t('form', 'Create user'); ?></h2>
					<div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'create-user-form',
					'action' => 'create-user',
					'enableAjaxValidation' => true,
					'enableClientValidation' => false,
				]]); 
				?>
                <?= $form->field($usersModel, 'username', [
					'template' => '
					<div class="form-group" id="field_username">
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
				<?= $form->field($usersModel, 'email', [
					'template' => '
					<div class="form-group" id="field_email">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-2">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<?= $form->field($usersModel, 'password', [
					'template' => '
					<div class="form-group" id="field_password">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-3">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->passwordInput(['maxlength' => 32]) 
				?>
				<?= $form->field($usersModel, 're_password', [
					'template' => '
					<div class="form-group" id="field_re_password">
						{label}
                        <div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-4">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->passwordInput(['maxlength' => 32]) 
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
