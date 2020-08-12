<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace backend\themes\gentella\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GentellaAppAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@app/themes/gentella';
    public $sourcePath = '@app/themes/gentella';
    public $css = [
		'build/css/custom.min.css',
		//'vendors/bootstrap/dist/css/bootstrap.min.css',
		'vendors/font-awesome/css/font-awesome.min.css',
		'vendors/nprogress/nprogress.css',
		'vendors/iCheck/skins/flat/green.css',
    ];
    public $js = [
		'vendors/bootstrap/dist/js/bootstrap.min.js',
		'vendors/fastclick/lib/fastclick.js',
		'vendors/nprogress/nprogress.js',
		'vendors/bootstrap-progressbar/bootstrap-progressbar.min.js',
		'build/js/custom.min.js',
		'vendors/iCheck/icheck.min.js',
		'js/script.js',
		'js/success.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
