<?php
use frontend\assets\BackOfficeAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;
use common\modules\tickets\widgets\TicketsListWidget;
use common\modules\seo\widgets\CounterWidget;

/* @var $this \yii\web\View */
/* @var $content string */

BackOfficeAppAsset::register($this);
$bundle = BackOfficeAppAsset::register($this);

$frontendMenu = new FrontendMenu();
$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$ticketsList = (isset($this->params['tickets_list'])) ? $this->params['tickets_list'] : '';
$ticketsCount = (isset($this->params['tickets_count'])) ? $this->params['tickets_count'] : 0;
$ticketsMesagesCount = (isset($this->params['tickets_mesages_count'])) ? $this->params['tickets_mesages_count'] : 0;

$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/dist/js/dropdown-bootstrap-extended.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/dist/js/jquery.slimscroll.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/vendors/bower_components/owl.carousel/dist/owl.carousel.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/vendors/bower_components/switchery/dist/switchery.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/dist/js/init.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="<?= Yii::$app->charset ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?= Html::encode(Yii::t('menu', 'Личный кабинет')) ?></title>
		<?php $this->head() ?>
	</head>
	<body>
    <?php $this->beginBody() ?>
		<?php include_once("analyticstracking.php") ?>
		<div class="wrapper theme-1-active pimary-color-red">
			<!-- Top Menu Items -->
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="mobile-only-brand pull-left">
					<div class="nav-header pull-left">
						<div class="logo-wrap">
							<a href="index.html">
								<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'logo.png'), ['alt'=>'brand']) ?>
								<span class="brand-text"><?= Html::encode(Yii::t('menu', 'Личный кабинет')) ?></span>
							</a>
						</div>
					</div>	
					<a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu"></i></a>
				</div>
				<div id="mobile_only_nav" class="mobile-only-nav pull-right">
					<ul class="nav navbar-right top-nav pull-right">
						<li class="dropdown alert-drp">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="zmdi zmdi-notifications top-nav-icon"></i><span class="top-nav-icon-badge"><?= $ticketsCount; ?></span></a>
							<ul  class="dropdown-menu alert-dropdown" data-dropdown-in="bounceIn" data-dropdown-out="bounceOut">
								<li>
									<div class="notification-box-head-wrap">
										<span class="notification-box-head pull-left inline-block"><?= Yii::t('menu', 'Сообщения'); ?></span>
										<div class="clearfix"></div>
										<hr class="light-grey-hr ma-0"/>
									</div>
								</li>
								<li>
									<div class="streamline message-nicescroll-bar">
										<?= TicketsListWidget::widget([
											'item_list' => $ticketsList,
										]);
										?>
									</div>
								</li>
								<li>
									<div class="notification-box-bottom-wrap">
										<hr class="light-grey-hr ma-0"/>
										<?= Html::a (Yii::t('menu', 'Все сообщения'), \Yii::$app->request->BaseUrl.'/tickets', $options = ['class'=>'block text-center read-all']); ?>
										<div class="clearfix"></div>
									</div>
								</li>
							</ul>
						</li>
						<li class="dropdown auth-drp">
							<a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown">
								<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'user1.png'), ['class'=>'user-auth-img img-circle']) ?>
							<span class="user-online-status"></span></a>
							<ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
								<li>
									<?= Html::a ('<i class="zmdi zmdi-account"></i><span>'.Yii::t('menu', 'Профиль').'</span>', \Yii::$app->request->BaseUrl.'/partners/profile/'.\Yii::$app->user->identity->id, $options = []); ?>
								</li>
								<li class="divider"></li>
								<li>
									<?= Html::a ('<i class="zmdi zmdi-power"></i><span>'.Yii::t('menu', 'Выход').'</span>', \Yii::$app->request->BaseUrl.'/logout', $options = []); ?>
								</li>
							</ul>
						</li>
					</ul>
				</div>	
			</nav>
			<!-- /Top Menu Items -->
			<!-- Left Sidebar Menu -->
			<div class="fixed-sidebar-left">
				<?= MenuWidget::widget([
					'items' => FrontendMenu::getMenuList(1, true),
						'options' => [
							'class'=>'nav navbar-nav side-nav nicescroll-bar'
						],
					]);
				?>
			</div>
			<!-- /Left Sidebar Menu -->
			<!-- Main Content -->
			<div class="page-wrapper">
				<div class="container-fluid">
					<!-- Title -->
					<div class="row heading-bg">
						<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
						  <h5 class="txt-dark"><?= $title; ?></h5>
						</div>
					</div>
					<!-- /Title -->
					<?= $content ?>
				</div>
				<!-- Footer -->
				<footer class="footer container-fluid pl-30 pr-30">
					<div class="counter">
						<?= CounterWidget::widget([
							'counter_name' => 'liveinternet',
						]); ?>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<p><?= date("Y"); ?> &copy; Gold Estafeta</p>
						</div>
					</div>
				</footer>
				<!-- /Footer -->
			</div>
			<!-- /Main Content -->
		</div>
		<!-- /#wrapper -->
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
