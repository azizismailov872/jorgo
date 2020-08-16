<?php
namespace common\modules\content\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\modules\content\models\Content;
use common\modules\content\models\StaticContent;

/**
 * FrontendMenu controller for the `content` module
 */
class FrontendMenuController extends Controller
{
	public $layout = 'main';
	public $theme = '';
	
	/**
     * Load modals
    */
    public function beforeAction($action) 
	{ 
		if(\Yii::$app->controller->action->id == 'index') 
		{
			$this->view->params['staticContent'] = StaticContent::getStaticContentList(['index', 'bottom-years', 'social-buttons']);
			
			//Set theme
			$this->theme = (isset(\Yii::$app->params['defaultTheme'])) ? ('_'.\Yii::$app->params['defaultTheme']) : '';
		}
		 
		/*if(\Yii::$app->controller->action->id == 'get-filter-data') 
		{
			$this->enableCsrfValidation = false;
		}*/
		 
		$this->enableCsrfValidation = false; 
		 
		return parent::beforeAction($action); 
	}
    
    /**
     * Renders the index view for the module
     * @return mixed
     */
    public function actionIndex()
    {
		$url = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
		
		$title = '';
		$metaTitle = '';
		$metaDesc = '';
		$metaKeys = '';
		$data = Content::getContentDataByUrl($url);
		$model = null;
		
		if($data !== null)
		{
			$title = $data->title;
			$metaTitle = $data->meta_title;
			$metaDesc = $data->meta_description;
			$metaKeys = $data->meta_keywords;
			
			//Set page title
			$this->view->params['title'] = $title;
		}
		
		//Set theme
        $theme = (isset(\Yii::$app->params['defaultTheme'])) ? ('_'.\Yii::$app->params['defaultTheme']) : '';
		
		return $this->render('index'.$theme, [
			'title'=>$title, 
			'data'=>$data,
			'meta_title'=>$metaTitle,
			'meta_desc'=>$metaDesc,
			'meta_keys'=>$metaKeys,
			'theme' => $theme,
			'model' => $model,
			'url' => $url,
			'category' => 'content_item'
		]);
	}
}
