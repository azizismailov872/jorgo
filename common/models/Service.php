<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for service functions.
 * Service functions use in all project
 */
class Service
{
	/**
     * Get pager count.
     * Get from settings model, if not get from params
     * @param varchar $index
     * @return int
     */
	public static function getPagerCount($index)
	{
		$result = 10;
		$model = Settings::find()->select(['value'])->where(['name'=>'pager', 'index'=>$index])->one();
		
		if($model !== null)
		{
			$result = (isset($model->value)) ? $model->value : $result;
		}
		else
		{
			$result = (isset(\Yii::$app->params['pagerCount'])) ? \Yii::$app->params['pagerCount'] : $result;
		}
		
		return $result;
	}
	
	/**
     * Set pager count.
     * Set pager count in Settings model
     * @param varchar $index
     * @param varchar $count
     * @return bool
     */
	public static function setPagerCount($index, $count)
	{
		$result = false;
		
		if($count > 0)
		{
			$model = Settings::find()->where(['name'=>'pager', 'index'=>$index])->one();
			
			if($model !== null)
			{
				$model->value = $count;
				
				if($model->save(false))
				{
					$result = true;
				}
			}
		}
		
		return $result;
	}
	
	/**
     * Convert object to array.
     * @param object $object
     * @param array $fileds
     * @return array
     */
    public static function convertFieldToArray($object)
    {
		return $data = ArrayHelper::toArray($object);
	}
}
