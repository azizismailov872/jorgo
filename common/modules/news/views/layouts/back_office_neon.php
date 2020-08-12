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

$this->registerCssFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/wysihtml5/bootstrap-wysihtml5.css',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/selectboxit/jquery.selectBoxIt.css',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/gsap/TweenMax.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/bootstrap.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/resizeable.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/neon-api.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/neon-custom.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/backoffice/assets/js/neon-demo.js',['depends' => [\yii\web\JqueryAsset::className()]]);
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
		<link type="text/css" rel="stylesheet" href="#" id="skin_change"/>
		<title><?= Html::encode(Yii::t('menu', 'Личный кабинет')) ?></title>
		<?php $this->head() ?>
	</head>
	<body class="page-body" data-url="http://bitcoooin.com">
    <?php $this->beginBody() ?>
    <?php include_once("analyticstracking.php") ?>
    <div class="page-container">
		<!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
		<div class="sidebar-menu">
			<div class="sidebar-menu-inner">
				<header class="logo-env">
					<!-- logo -->
					<div class="logo">
						<a href="/">
							<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'logo@2x.png'), ['alt'=>'logo', 'width'=>'120px']) ?>
						</a>
					</div>
					<!-- logo collapse icon -->
					<div class="sidebar-collapse">
						<a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
							<i class="entypo-menu"></i>
						</a>
					</div>		
					<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
					<div class="sidebar-mobile-menu visible-xs">
						<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
							<i class="entypo-menu"></i>
						</a>
					</div>
				</header>			
				<?= MenuWidget::widget([
					'items' => FrontendMenu::getMenuList(1, true),
						'options' => [
							'id'=>'main-menu',
							'class'=>'main-menu'
						],
						'submenuTemplate' => '<ul>{items}</ul>',
					]);
				?>
			</div>
		</div>
		<div class="main-content">
			<div class="row">
				<!-- Profile Info and Notifications -->
				<div class="col-md-6 col-sm-8 clearfix">
					<ul class="user-info pull-left pull-none-xsm">
						<!-- Profile Info -->
						<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'thumb-1@2x.png'), ['alt'=>'', 'class'=>'img-circle', 'width'=>'44px']) ?>
								<?php if(Yii::$app->user->identity !== null): ?>
								<?= (Yii::$app->user->identity->first_name != '') ? Yii::$app->user->identity->first_name : ''?>
								<?php endif; ?>
							</a>
							<ul class="dropdown-menu">
								<!-- Reverse Caret -->
								<li class="caret"></li>
								<!-- Profile sub-links -->
								<li>
									<?= Html::a ('<i class="entypo-user"></i>'.Yii::t('menu', 'Профиль'), \Yii::$app->request->BaseUrl.'/partners/profile/'.\Yii::$app->user->identity->id, $options = []); ?>
								</li>
							</ul>
						</li>
					</ul>
					<ul class="user-info pull-left pull-right-xs pull-none-xsm">
						<!-- Message Notifications -->
						<li class="notifications dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								<i class="entypo-mail"></i>
								<span class="badge badge-secondary"><?= $ticketsCount; ?></span>
							</a>
							<ul class="dropdown-menu">
								<li>
								<?= TicketsListWidget::widget([
									'item_list' => $ticketsList,
								]);
								?>
								</li>
								<li class="external">
									<?= Html::a (Yii::t('menu', 'Все сообщения'), \Yii::$app->request->BaseUrl.'/tickets', $options = []); ?>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<!-- Raw Links -->
				<div class="col-md-6 col-sm-4 clearfix hidden-xs">
					<ul class="list-inline links-list pull-right">
						<li class="sep"></li>
						<li>
							<?= Html::a (Yii::t('menu', 'Выход').'&nbsp;<i class="entypo-logout right"></i>', \Yii::$app->request->BaseUrl.'/logout', $options = []); ?>
						</li>
					</ul>
				</div>
			</div>
		<hr/>
		<?= $content ?>
		<!-- Footer -->
		<footer class="main">
			&copy; <?= date("Y"); ?> <strong>Bitcoooin</strong>
		</footer>
	</div>
	<div class="counter">
		<?= CounterWidget::widget([
			'counter_name' => 'liveinternet',
		]); ?>
	</div>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
