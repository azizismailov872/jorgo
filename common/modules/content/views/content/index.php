<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\content\models\StaticContent */

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'content.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<div class="row" id="index-wrapper">
	<div class="row" id="filters">
		<?php if(Yii::$app->session->hasFlash('success')): ?>
		<div id="alert-success" class="alert alert-success alert-dismissible fade in <?= (Yii::$app->session->hasFlash('success')) ? '' : 'hidden_element'; ?>" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<div class="alert_message"><?= (Yii::$app->session->hasFlash('success')) ? Html::encode(Yii::$app->session->getFlash('success')) : ''; ?></div>
		</div>
		<?php endif; ?>
		<?php if(Yii::$app->session->hasFlash('error')): ?>
		<div id="alert-danger" class="alert alert-danger alert-dismissible fade in <?= (Yii::$app->session->hasFlash('error')) ? '' : 'hidden_element'; ?>" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <div class="alert_message"><?= (Yii::$app->session->hasFlash('error')) ? Html::encode(Yii::$app->session->getFlash('error')) : ''; ?></div>
		</div>
		<?php endif; ?>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><?= Yii::t('form', 'Filters'); ?></h2>
                    <div class="clearfix"></div>
				</div>
                <div class="x_content">
				<?php $form = ActiveForm::begin([
					'action'=>['index'],
					'method' => 'get',
					'options' => [
						'id' => 'filters-form',
						'enableClientValidation' => false,
					],
				]);?>
				<div class="col-md-12">
					<?= $form->field($searchModel, 'id', [
						'template' => '<div class="form-group">
							{label}
							<div class="col-md-5">
								{input}
								<ul class="parsley-errors-list filled" id="parsley-id-1">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>
						</div>',
						'labelOptions' => ['class' => 'control-label col-md-1'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-3 col-xs-12']); ?>
					<?= $form->field($searchModel, 'title', [
						'template' => '<div class="form-group">
							{label}
							<div class="col-md-5">
								{input}
								<ul class="parsley-errors-list filled" id="parsley-id-1">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>
						</div>',
						'labelOptions' => ['class' => 'control-label col-md-1'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-3 col-xs-12']); ?>
				</div>
				<div class="col-md-12">
					<?= $form->field($searchModel, 'url', [
						'template' => '<div class="form-group">
							{label}
							<div class="col-md-5">
								{input}
								<ul class="parsley-errors-list filled" id="parsley-id-2">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>
						</div>',
						'labelOptions' => ['class' => 'control-label col-md-1'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-5 col-xs-12']); ?>
					<?= $form->field($searchModel, 'status', [
						'template' => '<div class="form-group">
							{label}
							<div class="col-md-5">
								{input}
								<ul class="parsley-errors-list filled" id="parsley-id-1">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>
						</div>',
						'labelOptions' => ['class' => 'control-label col-md-1'],
					])->dropDownList($statusList, ['id'=>'status-list', 'prompt'=>Yii::t('form', 'Select'), 'class' => 'form-control col-md-5 col-xs-12']); ?>
				</div>
				<div class="col-md-12">
					<?= $form->field($searchModel, 'date_from', [
					'inputOptions' => ['placeholder'=> 'Дата от'],
					'labelOptions' => ['class' => 'control-label col-md-1'],
					'template' => '<div class="form-group">
						{label}
						<div class="col-md-5">
							{input}
							<ul class="parsley-errors-list filled" id="parsley-id-2">
								<li class="parsley-required">{error}</li>
							</ul>
						</div>
					</div>',
					])->widget(\yii\jui\DatePicker::className(), [
						'language' => 'ru',
						'dateFormat' => 'dd-MM-yyyy',
						'options' => ['style'=>'width:200px;'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-5 col-xs-12', 'placeholder' => 'Дата от']); ?>
					<?= $form->field($searchModel, 'date_to', [
					'inputOptions' => ['placeholder'=> 'Дата до'],
					'labelOptions' => ['class' => 'control-label col-md-1'],
					'template' => '<div class="form-group">
						{label}
						<div class="col-md-5">
							{input}
							<ul class="parsley-errors-list filled" id="parsley-id-2">
								<li class="parsley-required">{error}</li>
							</ul>
						</div>
					</div>',
					])->widget(\yii\jui\DatePicker::className(), [
						'language' => 'ru',
						'dateFormat' => 'dd-MM-yyyy',
						'options' => ['style'=>'width:200px;'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-5 col-xs-12', 'placeholder' => 'Дата до']); ?>
				</div>
				<div class="col-md-12">
					<div class="ln_solid"></div>
					<div class="form-group" style="float:left; margin-left:110px;">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">
							<?= Html::submitButton(Yii::t('form', 'Search'), ['class' => 'btn btn-primary']); ?>
						</div>
					</div>
					<div class="form-group" style="float:left">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">
							<?= Html::submitButton(Yii::t('form', 'Reset'), ['id' => 'reset-form', 'class' => 'btn btn-success']); ?>
						</div>
					</div>
                </div>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="container">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><?= $title; ?></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="col-sm-12">
						<div class="nav navbar-right">
							<?= Html::a(Yii::t('form', 'Create'), ['create'], ['id'=>'create', 'class' => 'btn btn-success']) ?>
						</div>
						<div class="row">
						<div class="col-md-12">
						<?php Pjax::begin([
							'enablePushState' => false, // to disable push state
							'enableReplaceState' => false // to disable replace state
						]); ?>
						<?= GridView::widget([
							'dataProvider' => $dataProvider,
							//'filterModel' => $searchModel,
							'id'=>'datatable',
							'class'=>'table table-striped table-bordered',
							'layout'=>"{items}\n{pager}",
							'pager' => [
								//'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
								'firstPageLabel'=>Yii::t('form', 'Первый'),   // Set the label for the "first" page button
								'lastPageLabel'=>Yii::t('form', 'Последний'),    // Set the label for the "last" page button
								'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
								'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
								'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
								//'options' => ['class' => 'dataTables_paginate paging_simple_numbers'],
							],
							'columns' => [
								//['class' => 'yii\grid\SerialColumn'],
								'id',
								[
									'attribute'=>'menu',
									'format'=>'text',//raw, html
									'content'=>function($model)
									{
										return $model->menu->name;
									}
								],
								[
									'attribute'=>'url',
									'format'=>'text',//raw, html
									'content'=>function($model)
									{
										return $model->menu->url;
									}
								],
								'title',
								[
									'attribute' => 'created_at', 
									'label' => Yii::t('form', 'Register date'),
									'format' => ['date', 'php:Y-m-d H:m:s'],
									'filter'=>false,
								],
								[
									'class' => 'yii\grid\ActionColumn',
									'template' => '{status} {view} {update} {delete}',
									'buttons' => [
										'delete' => function ($url, $model) {
											return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
												'id' => $model->id,
												'class' => 'delete-static',
												'onclick' => "
													if(confirm('".Yii::t('form', 'Are you sure you want to delete this item?')."')) 
													{
														id = $(this).attr('id');
														data = {id:id};
														url = 'delete-content';
														alert_id = 'container';
														result_id = 'datatable';
														
														make_action(url, data, alert_id, result_id, true);
													}
													return false;
												",
											]);
										},
										'status' => function ($url, $model) {
											return Html::a('<span class="glyphicon glyphicon-'.(($model->status > 0) ? 'download' : 'upload').'"></span>', '#', [
												'id' => $model->id,
												'item_id' => $model->id,
												'onclick' => "
													if(confirm('".Yii::t('form', 'Are you sure you want to set status?')."')) 
													{
														var id = $(this).attr('id');
														set_status(id);
													}
													return false;
												",
											]);
										}
									],
								],
							],
						]); ?>
						<?php Pjax::end(); ?>	
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
