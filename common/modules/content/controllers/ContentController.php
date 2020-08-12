<?php
namespace common\modules\content\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use common\models\Service;
use common\modules\dashboard\models\AdminUsers;
use common\modules\content\models\ContentSearch;
use common\modules\content\models\Content;
use common\modules\content\models\MenuCategories;
use common\modules\content\models\Menu;
use common\modules\content\models\forms\AddMenuCategoryForm;
use common\modules\content\models\forms\EditMenuCategoryForm;
use common\modules\content\models\forms\AddMenuForm;
use common\modules\content\models\forms\EditMenuForm;

/**
 * ContentController for the `content` module
 */
class ContentController extends Controller
{
	public $layout = 'dashboard_with_left_menu';
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
					[
						'allow' => true,
						'actions' => ['index'],
						'matchCallback' => function ($rule, $action)
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'contentView');
						}
					],
					[
						'allow' => true,
						'actions' => ['view'],
						'matchCallback' => function ($rule, $action)
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'contentView');
						}
					],
					[
						'allow' => true,
						'actions' => ['create'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'contentCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['save'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'contentCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['edit'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'contentUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['update'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'contentUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['status'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'contentUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'contentDelete');
						}
					],
                ],
                'denyCallback' => function ($rule, $action) 
				{
					throw new NotFoundHttpException(Yii::t('messages', 'You have not permissions!'));
				}
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }
    
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
     * Switch off CSRF
    */
    public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false;
		 
		return parent::beforeAction($action); 
	}
	
	/**
     * Lists all Content models.
     * @return mixed
     */
   public function actionIndex()
   {
        //Get search model
		$searchModel = new ContentSearch();
		
		//Set data provider
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		//Get pager count
		$pagerCount = Service::getPagerCount('admin-catalog');
		
		//Set pager
        $dataProvider->pagination->pageSize = $pagerCount;
        
        //Set pager list
        $pagerCountList = (isset(\Yii::$app->params['pagerCountList'])) ? \Yii::$app->params['pagerCountList'] : [];
        
        //Set page titles
        $this->view->params['title'] = Yii::t('form', 'Content');
        
        //Render page
		return $this->render('index', [
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagerCountList' => $pagerCountList,
            'pagerCount' => $pagerCount,
            'statusList' => Content::getStatusList()
		]);
    }
    
    /**
     * Creates a new item in Content model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
		//Get models
		$addMenuCategoryModel = new AddMenuCategoryForm();
		$editMenuCategoryModel = new EditMenuCategoryForm();
		$addMenuModel = new AddMenuForm();
		$editMenuModel = new EditMenuForm();
		$contentModel = new Content;
		
		//Get menu categories list from model
        $menuCategoriesList = MenuCategories::find()->select(['id', 'name'])->asArray()->all();
		$menuCategoriesList = (!empty($menuCategoriesList)) ? ArrayHelper::map($menuCategoriesList, 'id', 'name') : [];
		
		//Get menu list from model
        $menuList = Menu::find()->select(['id', 'name'])->asArray()->all();
		$menuList = (!empty($menuList)) ? ArrayHelper::map($menuList, 'id', 'name') : [];
		
		//Set page title
		$this->view->params['title'] = Yii::t('form', 'Create page');
		
		//Render page
        return $this->render('create', [
			'menuCategoryModel' => $addMenuCategoryModel,
			'editMenuCategoryModel' => $editMenuCategoryModel,
			'addMenuModel' => $addMenuModel,
			'editMenuModel' => $editMenuModel,
			'contentModel' => $contentModel,
            'menuCategoriesList' => $menuCategoriesList,
            'menuList' => $menuList,
            'category' => 'content_item',
        ]);
    }
    
    /**
     * Creates a new item in Content model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return array
     */
    public function actionSave()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		$url = \Yii::$app->request->BaseUrl.'/content/content';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			
			//Get model
			$model = new Content();
			
			//Validate data
			if($model->load($post))
			{	
				//Save data in model
				if($model->save())
				{	
					$result = true;
					$msg = Yii::t('messages', 'Record is created!');
						
					\Yii::$app->getSession()->setFlash('success', Yii::t('messages', $msg));
				}
			}
		}
		
		return ['result' => $result, 'msg' => $msg, 'url' => $url];
	}
	
	/**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
    */
    public function actionView($id)
    {
		return $this->render('view', [
            'model' => (Content::find()->joinWith(['menu'])->where(['content.id'=>$id]) !== null) ? Content::find()->joinWith(['menu'])->where(['content.id'=>$id])->one() : null,
            'statusList' => Content::getStatusList()
        ]);
    }
	
	/**
     * Updates an existing StaticContent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = (Content::find()->joinWith(['menu'])->where(['content.id'=>$id]) !== null) ? Content::find()->joinWith(['menu'])->where(['content.id'=>$id])->one() : null;

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
                'category' => 'content_item',
            ]);
        }
    }
    
    /**
     * Change order status in StaticContent model through ajax mode.
     * If delete is successful, the browser will be displayed the 'index' page.
     * @return array
     */
	public function actionStatus()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$id = (isset($post['id'])) ? intval($post['id']) : 0;
			
			//Check id
			if($id > 0)
			{
				//Set model
				$model = $this->findModel($id);
				$status = ($model->status > 0) ? Content::STATUS_NOT_PUBLISH : Content::STATUS_PUBLISH;
				$model->status = $status;
				
				if($model->save(false))
				{
					$result = true;
					$msg = Yii::t('messages', 'Record is updated!');
				}
			}
		}
		
		return ['result' => $result, 'msg' => $msg, 'status' => $status, 'id' => $id];
	}
	
    /**
     * Delete item in StaticContent model through ajax mode.
     * If delete is successful, the browser will be displayed the 'index' page.
     * @return array
     */
	public function actionDelete()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$id = (isset($post['id'])) ? intval($post['id']) : 0;
			
			//Check id
			if($id > 0)
			{
				//Delete data in model
				if($this->findModel($id)->delete())
				{
					$result = true;
					$msg = Yii::t('messages', 'Record is deleted!');
				}
			}
		}
		
		return ['result' => $result, 'msg' => $msg, 'id' => $id];
	}
	
	/**
     * Finds the StaticContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaticContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Content::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /* 
     * Get user id 
    */
    protected static function getUserID()
    {
		return (\Yii::$app->user->identity !== null) ? \Yii::$app->user->identity->id : 0;
	}
	
	/* 
     * Get group id 
    */
	protected static function getUserGroup()
    {
		return (\Yii::$app->user->identity !== null) ? \Yii::$app->user->identity->group_id : 0;
	}
}
