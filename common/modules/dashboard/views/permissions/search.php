<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\modules\dashboard\models\AdminUsers */

/* js */
$this->registerJsFile(Yii::getAlias('@modules').DIRECTORY_SEPARATOR.'dashboard'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'rbac.js', [
	'depends' => [\yii\web\JqueryAsset::className()]
]);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<div class="col-md-12">
	<div id="alert-success" class="alert alert-success alert-dismissible fade in hidden_element" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		<div class="alert_message"></div>
	</div>
	<div id="alert-danger" class="alert alert-danger alert-dismissible fade in hidden_element" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <div class="alert_message"></div>
    </div>
	<div class="x_panel">
		<div class="x_title">
			<h2><?= $title; ?></h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<div class="col-sm-12">
				<div class="nav navbar-right">
					<?= Html::a(Yii::t('form', 'Roles'), ['/rbac/roles'], ['id'=>'create-user', 'class' => 'btn btn-success']) ?>
					<?= Html::a(Yii::t('form', 'Create permissions'), ['create'], ['id'=>'create-user', 'class' => 'btn btn-success']) ?>
				</div>
			</div>
			<?php if(!empty($pagerCountList)): ?>
			<div class="col-sm-6">
				<div class="dataTables_length" id="datatable_length">
					<?= Yii::t('form', 'Show').Html::dropDownList('datatable_length', null, $pagerCountList, $options = [
							'id'=>'set-pager', 
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
						'role_name',
						'child',
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}{delete}',
						'buttons' => [
							'delete' => function ($url, $model) {
								return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
									'id' => $model->parent,
									'class' => 'delete-user',
									'onclick' => "
										if(confirm('".Yii::t('form', 'Are you sure you want to delete this item?')."')) 
										{
											id = $(this).attr('id');
											url = 'delete-user';
													
											delete_user(url, id);
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
