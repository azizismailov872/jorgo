<?php
namespace common\modules\dashboard\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use common\modules\dashboard\models\AdminUserGroups;
use common\modules\dashboard\models\forms\AddGroupForm;
use common\modules\dashboard\models\forms\EditGroupForm;

/**
 * UsersGroupsControler
 */
class UsersGroupsController extends Controller
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
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
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
     * Creates a new item in AdminUserGroups model through ajax mode.
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
			//Set AdminUserGroups model
			$model = new AddGroupForm();
			
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
     * Dsiplay user`s group edit form through ajax mode.
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
     * Update item in AdminUserGroups model through ajax mode.
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
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$id = (isset($post['id'])) ? intval($post['id']) : 0;
			$groupName = (isset($post['name'])) ? $post['name'] : '';
			
			//Check post data
			if($id > 0 && $groupName != '')
			{
				//Get model data
				$model = $this->findModel($id);
				$model->scenario = AdminUserGroups::SCENARIO_UPDATE;
				$model->name = $groupName;
				$model->id = $id;
				
				if($model->validate())
				{	
					//Update data in model
					if($model->save())
					{
						$result = true;
						$msg = Yii::t('messages', 'Record is updated!');
							
						//Set models
						$editGroupModel = new EditGroupForm();
							
						//Get users groups
						$usersGroupsList = AdminUserGroups::find()->select(['id', 'name'])->asArray()->all();
						$usersGroupsList = (!empty($usersGroupsList)) ? ArrayHelper::map($usersGroupsList, 'id', 'name') : [];
							
						//Get form for displaying in page
						$html = $this->renderAjax('partial/edit_group_form', [
							'editGroupModel' => $editGroupModel,
							'usersGroupsList' => $usersGroupsList,
						]);
					}
				}
			}
		}
		
		return ['result' => $result, 'html' => $html, 'msg' => $msg];
	}
	
	/**
     * Delete item in AdminUserGroups model through ajax mode.
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
     * Finds the AdminUserGroups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminUserGroups::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'The requested page does not exist!'));
        }
    }
}
