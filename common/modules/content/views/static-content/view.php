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
							<th><?= Yii::t('form', 'Name'); ?></th>
							<td><?= $model->name; ?></td>
						</tr>
                        <tr>
							<th><?= Yii::t('form', 'Content'); ?></th>
							<td><?= $model->content; ?></td>
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
