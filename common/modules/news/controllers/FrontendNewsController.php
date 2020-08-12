<?php
namespace common\modules\news\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use common\models\Service;
use common\modules\content\models\StaticContent;
use common\modules\backoffice\models\Partners;
use common\modules\backoffice\models\forms\SignupForm;
use common\modules\backoffice\models\forms\LoginForm;
use common\modules\news\models\News;
use common\modules\shop\models\CatalogItem;
use common\modules\tickets\models\Tickets;

/**
 * FrontendHistoryItems controller for the `shop` module
 */
class FrontendNewsController extends Controller
{
	public $layout = 'content';
	public $theme = '';
	
	/**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
		];
     }
     
    /**
     * Load modals
    */
    public function beforeAction($action) 
	{ 
		if(\Yii::$app->controller->action->id == 'index' || \Yii::$app->controller->action->id == 'view') 
		{
			$this->view->params['loginModel'] = new LoginForm();
			$this->view->params['signupModel'] = new SignupForm();
			$this->view->params['sponsorData'] = Partners::getSponsorData();
			$this->view->params['staticContent'] = StaticContent::getStaticContentList(['logo-decription', 'payment-methods', 'banner-buyers', 'banner-partnership', 'price-list', 'bottom-social-line', 'top-menu-contacts']);
			
			//Get item list for menu cart widget
			$this->view->params['menuCartWidgetList'] = CatalogItem::getItemsListForWidget();
			
			//Get item list for messages widget
			$this->view->params['messagesWidgetList'] = Tickets::find()->select('`id`, `subject` AS `message`, "2" AS `type`')->where(['status' => Tickets::STATUS_ADMIN_ANSWER])->asArray()->all();
			
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
     * Displays all News model.
     * @return mixed
     */
    public function actionIndex()
    {
		//Set data provider for news
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->where(['>', '`status`', News::STATUS_NOT_PUBLISH])->orderBy('`created_at` DESC'),
        ]);
        
        //Get pager count
		$pagerCount = Service::getPagerCount('frontend-news');
        
		$this->view->params['title'] = Yii::t('form', 'News');
		
        return $this->render('index'.$this->theme, [
            'dataProvider' => $dataProvider,
            'pagerCount' => $pagerCount,
            'category' => 'news_item',
            'theme' => $this->theme
        ]);
    }
    
    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->view->params['title'] = Yii::t('form', 'News');
		
        return $this->render('news'.$this->theme, [
            'model' => $this->findModel($id),
            'category' => 'news_item',
        ]);
    }
    
    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages', 'The requested page does not exist!'));
        }
    }
}
