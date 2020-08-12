<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use common\models\Service;

/* @var $this yii\web\View */
/* @var $model common\modules\dashboard\models\AdminUsers */

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'dashboard'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'user.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$blockTitle = (isset($this->params['block_title'])) ? $this->params['block_title'] : '';
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
							<div class="col-md-3">
								{input}
								<ul class="parsley-errors-list filled" id="parsley-id-1">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>
						</div>',
						'labelOptions' => ['class' => 'control-label col-md-1'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-3 col-xs-12']); ?>
					<?= $form->field($searchModel, 'username', [
						'template' => '<div class="form-group">
							{label}
							<div class="col-md-3">
								{input}
								<ul class="parsley-errors-list filled" id="parsley-id-1">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>
						</div>',
						'labelOptions' => ['class' => 'control-label col-md-1'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-3 col-xs-12']); ?>
					<?= $form->field($searchModel, 'email', [
						'template' => '<div class="form-group">
							{label}
							<div class="col-md-3">
								{input}
								<ul class="parsley-errors-list filled" id="parsley-id-2">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>
						</div>',
						'labelOptions' => ['class' => 'control-label col-md-1'],
					])->textInput(['maxlength' => 100, 'class' => 'form-control col-md-3 col-xs-12']); ?>
				</div>
				<div class="col-md-12">
					<?= $form->field($searchModel, 'group_id', [
						'template' => '<div class="form-group">
							{label}
							<div class="col-md-5">
								{input}
								<ul class="parsley-errors-list filled" id="parsley-id-3">
									<li class="parsley-required">{error}</li>
								</ul>
							</div>
						</div>',
						'labelOptions' => ['class' => 'control-label col-md-1'],
					])->dropDownList($usersGroupsList, ['id'=>'users-groups', 'prompt'=>Yii::t('form', 'Select'), 'class' => 'form-control col-md-5 col-xs-12']); ?>
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
					<h2><?= $blockTitle; ?></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="col-sm-12">
						<div class="nav navbar-right">
							<?= Html::a(Yii::t('form', 'Create user'), ['create'], ['id'=>'create-user', 'class' => 'btn btn-success']) ?>
						</div>
					</div>
					<?php if(!empty($pagerCountList)): ?>
					<div class="col-sm-6">
						<div class="dataTables_length" id="datatable_length">
							<?= Yii::t('form', 'Show').Html::dropDownList('datatable_length', null, $pagerCountList, $options = [
									'id'=>'set-pager', 
									'index'=>'admin-users',
									'url'=>'users',
									'class'=>'form-control input-sm',
									'options' =>[
										$pagerCount =>['Selected'=>true]
									]
								]).Yii::t('form', 'entries'); 
							?>
						</div>
					</div>
					<?php endif; ?>
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
							'firstPageLabel'=>Yii::t('form', 'First'),   // Set the label for the "first" page button
							'lastPageLabel'=>Yii::t('form', 'Last'),    // Set the label for the "last" page button
							'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
							'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
							'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
							//'options' => ['class' => 'dataTables_paginate paging_simple_numbers'],
						],
						'columns' => [
							//['class' => 'yii\grid\SerialColumn'],
							'id',
							[
								'attribute'=>'group_id',
								'label'=>Yii::t('form', 'Group name'),
								'format'=>'text', // Возможные варианты: raw, html
								'content'=>function($data){
									return $data->group->name;
								},
							],
							'username',
							'email',
							[
								'attribute'=>'status',
								'format'=>'text',//raw, html
								'content'=>function($model) use ($statusList)
								{
									return isset($statusList[$model->status]) ? $statusList[$model->status] : '';
								}
							],
							[
								'attribute' => 'created_at', 
								'label' => Yii::t('form', 'Register date'),
								'format' => ['date', 'php:Y-m-d H:m:s'],
								'filter'=>false,
							],
							[
								'class' => 'yii\grid\ActionColumn',
								'template' => '{view}{update}{delete}',
								'buttons' => [
									'delete' => function ($url, $model) {
										return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
											'id' => $model->id,
											'class' => 'delete-user',
											'onclick' => "
												if(confirm('".Yii::t('form', 'Are you sure you want to delete this item?')."')) 
												{
													id = $(this).attr('id');
													data = {id:id};
													url = 'users/delete-user';
													alert_id = 'container';
													result_id = 'datatable';
													
													make_action(url, data, alert_id, result_id, true);
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


