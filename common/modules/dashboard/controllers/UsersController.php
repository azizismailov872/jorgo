<?php
namespace common\modules\dashboard\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Service;
use common\modules\dashboard\models\forms\CreateUserForm;
use common\modules\dashboard\models\AdminUsers;
use common\modules\dashboard\models\AdminUsersSearch;
use common\modules\dashboard\models\forms\AddGroupForm;
use common\modules\dashboard\models\forms\EditGroupForm;
use common\modules\dashboard\models\AdminUserGroups;
use common\modules\dashboard\models\AuthAssignment;

/**
 * UsersControler
 */
class UsersController extends Controller
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
                        'actions' => ['view'],
                        'roles' => ['@'],
                    ],
                    [
						'allow' => true,
						'actions' => ['index'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'usersView');
						}
					],
					[
						'allow' => true,
						'actions' => ['create'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'usersCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['add-user'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'usersCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['update'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'usersUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['update-user'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'usersUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'usersDelete');
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {	
		//Get search model
		$searchModel = new AdminUsersSearch();
		
		//Set data provider
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		//Get pager count
		$pagerCount = Service::getPagerCount('admin-users');
		
		//Set pager
        $dataProvider->pagination->pageSize = $pagerCount;
        
        //Set pager list
        $pagerCountList = (isset(\Yii::$app->params['pagerCountList'])) ? \Yii::$app->params['pagerCountList'] : [];
        
        //Get users groups
        $usersGroupsList = AdminUserGroups::find()->select(['id', 'name'])->asArray()->all();
		$usersGroupsList = (!empty($usersGroupsList)) ? ArrayHelper::map($usersGroupsList, 'id', 'name') : [];
        
        //Set page titles
        $this->view->params['title'] = Yii::t('form', 'Users');

        $this->view->params['block_title'] = Yii::t('form', 'User`s list');
        
        //Render page
		return $this->render('index', [
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'usersGroupsList' => $usersGroupsList,
            'pagerCountList' => $pagerCountList,
            'pagerCount' => $pagerCount,
            'statusList' => AdminUsers::getStatusList()
		]);
	}
	
    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
    */
    public function actionView($id)
    {
		//Get model

		$model = AdminUsers::find()->with('group')->where('admin_users.id=:id', [':id' => $id])->one();
		
		if($model !== null) 
        {
            //Set page titles
			$this->view->params['title'] = Yii::t('form', 'Profile');
				
			//Render page
			return $this->render('view', [
				'model' => $model,
				'statusList' => AdminUsers::getStatusList()
			]);
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'The requested page does not exist!'));
        }
    }
    
    /**
     * Creates a new item in Users model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
		//Get models
		$usersModel = new CreateUserForm();

		$addGroupModel = new AddGroupForm();

		$editGroupModel = new EditGroupForm();
		
		//Set page title
		$this->view->params['title'] = Yii::t('form', 'Users');
		
		//Get user groups from model
        $usersGroupsList = AdminUserGroups::find()->select(['id', 'name'])->asArray()->all();
		$usersGroupsList = (!empty($usersGroupsList)) ? ArrayHelper::map($usersGroupsList, 'id', 'name') : [];
		
		//Get POST data
		$post = Yii::$app->request->post();
		
		//Render page
        return $this->render('create', [
			'usersModel' => $usersModel,
            'addGroupModel' => $addGroupModel,
            'editGroupModel' => $editGroupModel,
			'usersGroupsList' => $usersGroupsList,
        ]);
    }
    
    /**
     * Creates a new item in Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionAddUser()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$request = \Yii::$app->getRequest();

		$result = false;
		
		//Initial vars
		$errors = [];
		$url = \Yii::$app->request->BaseUrl.'/users';
		$msg = Yii::t('messages', 'Failure!');
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			
			//Get models
			$usersModel = new CreateUserForm(['scenario'=>CreateUserForm::SCENARIO_REGISTER]);
			$editGroupModel = new EditGroupForm(['scenario'=>EditGroupForm::SCENARIO_DEFAULT]);
			
			if($editGroupModel->load($post))
			{	
				if($editGroupModel->validate())
				{	
					//Validate data
					if($usersModel->load($post))
					{	
						//Set data in user model 
						$usersModel->group_id = $editGroupModel->edit_group_name;
						
						if($usersModel->validate())
						{	
							//Save data in model
							if($usersModel->save())
							{
								$result = true;
								$msg = Yii::t('messages', 'Record is added!');
								
								\Yii::$app->getSession()->setFlash('success', Yii::t('messages', $msg));
							}
						}
						else
						{
							//Get validate errors
							$errors = [1, ActiveForm::validate($usersModel), 'create-user-form'];
						}
					}
					else
					{
						//Get validate errors
						$errors = [1, ActiveForm::validate($usersModel), 'create-user-form'];
					}
				}
				else
				{
					//Get validate errors
					$errors = [0, $editGroupModel->errors, 'edit-group-form'];
				}
			}
			else
			{
				//Get validate errors
				$errors = [0, ActiveForm::validate($editGroupModel), 'create-user-form'];
			}
		}
		
		return ['result' => $result, 'errors' => $errors, 'msg' => $msg, 'url' => $url];
	}
	
	/**
     * Update data item in Users model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @return mixed
    */
	public function actionUpdate($id)
	{
		//Get model
		$model = AdminUsers::find()->joinWith(['group'])->where(['admin_users.id'=>$id])->one();
		
		//Check model
		if($model !== null) 
		{
			//Set scenario
			$model->scenario = ('update');
			
			if($model->load(Yii::$app->request->post()) && $model->save(false)) 
			{	
				\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Record is updated!'));
				
				return $this->redirect(['index']);
			} 
			else 
			{
				//Set page title
				$this->view->params['title'] = Yii::t('form', 'Edit');
		
				//Render page
				return $this->render('update', [
					'model' => $model
				]);
			}
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'The requested page does not exist!'));
        }
	}
	
	/**
     * Update data item in Users model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
	public function actionUpdateUser()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$html = '';
		$errors = [];
		$url = \Yii::$app->request->BaseUrl.'/users';
		$msg = Yii::t('messages', 'Failure!');
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			
			$id = (isset($post['id'])) ? intval($post['id']) : 0;
			
			//Get model data
			$model = $this->findModel($id);
			
			//Set scenario
			$model->scenario = ('update');
			
			//Validate data and update data in model
			if($model->load(Yii::$app->request->post()) && $model->save())
			{	
				$result = true;
			}
		}
		
		return ['result' => $result, 'msg' => $msg, 'url_data' => ["Users", $url]];
	}
	
	/**
     * Delete item in AdminUsers model through ajax mode.
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
     * Finds the AdminUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminUsers::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'The requested page does not exist!'));
        }
    }
}
