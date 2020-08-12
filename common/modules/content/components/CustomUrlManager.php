<?php
namespace common\modules\content\components;

use yii\web\UrlManager;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class CustomUrlManager extends UrlManager
{
	public function addRules($rules, $append = true)
    {
		$connection = \Yii::$app->db;
		$sql = "SELECT `menu`.`url` 
		FROM `menu`, `content`
		WHERE `menu`.`id` = `content`.`menu_id` AND `menu`.`status` > 0;";
		$urlList = $connection->createCommand($sql)->queryAll();
		
		if(!empty($urlList))
		{
			$rules = array_fill_keys(array_keys(array_flip(ArrayHelper::getColumn($urlList, 'url'))), 'content/frontend-menu/index');
		}
		
        if(!$this->enablePrettyUrl) 
        {
            return;
        }
        
        $rules = $this->buildRules($rules);
        
        if ($append) 
        {
            $this->rules = array_merge($this->rules, $rules);
        } 
        else 
        {
            $this->rules = array_merge($rules, $this->rules);
        }
    }
}
