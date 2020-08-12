<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'uploads.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'content.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="page-create" id="index-wrapper">
	<div class="row" id="add-menu-category-container">
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
					<h2><?= Yii::t('form', 'Create menu category'); ?></h2>
					<div class="nav navbar-right">
						<?= Html::a(Yii::t('form', 'Back'), ['/content'], ['class' => 'btn btn-success']) ?>
					</div>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'add-menu-category-form',
					'action' => 'create-menu-category',
					'enableClientValidation' => false,
				]]); 
				?>
				<?= $form->field($menuCategoryModel, 'menu_category_name', [
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
				])->textInput(['maxlength' => 100, 'id'=> 'category-menu-name']); ?>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="edit-menu-category-container">
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
					<h2><?= Yii::t('form', 'Edit and delete menu category'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content" id="edit-menu-category-form-wrapper">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'edit-menu-category-form',
					'action' => 'edit-menu-category',
					'enableAjaxValidation' => true,
					'enableClientValidation' => false,
				]]); 
				?>
                <?= $form->field($editMenuCategoryModel, 'edit_menu_category_name', [
					'template' => '<div class="form-group" id="field_edit_menu_category_name">
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
							<ul class="parsley-errors-list filled" id="parsley-id-1">
								<li class="parsley-required">{error}</li>
							</ul>
                        </div>
					</div>',
					'labelOptions' => ['class' => 'col-sm-2 control-label'],
				])->dropDownList($menuCategoriesList, ['id'=>'menu-category', 'prompt'=>'Выбрать']); ?>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="add-menu-container">
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
					<h2><?= Yii::t('form', 'Create menu'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content" id="add-menu-form-wrapper">
					<br />
					<?php $form = ActiveForm::begin(['options' => [
						'class' => 'form-horizontal form-label-left',
						'id' => 'add-menu-form',
						'enableClientValidation' => false,
					]]); 
					?>
					<div id="menu-category-wrapper">
						<?= $form->field($addMenuModel, 'menu_category_id', [
							'template' => '
							<div class="form-group" id="field_category_id">
								{label}
								<div class="col-sm-8">
									{input}
								</div>
								<ul class="parsley-errors-list filled" id="parsley-id-2">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>',
							'labelOptions' => ['class' => 'control-label col-sm-2'],
							])->dropDownList($menuCategoriesList, ['id'=>'menu-category-id', 'prompt'=>'Выбрать']);
						?>
					</div>
					<?= $form->field($addMenuModel, 'menu_parent_id', [
						'template' => '
						<div class="form-group" id="field_menu_parent_id">
							{label}
							<div class="col-sm-8">
								{input}
							</div>
							<ul class="parsley-errors-list filled" id="parsley-id-2">
								<li class="parsley-required">{error}</li>
							</ul>
						</div>',
						'labelOptions' => ['class' => 'control-label col-sm-2'],
						])->dropDownList($menuList, ['id'=>'menu', 'prompt'=>'Выбрать']);
					?>
					<?= $form->field($addMenuModel, 'menu_name', [
						'template' => '
						<div class="form-group" id="field_menu_name">
							{label}
							<div class="col-sm-8">
								{input}
							</div>
							<ul class="parsley-errors-list filled" id="parsley-id-1">
								<li class="parsley-required">{error}</li>
							</ul>
						</div>',
						'labelOptions' => ['class' => 'control-label col-sm-2'],
						])->textInput(['maxlength' => 100, 'id' => 'menu-name', 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
					?>
					<?= $form->field($addMenuModel, 'menu_url', [
						'template' => '
						<div class="form-group" id="field_menu_url">
							{label}
							<div class="col-sm-8">
								{input}
							</div>
							<ul class="parsley-errors-list filled" id="parsley-id-1">
								<li class="parsley-required">{error}</li>
							</ul>
						</div>',
						'labelOptions' => ['class' => 'control-label col-sm-2'],
						])->textInput(['maxlength' => 100, 'id' => 'menu-url', 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
					?>
					<?=$form->field($addMenuModel, 'menu_status', [
					'template' => '
					<div class="form-group" id="field_menu_status">
						{label}
						<div class="col-sm-8">
							<div class="checkbox">
								{input}
							</div>
							<ul class="parsley-errors-list filled" id="parsley-id-1">
								<li class="parsley-required">{error}</li>
							</ul>
						</div>
					</div>',
					'labelOptions' => ['class' => 'form-control col-md-8 col-xs-10'],
					])->checkbox(['class' => 'flat', 'uncheck' => '0', 'checked' => '1'])->label(Yii::t('form', 'Status'), ['class' => 'col-md-2 col-sm-2 col-xs-10 control-label']);?>
					
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
	<div class="row" id="edit-menu-container">
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
					<h2><?= Yii::t('form', 'Edit and delete menu'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content" id="edit-menu-form-wrapper">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'edit-menu-form',
					'action' => 'edit-menu',
					'enableAjaxValidation' => true,
					'enableClientValidation' => false,
				]]); 
				?>
                <?= $form->field($editMenuModel, 'edit_menu_name', [
					'template' => '<div class="form-group" id="field_edit_menu_category_name">
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
							<ul class="parsley-errors-list filled" id="parsley-id-1">
								<li class="parsley-required">{error}</li>
							</ul>
                        </div>
					</div>',
					'labelOptions' => ['class' => 'col-sm-2 control-label'],
				])->dropDownList($menuList, ['id'=>'menu-list', 'prompt'=>'Выбрать']); ?>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="create-content-container">
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
					<h2><?= Yii::t('form', 'Create content'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content" id="create-content-form-wrapper">
                <br />
                <?php $form = ActiveForm::begin(['options' => [
					'class' => 'form-horizontal form-label-left',
					'id' => 'create-content-form',
					'action' => 'create-content',
					'enableAjaxValidation' => true,
					'enableClientValidation' => false,
				]]); 
				?>
				<?= $form->field($contentModel, 'menu_id', [
					'template' => '
					<div class="form-group" id="field_menu_id">
						{label}
						<div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-2">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->dropDownList($menuList, ['id'=>'menu-content-list', 'prompt'=>'Выбрать']);
				?>
				<?= $form->field($contentModel, 'title', [
					'template' => '
					<div class="form-group" id="field_title">
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
				<?= $form->field($contentModel, 'content', [
					'template' => '
					<div class="form-group" id="field_content">
						{label}
						<div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->widget(CKEditor::className(),[
					'editorOptions' => [
						'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
						'inline' => false, //по умолчанию false
						'language' => 'ru',
						'extraPlugins' => 'image',
						'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
					],
				]); 
				?>
				<?= $form->field($contentModel, 'meta_title', [
					'template' => '
					<div class="form-group" id="field_title">
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
				<?= $form->field($contentModel, 'meta_description', [
					'template' => '
					<div class="form-group" id="field_description">
						{label}
						<div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textArea(['rows' => '6', 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<?= $form->field($contentModel, 'meta_keywords', [
					'template' => '
					<div class="form-group" id="field_meta_keywords">
						{label}
						<div class="col-sm-8">
							{input}
						</div>
						<ul class="parsley-errors-list filled" id="parsley-id-1">
							<li class="parsley-required">{error}</li>
						</ul>
					</div>',
					'labelOptions' => ['class' => 'control-label col-sm-2'],
					])->textArea(['rows' => '6', 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']); 
				?>
				<div class="form-group" style="margin-bottom:20px;">
					<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-2">
						<?= FileUpload::widget([
							'model' => $contentModel,
							'attribute' => 'image',
							'url' => ['/slider/image-upload', 'id' => $contentModel->id, 'category' => $category, 'upload_type' => 'tmp'], // your url, this is just for demo purposes,
							'options' => ['accept' => 'image/*'],
							'clientOptions' => [
								'maxFileSize' => 2000000
							],
							// Also, you can specify jQuery-File-Upload events
							// see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
							'clientEvents' => [
								'fileuploaddone' => 'function(e, data) 
								{
									var item_id = ($("div.file_wrap").length > 0) ? $("div.file_wrap").length : 0;
									file_upload(data, item_id);
								}',
								'fileuploadfail' => 'function(e, data) 
								{
									console.log(e);
									console.log(data);
								}',
							],
						]); ?>
						<?= \yii\base\View::render(
						'//../../common/modules/uploads/views/file-list/list-file',
							[
								'category'=>$category,
								'id'=>0,
								'thumbnail'=>$thumbnail = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [],
								'tmp'=>true,
							]
						);
						?>
					</div>
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
