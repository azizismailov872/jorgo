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
use common\models\Service;
use common\modules\dashboard\models\forms\CreateRoleForm;
use common\modules\dashboard\models\forms\CreatePermissionsForm;
use common\modules\dashboard\models\AdminUserGroups;
use common\modules\dashboard\models\AuthModules;
use common\modules\dashboard\models\AuthAssignment;
use common\modules\dashboard\models\AuthItem;
use common\modules\dashboard\models\AuthItemChild;
use common\modules\dashboard\models\AuthItemChildSearch;
use common\modules\dashboard\models\AdminUsers;

/**
 * PermissionsControler
 */
class PermissionsController extends Controller
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
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'permissionsView');
						}
					],
					[
						'allow' => true,
						'actions' => ['view'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'permissionsView');
						}
					],
					[
						'allow' => true,
						'actions' => ['create'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'permissionsCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['create-permission'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'permissionsCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete-permission'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'permissionsDelete');
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
            ]
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
     * Lists all Rbac permissions.
     * @return mixed
     */
    public function actionIndex()
    {	
		//Get search model
		$searchModel = new AuthItemChildSearch();
		
		//Set data provider
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		//Get pager count
		$pagerCount = Service::getPagerCount('admin-permissions');
		
		//Set pager
        $dataProvider->pagination->pageSize = $pagerCount;
        
        //Set pager list
        $pagerCountList = (isset(\Yii::$app->params['pagerCountList'])) ? \Yii::$app->params['pagerCountList'] : [];
		
		//Set page titles
        $this->view->params['title'] = Yii::t('form', 'Permissions access');
        
        //Render page
		return $this->render('index', [
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagerCountList' => $pagerCountList,
            'pagerCount' => $pagerCount
		]);
	}
	
	/**
     * Displays a permission by name.
     * @param varchar $child
     * @return mixed
    */
    public function actionView($child)
    {
		$authManager = Yii::$app->getAuthManager();
		
		//Get permission		
		$permissions = $authManager->getPermission($child);
		$permissions = Service::convertFieldToArray($permissions);
		
		if(!empty($permissions)) 
        {
            //Set page titles
			$this->view->params['title'] = Yii::t('form', 'Permission');
			
			//Render page
			return $this->render('view', [
				'permissions' => $permissions
			]);
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'The requested page does not exist!'));
        }
    }
	
	/**
     * Creates a new item in Rbac.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
		//Get models
		$moduleModel = new AuthModules();
		$permissionsModel = new CreatePermissionsForm();
		
		//Get user groups list from model
        $usersGroupsList = AdminUserGroups::find()->select(['id', 'name'])->asArray()->all();
		$usersGroupsList = (!empty($usersGroupsList)) ? ArrayHelper::map($usersGroupsList, 'id', 'name') : [];
		
		//Get roles list from model
        $moduleList = AuthModules::find()->select(['id', 'name'])->asArray()->all();
		$moduleList = (!empty($moduleList)) ? ArrayHelper::map($moduleList, 'id', 'name') : [];
		
		//Get roles list from model
        $roleList = AuthItem::find()->select(['name'])->where(['type'=>'1'])->asArray()->all();
		$roleList = (!empty($roleList)) ? ArrayHelper::map($roleList, 'name', 'name') : [];
		
		//Get permissions list from model
        $permissionsList = AuthItem::getPermissionActionsList();
		
		//Set page title
		$this->view->params['title'] = Yii::t('form', 'Permissions access');
		
		//Render page
        return $this->render('create', [
			'moduleModel' => $moduleModel,
			'permissionsModel' => $permissionsModel,
			'permissionsList' => $permissionsList,
			'usersGroupsList' => $usersGroupsList,
			'moduleList' => $moduleList,
			'roleList' => $roleList
        ]);
	}
	
	/**
     * Creates a new permission in Rbac.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return array
     */
    public function actionCreatePermission()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		$url = \Yii::$app->request->BaseUrl.'/permissions';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			
			//Get models
			$permissionsModel = new CreatePermissionsForm();
			
			//Validate data
			if($permissionsModel->load($post))
			{
				//Save data in model
				if($permissionsModel->save())
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
     * Delete item in AuthItem model through ajax mode.
     * If delete is successful, the browser will be displayed the 'index' page.
     * @return array
     */
	public function actionDeletePermission()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		$html = '';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$permissionName = (isset($post['item'])) ? $post['item'] : '';
			
			//Check permission
			if($permissionName != '')
			{	
				$authManager = Yii::$app->getAuthManager();
				$permission = $authManager->getPermission($permissionName);
				
				if($permission !== null)
				{
					//Delete data in model
					if($authManager->remove($permission))
					{
						$result = true;
						$msg = Yii::t('messages', 'Record is deleted!');
					}
				}
			}
		}
		
		return ['result' => $result, 'msg' => $msg, 'id' => $permissionName];
	}
}
