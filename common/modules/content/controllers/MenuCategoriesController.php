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
use common\modules\content\models\forms\AddMenuCategoryForm;
use common\modules\content\models\forms\EditMenuCategoryForm;

/**
 * Menu Categories controller for the `MenuCategories` module
 */
class MenuCategoriesController extends Controller
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
						'actions' => ['save'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuCategoriesCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['edit'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuCategoriesUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['update'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuCategoriesUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['status'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuCategoriesUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'menuCategoriesDelete');
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
     * Creates a new item in MenuCategories model through ajax mode.
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
			$model = new AddMenuCategoryForm();
			
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
     * Dsiplay menu categories edit form through ajax mode.
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
				$model = $this->findModel($id);
				
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
	public function actionUpdate()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$html = '';
		$msg = Yii::t('messages', 'Failure!');
		$categoryName = '';
		$id = 0;
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$id = (isset($post['id'])) ? intval($post['id']) : 0;
			$categoryName = (isset($post['name'])) ? $post['name'] : '';
			
			//Check post data
			if($id > 0 && $categoryName != '')
			{
				//Get model data
				$model = $this->findModel($id);
				$model->scenario = MenuCategories::SCENARIO_UPDATE;
				$model->name = $categoryName;
				$model->id = $id;
				
				if($model->validate())
				{	
					//Update data in model
					if($model->save())
					{
						$result = true;
						$msg = Yii::t('messages', 'Record is updated!');
							
						//Set models
						$editMenuCategoryModel = new EditMenuCategoryForm();
							
						//Get menu categories list from model
						$menuCategoriesList = MenuCategories::find()->select(['id', 'name'])->asArray()->all();
						$menuCategoriesList = (!empty($menuCategoriesList)) ? ArrayHelper::map($menuCategoriesList, 'id', 'name') : [];
											
						//Get form for displaying in page
						$html = $this->renderAjax('partial/edit_group_form', [
							'editMenuCategoryModel' => $editMenuCategoryModel,
							'menuCategoriesList' => $menuCategoriesList,
						]);
					}
				}
			}
		}
		
		return ['result' => $result, 'id' => $id, 'name' => $categoryName, 'html' => $html, 'msg' => $msg];
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
				//Delete data in model
				if($this->findModel($id)->delete())
				{
					$result = true;
					$msg = Yii::t('messages', 'Record is deleted!');
				}
			}
		}
		
		return ['result' => $result, 'msg' => $msg];
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
        if (($model = MenuCategories::findOne($id)) !== null) {
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
