<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu;
use common\models\Menu as LeftAdminMenu;
use common\models\Service;
use app\models\AdminMenu;
use backend\widgets\CustomMenu;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$bundle = AppAsset::register($this);

$adminMenu = new AdminMenu();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Fonts -->
	<!--<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold|PT+Sans+Narrow:regular,bold|Droid+Serif:iamp;v1' rel='stylesheet' type='text/css' />	-->
</head>
<body>
    <?php $this->beginBody() ?>
    <div id="header-wrapper" class="container">
		<div id="user-account" class="row" >
			<div class="threecol"></div>
			<?php if(Yii::$app->user->identity !== null): ?>
				<div class="ninecol last"><a href="<?=\Yii::$app->request->BaseUrl; ?>/site/logout"><?= Yii::t('menu', 'Выход'); ?></a> <span>|</span> <a href="<?=\Yii::$app->request->BaseUrl; ?>/users/profile/<?= \Yii::$app->user->identity->id ?>"><?= Yii::t('menu', 'Профиль'); ?></a> <span>|</span> <span><?= Yii::t('messages', 'Добро пожаловать'); ?>, <strong><?= \Yii::$app->user->identity->username ?>!</strong></span> </div>
			<?php endif; ?>
		</div>
		<div id="user-options" class="row">
			<div class="threecol"></div>
			<div class="ninecol last fixed">
				<?= CustomMenu::widget([
					'items' => $adminMenu->getAdminTopMenuList(),
					'options' => [
						'class'=>"nav-user-options"
					],
					'activeCssClass'=>false,
					'encodeLabels' => false,
					'linkTemplate'=>'<a href="{url}">{image}&nbsp;&nbsp;{label}</a>',
				]); ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div id="sidebar" class="threecol">
				<?= Menu::widget([
					'items' => $adminMenu->getAdminLeftMenuList(),
					'options' => [
						'id'=>"navigation"
					],
					'activeCssClass'=>false,
					'encodeLabels' => false,
					'firstItemCssClass'=>'first active',
				]); ?>
			</div>
			<div id="content" class="ninecol last"><?= $content ?></div>		
		</div>
	</div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
