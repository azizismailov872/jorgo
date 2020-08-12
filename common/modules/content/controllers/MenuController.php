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
use common\modules\content\models\MenuCategories;
use common\modules\content\models\Menu;
use common\modules\content\models\MenuSearch;
use common\modules\content\models\forms\AddMenuForm;
use common\modules\content\models\forms\EditMenuForm;
use common\modules\content\models\Content;

/**
 * Menu controller for the `Menu` module
 */
class MenuController extends Controller
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
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuView');
						}
					],
					[
						'allow' => true,
						'actions' => ['get-menu-category-list'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['create'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['save'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['edit'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['update'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['update-menu'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['change-publish'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['publish-menu'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['status'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['order-up'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuDelete');
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
     * Renders the index view for the module
     * @return mixed
    */
    public function actionIndex()
    {
		//Get search model
		$searchModel = new MenuSearch();
		
		//Set data provider
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		//Get pager count
		$pagerCount = Service::getPagerCount('admin-catalog');
		
		//Set pager
        $dataProvider->pagination->pageSize = $pagerCount;
        
        //Set pager list
        $pagerCountList = (isset(\Yii::$app->params['pagerCountList'])) ? \Yii::$app->params['pagerCountList'] : [];
        
        //Get menu categories list from model
		$menuCategoriesList = MenuCategories::find()->select(['id', 'name'])->asArray()->all();
		$menuCategoriesList = (!empty($menuCategoriesList)) ? ArrayHelper::map($menuCategoriesList, 'id', 'name') : [];
        
        //Set page titles
        $this->view->params['title'] = Yii::t('form', 'Menu');
        
        //Render page
		return $this->render('index', [
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagerCountList' => $pagerCountList,
            'pagerCount' => $pagerCount,
            'statusList' => Menu::getStatusList(),
            'menuCategoriesList' => $menuCategoriesList,
		]);
	}
	
	/**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu(['scenario' => 'create']);
        
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {	
			return $this->redirect(['index']);
        } 
        else 
        {
			//Set page title
			$this->view->params['title'] = Yii::t('form', 'Create menu');
			
			//Get menu categories list from model
			$menuCategoriesList = MenuCategories::find()->select(['id', 'name'])->asArray()->all();
			$menuCategoriesList = (!empty($menuCategoriesList)) ? ArrayHelper::map($menuCategoriesList, 'id', 'name') : [];
			
			//Get menu list from model
			$menuList = Menu::find()->select(['id', 'name'])->asArray()->all();
			$menuList = (!empty($menuList)) ? ArrayHelper::map($menuList, 'id', 'name') : [];
			        
			return $this->render('create', [
                'model' => $model,
				'menuCategoriesList' => $menuCategoriesList,
				'menuList' => $menuList,
            ]);
        }
    }
    
    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		//Get the menu data by ID 
		$model = Menu::getMenuDataByIDInUpdateMode($id);
		$model->scenario = ('update');
	
		if($model !== null) 
		{	
			if($model->load(Yii::$app->request->post()) && $model->save()) 
			{	
				return $this->redirect(['index']);
			} 
			else 
			{
				return $this->render('update', [
					'model' => $model,
					'id' => $id
				]);
			}
        } 
        else 
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Creates a new item in Menu model through ajax mode.
     * If creation is successful, the browser will be displayed the 'index' page.
     * @return array
     */
	public function actionSave()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		$id = 0;
		$name = '';
		
		//Check request is ajax
		if($request->isAjax) 
		{
			//Set MenuCategories model
			$model = new AddMenuForm();
			
			//Validate data
			if($model->load($request->post()))
			{
				//Save data in model
				$model = $model->save();
				
				//Set model data in vars
				if(($model !== null) && ($model->id > 0 && $model->name != ''))
				{
					$result = true;
					$msg = Yii::t('messages', 'Record is created!');
					$id = $model->id;
					$name = $model->name;
				}
			}
		}
		
		return ['result' => $result, 'id' => $id, 'name' => $name, 'msg' => $msg];
	}
	
	/**
     * Dsiplay menu edit form through ajax mode.
     * @return array
     */
	public function actionEdit()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		
		//Initial vars
		$html = '';
		$msg = Yii::t('messages', 'Failure!');
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$id = (isset($post['item'])) ? intval($post['item']) : '';
			
			//Check id
			if($id > 0)
			{
				//Get model data
				$model = Menu::getMenuDataByIDInUpdateMode($id);
				
				//Get form for displaying in page
				$html = $this->renderAjax('partial/edit_form', [
					'model' => $model,
					'id' => $id
				]);
			}
		}
		
		return ['html' => $html, 'msg' => $msg];
	}
	
	/**
     * Update item in MenuCategories model through ajax mode.
     * If creation is successful, the browser will be displayed the 'create' page.
     * @return array
     */
	public function actionUpdateMenu()
	{	
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$html = '';
		$msg = Yii::t('messages', 'Failure!');
		$menuName = '';
		$id = 0;
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$id = (isset($post['id'])) ? intval($post['id']) : 0;
			$menuName = (isset($post['name'])) ? $post['name'] : '';
			$menuUrl = (isset($post['url'])) ? $post['url'] : '';
			
			//Check post data
			if($id > 0 && $menuName != '' && $menuUrl != '')
			{
				//Get model data
				$model = $this->findModel($id);
				$model->scenario = Menu::SCENARIO_UPDATE;
				$model->name = $menuName;
				$model->url = $menuUrl;
				$model->id = $id;
				
				if($model->validate())
				{	
					//Update data in model
					if($model->save())
					{
						$result = true;
						$msg = Yii::t('messages', 'Record is updated!');
							
						//Set models
						$editMenuModel = new EditMenuForm();
							
						//Get menu list from model
						$menuList = Menu::find()->select(['id', 'name'])->asArray()->all();
						$menuList = (!empty($menuList)) ? ArrayHelper::map($menuList, 'id', 'name') : [];
											
						//Get form for displaying in page
						$html = $this->renderAjax('partial/edit_group_form', [
							'editMenuModel' => $editMenuModel,
							'menuList' => $menuList,
						]);
					}
				}
			}
		}
		
		return ['result' => $result, 'html' => $html, 'id' => $id, 'name' => $menuName, 'msg' => $msg];
	}
	
	/**
     * Delete item in MenuCategories model through ajax mode.
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
				$model = new Menu();
				
				//Delete data in model
				if($model->deleteMenu($id))
				{
					$result = true;
					$msg = Yii::t('messages', 'Record is deleted!');
				}
			}
		}
		
		return ['result' => $result, 'msg' => $msg, 'id' => $id];
	}
	
	public function actionGetMenuCategoryList()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		$html = '';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$id = (isset($post['id'])) ? intval($post['id']) : 0;
			
			//Check id
			if($id > 0)
			{	
				$model = new AddMenuForm();
				
				if($id > 1)
				{
					//Get dynamic menu categories list from model
					$menuCategoriesList = MenuCategories::find()->select(['id', 'name'])->asArray()->all();
					$menuCategoriesList = (!empty($menuCategoriesList)) ? ArrayHelper::map($menuCategoriesList, 'id', 'name') : [];
				}
				else
				{
					//Get static menu categories list
					$menuCategoriesList = (isset(\Yii::$app->params['staticMenuCategories'])) ? \Yii::$app->params['staticMenuCategories'] : [];
				}
				
				$html = $this->renderAjax('partial/menu_list', [
					'model' => $model,
					'menuCategoriesList' => $menuCategoriesList
				]);
			}
		}
		
		return ['html' => $html];
	}
	
	public function actionChangePublish()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		$publish = 0;
		
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
				$result = true;
				$publish = $this->findModel($id)->status;
				$publishMsg = ($publish > 0) ? Yii::t('messages', 'Unpublish') : Yii::t('messages', 'Publish');
			}
		}
		
		return ['result' => $result, 'publish' => $publish, 'publish_msg' => $publishMsg, 'msg' => $msg];
	}	
	
	public function actionPublishMenu()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		$publish = 0;
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$id = (isset($post['id'])) ? intval($post['id']) : 0;
			$publish = (isset($post['publish'])) ? intval($post['publish']) : 0;
			
			//Check id
			if($id > 0)
			{	
				//Get model data
				$model = $this->findModel($id);
				$model->scenario = Menu::SCENARIO_PUBLISH;
				$model->status = $publish;
				
				if($model->validate())
				{	
					//Update data in model
					if($model->save())
					{
						$result = true;
						$msg = Yii::t('messages', 'Record is updated!');
						$publishMsg = ($publish > 0) ? Yii::t('messages', 'Unpublish') : Yii::t('messages', 'Publish');
					}
				}
			}
		}
		
		return ['result' => $result, 'publish' => $publish, 'publish_msg' => $publishMsg, 'msg' => $msg];
	}
	
	/**
     * Change order status in Menu model through ajax mode.
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
     * Change order status in Menu model through ajax mode.
     * If delete is successful, the browser will be displayed the 'index' page.
     * @return array
     */
	public function actionOrderUp()
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
			$order = (isset($post['order'])) ? $post['order'] : '';
			
			//Check id
			if($id > 0 && $order != '')
			{
				//Set model
				$model = $this->findModel($id);
				
				Menu::orderMove($model, $id, $order);
				/*$model->status = ($model->status > 0) ? 0 : 1;
				
				if($model->save(false))
				{
					$result = true;
					$msg = Yii::t('messages', 'Record is updated!');
				}*/
			}
		}
		
		return ['result' => $result, 'msg' => $msg, 'id' => $id];
	}
	
	/**
     * Finds the MenuCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
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
