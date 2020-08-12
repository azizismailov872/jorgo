<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

$blockTitle = (isset($this->params['block_title'])) ? $this->params['block_title'] : '';
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
