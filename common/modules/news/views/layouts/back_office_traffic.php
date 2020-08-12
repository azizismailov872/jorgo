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
		<title><?= Html::encode(Yii::t('menu', 'Личный кабинет')) ?></title>
		<?php $this->head() ?>
	</head>
	<body>
    <?php $this->beginBody() ?>
		<?php include_once("analyticstracking.php") ?>
		<div id="wrapper">
			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<?= MenuWidget::widget([
						'items' => FrontendMenu::getMenuList(1, true),
							'options' => [
								'class'=>'nav metismenu',
								'id'=>'side-menu'
							],
						]);
					?>
				</div>
			</nav>
			<div id="page-wrapper" class="gray-bg">
				 <div class="row border-bottom">
					<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
						<div class="navbar-header">
							<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i></a>
						</div>
						<ul class="nav navbar-top-links navbar-right">
							<li class="dropdown">
								<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
									<i class="fa fa-envelope"></i>  <span class="label label-warning"><?= $ticketsCount; ?></span>
								</a>
								<ul class="dropdown-menu dropdown-messages">
									<?= TicketsListWidget::widget([
										'item_list' => $ticketsList,
									]);
									?>
									<li>
										<div class="text-center link-block">
											<?= Html::a ('<i class="fa fa-envelope"></i><strong>&nbsp;'.Yii::t('menu', 'Все сообщения').'</strong>', \Yii::$app->request->BaseUrl.'/tickets', $options = []); ?>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<?= Html::a ('<i class="fa fa-sign-out"></i>'.Yii::t('menu', 'Выход').'</span>', \Yii::$app->request->BaseUrl.'/logout', $options = []); ?>
							</li>
						</ul>
					</nav>
				</div>
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-sm-4">
						<h2><?= $title; ?></h2>
					</div>
				</div>
				<div class="wrapper wrapper-content animated fadeIn">
					<?= $content ?>
				</div>
				<div class="footer">
					<div class="counter">
						<?= CounterWidget::widget([
							'counter_name' => 'liveinternet',
						]); ?>
					</div>
					<div>
						<p>Copyright&nbsp;<?= date("Y"); ?> &copy; <?= Yii::$app->name; ?></p>
					</div>
				</div>
			</div>
        </div>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
