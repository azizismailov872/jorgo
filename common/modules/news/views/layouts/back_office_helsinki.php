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
	<body>
    <?php $this->beginBody() ?>
    <?php include_once("analyticstracking.php") ?>
    <div class="wrap">
		<!-- page HEADER -->
		<!-- ========================================================= -->
		<div class="page-header">
			<!-- LEFTSIDE header -->
			<div class="leftside-header">
				<div class="logo">
					<a href="/" class="on-click">
						<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'header-logo.png'), ['alt'=>'logo', '']) ?>
					</a>
				</div>
				<div id="menu-toggle" class="visible-xs toggle-left-sidebar" data-toggle-class="left-sidebar-open" data-target="html">
					<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
				</div>
			</div>
			<!-- RIGHTSIDE header -->
			<div class="rightside-header">
				<div class="header-middle"></div>
				<!--NOCITE HEADERBOX-->
				<div class="header-section hidden-xs" id="notice-headerbox">
					<!--messages-->
					<div class="notice" id="messages-notice">
						<i class="fa fa-comments-o" aria-hidden="true"><span class="badge x-danger b-rounded"><?= $ticketsMesagesCount; ?></span></span></i>
						<div class="dropdown-box basic">
							<div class="drop-header ">
								<h3><i class="fa fa-comments" aria-hidden="true"></i> <?= Yii::t('menu', 'Сообщения'); ?></h3>
								<span class="badge x-danger b-rounded"><?= $ticketsCount; ?></span>
							</div>
							<div class="drop-content">
								<div class="widget-list list-left-element">
									<?= TicketsListWidget::widget([
										'item_list' => $ticketsList,
									]);
									?>
								</div>
							</div>
							<div class="drop-footer">
								<?= Html::a (Yii::t('menu', 'Все сообщения'), \Yii::$app->request->BaseUrl.'/tickets', $options = []); ?>
							</div>
						</div>
					</div>
				</div>
				<!--USER HEADERBOX -->
				<div class="header-section" id="user-headerbox">
					<div class="user-header-wrap">
						<div class="user-photo">
							<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'helsinki.jpg'), ['alt'=>'profile photo', '']) ?>
						</div>
						<div class="user-info">
							<?php if(Yii::$app->user->identity !== null): ?>
							<span class="user-name"><?= (Yii::$app->user->identity->first_name != '') ? Yii::$app->user->identity->first_name : ''?></span>
							<?php endif; ?>
						</div>
						<i class="fa fa-plus icon-open" aria-hidden="true"></i>
						<i class="fa fa-minus icon-close" aria-hidden="true"></i>
					</div>
					<div class="user-options dropdown-box">
						<div class="drop-content basic">
							<?php if(Yii::$app->user->identity !== null): ?>
							<ul>
								<li> 
									<?= Html::a ('<i class="fa fa-user" aria-hidden="true"></i>'.Yii::t('menu', 'Профиль'), \Yii::$app->request->BaseUrl.'/partners/profile/'.\Yii::$app->user->identity->id, $options = []); ?>
								<li> 
								<li> 
									<?= Html::a ('<i class="fa fa-lock" aria-hidden="true"></i>'.Yii::t('menu', 'Выход'), \Yii::$app->request->BaseUrl.'/logout', $options = []); ?>
								<li>
							</ul>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="header-separator"></div>
				<!--Log out -->
				<div class="header-section">
					<?= Html::a ('<i class="fa fa-sign-out log-out" aria-hidden="true"></i>', \Yii::$app->request->BaseUrl.'/logout', $options = [
						'data-toggle'=>'tooltip',
						'data-placement'=>'left',
						'title'=>'Logout',
					]); ?>
				</div>
			</div>
		</div>
		<!-- page BODY -->
		<!-- ========================================================= -->
		<div class="page-body">
			<!-- LEFT SIDEBAR -->
			<!-- ========================================================= -->
			<div class="left-sidebar">
				<!-- left sidebar HEADER -->
				<div class="left-sidebar-header">
					<div class="left-sidebar-title"><?= Html::encode(Yii::t('menu', 'Меню')) ?></div>
					<div class="left-sidebar-toggle c-hamburger c-hamburger--htla hidden-xs" data-toggle-class="left-sidebar-collapsed" data-target="html">
						<span></span>
					</div>
				</div>
				<!-- NAVIGATION -->
				<!-- ========================================================= -->
				<div id="left-nav" class="nano has-scrollbar">
					<div class="nano">
						<nav>
						<?= MenuWidget::widget([
							'items' => FrontendMenu::getMenuList(1, true),
							'options' => [
								'id'=>'main-nav',
								'class'=>'nav'
							],
							'submenuTemplate' => '<ul class="nav child-nav level-1">{items}</ul>',
						]);
						?>
						</nav>
					</div>
				</div>
			</div>
			<!-- CONTENT -->
			<!-- ========================================================= -->
			<div class="content">
			<!-- content HEADER -->
				<!-- ========================================================= -->
				<div class="content-header mb-xl">
					<!-- leftside content header -->
					<div class="leftside-content-header">
						<ul class="breadcrumbs">
							<li><i class="fa fa-copy" aria-hidden="true"></i><a href="#"><?= $title; ?></a></li>
						</ul>
					</div>
				</div>
				<!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
				<div class="animated fadeInUp">
					<!--content-->
					<div class="row">
						<?= $content ?>
					</div>
				</div>
            <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
			</div>
		</div>
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
