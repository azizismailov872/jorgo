<?php
use yii\helpers\Html;
use yii\helpers\Url;

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<div class="row">
	<div class="col-md-8s col-sm-8 col-xs-12">
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
							<td><?= (isset($permissions['name'])) ? $permissions['name'] : '' ?></td>
						</tr>
                        <tr>
							<th><?= Yii::t('form', 'Description'); ?></th>
							<td><?= (isset($permissions['description'])) ? $permissions['description'] : '' ?></td>
                        </tr>
                        <tr>
							<th><?= Yii::t('form', 'Created at'); ?></th>
							<td><?= (isset($permissions['createdAt'])) ? date("Y-m-d H:i:s", $permissions['createdAt']) : '' ?></td>
                        </tr>
                        <tr>
							<th><?= Yii::t('form', 'Update at'); ?></th>
							<td><?= (isset($permissions['updatedAt'])) ? date("Y-m-d H:i:s", $permissions['updatedAt']) : '' ?></td>
                        </tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
