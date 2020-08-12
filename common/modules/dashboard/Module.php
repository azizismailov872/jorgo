<?php

namespace common\modules\dashboard;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\dashboard\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->layoutPath = \Yii::getAlias('@app/themes/gentella/views/layouts/');

        // custom initialization code goes here
    }
}
