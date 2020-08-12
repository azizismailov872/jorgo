<?php
use backend\themes\gentella\assets\GentellaAppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\modules\dashboard\models\AdminMenu;

/* @var $this \yii\web\View */
/* @var $content string */
GentellaAppAsset::register($this);

$title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?= Html::csrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
	</head>
	<body class="nav-md">
	<?php $this->beginBody() ?>
	<div class="container body">
		<div class="main_container">
			<div class="col-md-3 left_col">
				<div class="left_col scroll-view">
					<div class="navbar nav_title" style="border: 0;">
						<a href="index.html" class="site_title">
							<i class="fa fa-paw"></i> 
							<span><?= Yii::t('menu', 'Dashboard'); ?>!</span>
						</a>
					</div>
					<div class="clearfix"></div>
					<!-- menu profile quick info -->
					<div class="profile clearfix">
						<div class="profile_pic">
							<?= Html::img(Url::to('@dashboard_images'.DIRECTORY_SEPARATOR.'img.jpg'), ['alt'=>'', 'class'=>'img-circle profile_img']) ?>
						</div>
						<div class="profile_info">
							<span><?= Yii::t('menu', 'Welcome'); ?>,</span>
							<h2><?= ((\Yii::$app->user->identity !== null) ? \Yii::$app->user->identity->username : ''); ?></h2>
						</div>
					</div>
					<!-- /menu profile quick info -->
					<br/>
					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<h3><?= Yii::t('menu', 'Menu'); ?></h3>
							<?= MenuWidget::widget([
									'items' => AdminMenu::getAdminMenuList(),
									'options' => [
										'class'=>'nav side-menu'
									],
									'submenuTemplate' => '<ul class="nav child_menu">{items}</ul>',
								]);
							?>
						</div>
					</div>
					<!-- /sidebar menu -->
					<!-- /menu footer buttons -->
					<div class="sidebar-footer hidden-small">
						  <a data-toggle="tooltip" data-placement="top" title="Settings">
							<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
						  </a>
						  <a data-toggle="tooltip" data-placement="top" title="FullScreen">
							<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
						  </a>
						  <a data-toggle="tooltip" data-placement="top" title="Lock">
							<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
						  </a>
						  <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
							<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
						  </a>
					</div>
					<!-- /menu footer buttons -->
				</div>
			</div>
			<!-- top navigation -->
			<div class="top_nav">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle"><i class="fa fa-bars"></i></a>
						</div>
						<ul class="nav navbar-nav navbar-right">
							<li class="">
								<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?= Html::img(Url::to('@dashboard_images'.DIRECTORY_SEPARATOR.'img.jpg'), ['alt'=>'', 'class'=>'']) ?>
									<?= ((\Yii::$app->user->identity !== null) ? \Yii::$app->user->identity->username : ''); ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><?= Html::a (Yii::t('menu', 'Profile'), (\Yii::$app->request->BaseUrl.'/users/profile/'.((\Yii::$app->user->identity !== null) ? \Yii::$app->user->identity->id : '')), $options = []); ?></li>
									<li><?= Html::a ('<i class="fa fa-sign-out pull-right"></i>'.Yii::t('menu', 'Logout'), \Yii::$app->request->BaseUrl.'/logout', $options = []); ?></li>
								</ul>
							</li>
						</ul>
					</nav>
				</div>
			</div>
			<!-- /top navigation -->
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3><?= $title; ?></h3>
						</div>
						<div class="clearfix"></div>
						<?= $content; ?>
					</div>
				</div>
			</div>
			<!-- /page content -->
			<!-- footer content -->
			<footer>
				<div class="pull-right"></div>
				<div class="clearfix"></div>
			</footer>
			<!-- /footer content -->
		</div>
    </div>
	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
