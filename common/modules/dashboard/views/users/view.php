<?php
use yii\helpers\Html;
use yii\helpers\Url;

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?= $title; ?></h2>
                <div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
					<div class="profile_img">
						<div id="crop-avatar">
							<!-- Current avatar -->
							<?= Html::img(Url::to('@dashboard_images'.DIRECTORY_SEPARATOR.'picture.jpg'), ['alt'=>'Avatar', 'title'=>'Change the avatar', 'class'=>'img-responsive avatar-view']) ?>
						</div>
					</div>
					<h3><?= $model->username; ?></h3>
						<ul class="list-unstyled user_data">
							<li>
								<i class="glyphicon glyphicon-user"></i>&nbsp;<?= Yii::t('form', 'Status').' - '.(isset($statusList[$model->status]) ? $statusList[$model->status] : ''); ?>
							</li>
							<li>
								<i class="glyphicon glyphicon-user"></i>&nbsp;<?= Yii::t('form', 'Group name').' - '.$model->group->name; ?>
							</li>
							<li>
								<i class="glyphicon glyphicon-user"></i>&nbsp;<?= Yii::t('form', 'Created').' - '.date("Y-m-d H:i:s", $model->created_at); ?>
							</li>
                      </ul>
                      <?= Html::a('<i class="fa fa-edit m-right-xs" style="margin-right:5px;"></i>'.Yii::t('form', 'Edit Profile'), ['update', 'id'=>$model->id], ['id'=>'create-user', 'class' => 'btn btn-success']) ?>
                      <br />
				</div>
				<div class="col-md-9 col-sm-9 col-xs-12">123</div>
			</div>
		</div>
	</div>
</div>
