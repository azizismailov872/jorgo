<?php
namespace common\modules\content;

/**
 * content module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\content\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {	
		parent::init();
        
        if(\Yii::$app->id == 'app-frontend')
        {	
			$theme = (isset(\Yii::$app->params['defaultThemeLayout'])) ? \Yii::$app->params['defaultThemeLayout'] : '';
			
			if($theme != '')
			{
				$this->layoutPath = \Yii::getAlias($theme);
			}
		}
		else
		{
			$this->layoutPath = \Yii::getAlias('@app/themes/gentella/views/layouts/');
		}
        // custom initialization code goes here
    }
}
