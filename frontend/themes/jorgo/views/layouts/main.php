<?php
use frontend\assets\JorgoAppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Menu as MenuWidget;
use common\modules\content\models\Menu as FrontendMenu;

/* @var $this \yii\web\View */
/* @var $content string */

JorgoAppAsset::register($this);
$bundle = JorgoAppAsset::register($this);

$staticContent = (isset($this->params['staticContent'])) ? $this->params['staticContent'] :[];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
	<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
	<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
	<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
	<head>
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<link rel="icon" href="<?= Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'favicon.ico'); ?>" type="image/x-icon">
		<title><?= $this->title; ?></title>
		<?php $this->head() ?>
	</head>
	<body>
		<?php $this->beginBody(); ?>
		<header>
			<div class="container">
				<div class="header-in">
					<h1 class="logo">
						<?= Html::a(Html::img(Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'logo.png'), ['alt'=> 'logo', 'title'=>'logo']), '/', []) ?>
					</h1>
					<div class="nav-wrap">
						<nav class="navbar navbar-expand-lg navbar-light bg-light">
							<a class="navbar-brand" href="#"></a>
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"><i class="fas fa-caret-left"></i></span>
							</button>
							<div class="collapse navbar-collapse" id="navbarNav">
							<!-- #menu -->
							<?= MenuWidget::widget([
								'items' => FrontendMenu::formMenuList('top-menu'),
								'options' => [
									'class' => 'navbar-nav menu',
								],
								'firstItemCssClass'=>'nav-item active',
								'lastItemCssClass' =>'nav-item',
								'linkTemplate' => '<a href="{url}" class="nav-link">{label}</a>',
							]);
							?>
							<!-- /#menu -->
							</div>
						</nav>
					</div>
				</div>
			</div>
		</header>
		<section class="main-page">
			<div class="container">
				<?= $content; ?>
			</div>
		</section>
		<footer>
			<div class="container">
				<div class="footer-in">
					<div class="f-left">
						<!-- Default dropup button -->
						<div class="btn-group dropup">
						  <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-globe-africa"></i>Русский
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
							<button class="dropdown-item" type="button">English</button>
							<button class="dropdown-item" type="button">Кыргызча</button>
						  </div>
						</div>
					</div>
					<div class="f-middle"><?= isset($staticContent['bottom-years']) ? $staticContent['bottom-years'] : ''; ?></div>
					<div class="f-right">
						<ul class="social">
							<li><a href="#"><i class="fab fa-instagram"></i></a></li>
							<li><a href="#"><i class="fab fa-facebook-square"></i></a></li>
							<li><a href="#"><i class="fab fa-odnoklassniki-square"></i></a></li>
							<li><a href="#"><i class="fab fa-youtube"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</footer>
		<?php $this->endBody(); ?>
	</body>
</html>
<?php $this->endPage() ?>
