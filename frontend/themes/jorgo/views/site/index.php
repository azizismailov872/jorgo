<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$staticContent = (isset($this->params['staticContent'])) ? $this->params['staticContent'] :[];
?>
<?= isset($staticContent['index']) ? $staticContent['index'] : ''; ?>
