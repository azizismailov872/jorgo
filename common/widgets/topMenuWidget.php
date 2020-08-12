<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class topMenuWidget extends Widget
{
	public $itemList;
	public $template = '<li>{url}</li>';
	
	public function run()
	{
		$lines = [];
		
		if(is_array($this->itemList) && !empty($this->itemList))
		{	
			$lines[] = '<ul id="w2" class="navbar-nav navbar-right nav">';
			
			foreach ($this->itemList as $i => $item) 
			{
				if(isset($item['name']) && isset($item['url']))
				{
					$lines[] = $this->renderItem($item);
				}
			}
			
			$lines[] = '</ul>';	
		}
		
		echo implode("\n", $lines);
	}
	
	protected function renderItem($item)
    {	
		return strtr($this->template, [
			'{url}' => Html::a($item['name'], \Yii::$app->request->BaseUrl.'/'.$item['url'], [])
		]);
    }
}
