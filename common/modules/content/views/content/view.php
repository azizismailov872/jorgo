<?php
use yii\helpers\Html;
use yii\helpers\Url;

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<div class="row">
	<div class="col-md-10s col-sm-10 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?= $title; ?></h2>
                <div class="clearfix"></div>
			</div>
			<div class="x_content">
				<table class="table table-striped">
					<tbody>
						<tr>
							<th><?= Yii::t('form', 'Menu'); ?></th>
							<td><?= $model->menu->name; ?></td>
						</tr>
						<tr>
							<th><?= Yii::t('form', 'Name'); ?></th>
							<td><?= $model->title; ?></td>
						</tr>
                        <tr>
							<th><?= Yii::t('form', 'Content'); ?></th>
							<td><?= $model->content; ?></td>
                        </tr>
                        <tr>
							<th><?= Yii::t('form', 'Status'); ?></th>
							<td><?= isset($statusList[$model->status]) ? $statusList[$model->status] : ''; ?></td>
                        </tr>
                        <tr>
							<th><?= Yii::t('form', 'Meta title'); ?></th>
							<td><?= $model->meta_title; ?></td>
                        </tr>
                        <tr>
							<th><?= Yii::t('form', 'Meta description'); ?></th>
							<td><?= $model->meta_description; ?></td>
                        </tr>
                        <tr>
							<th><?= Yii::t('form', 'Meta keywords'); ?></th>
							<td><?= $model->meta_keywords; ?></td>
                        </tr>
                        <tr>
							<th><?= Yii::t('form', 'Created at'); ?></th>
							<td><?= date("Y-m-d H:i:s", $model->created_at); ?></td>
                        </tr>
                        <tr>
							<th><?= Yii::t('form', 'Update at'); ?></th>
							<td><?= date("Y-m-d H:i:s", $model->updated_at); ?></td>
                        </tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
