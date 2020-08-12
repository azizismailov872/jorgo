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
use yii\widgets\ActiveForm;
use common\models\Service;
use common\modules\dashboard\models\forms\CreateRoleForm;
use common\modules\dashboard\models\forms\InheritRoleForm;
use common\modules\dashboard\models\AdminUserGroups;
use common\modules\dashboard\models\AuthItem;
use common\modules\dashboard\models\AuthItemChildSearch;
use common\modules\dashboard\models\AuthItemChild;
use common\modules\dashboard\models\AdminUsers;

/**
 * RolesControler
 */
class RolesController extends Controller
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
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'roleView');
						}
					],
					[
						'allow' => true,
						'actions' => ['create-role'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'roleCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete-role'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'roleDelete');
						}
					],
					[
						'allow' => true,
						'actions' => ['check-inherit-role'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'roleCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['inherit-role'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'roleCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['get-inherit-roles'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'roleCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete-inherit-roles'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'roleDelete');
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
     * Creates a new item in Rbac.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionIndex()
    {
		//Get models
		$roleModel = new CreateRoleForm();
		$inheritRoleModel = new InheritRoleForm();
		
		//Get user groups list from model
        $usersGroupsList = AdminUserGroups::find()->select(['id', 'name'])->asArray()->all();
		$usersGroupsList = (!empty($usersGroupsList)) ? ArrayHelper::map($usersGroupsList, 'id', 'name') : [];
		
		//Get roles list from model
        $roleList = AuthItem::find()->select(['name'])->where(['type'=>'1'])->asArray()->all();
		$roleList = (!empty($roleList)) ? ArrayHelper::map($roleList, 'name', 'name') : [];
		
		//Set page title
		$this->view->params['title'] = Yii::t('form', 'Create role');
		
		//Render page
        return $this->render('create', [
			'roleModel' => $roleModel,
			'inheritRoleModel' => $inheritRoleModel,
			'usersGroupsList' => $usersGroupsList,
			'roleList' => $roleList
        ]);
	}
	
	/**
     * Creates a new role in Rbac.
     * If creation is successful, the browser will be redirected to the 'create' page.
     * @return array
     */
    public function actionCreateRole()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$msg = Yii::t('messages', 'Failure!');
		$errors = [];
		$role = '';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			
			//Get model
			$model = new CreateRoleForm();
				
			//Validate data
			if($model->load($post))
			{	
				if($model->validate())
				{			
					//Save data in model
					if($model->save(false))
					{	
						$result = true;
						$msg = Yii::t('messages', 'Record is created!');
						$role = $model->role;
					}
				}
				else
				{
					//Get validate errors
					$errors = [1, ActiveForm::validate($model), 'create-role-form'];
				}
			}
		}
		
		return ['result' => $result, 'role' => $role, 'errors' => $errors, 'msg' => $msg];
	}
	
	/**
     * Delete item in AuthAssignment model through ajax mode.
     * If delete is successful, the browser will be displayed the 'create' page.
     * @return array
     */
	public function actionDeleteRole()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		
		//Initial vars
		$result = false;
		$msg = Yii::t('messages', 'Failure!');
		$item = '';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			$item = (isset($post['item'])) ? $post['item'] : '';
			
			//Check role name
			if($item != '')
			{
				$authManager = Yii::$app->getAuthManager();
				
				//Get role from model
				$role = $authManager->getRole($item);
				
				//Get user ID by session
				$userID = (\Yii::$app->user->identity !== null) ? \Yii::$app->user->identity->id : 0;
				
				//Check data
				if($userID > 0 && $role !== null)
				{
					//Delete data in model
					if($authManager->remove($role, $userID))
					{
						$result = true;
						$msg = Yii::t('messages', 'Record is deleted!');
					}
				}
			}
		}
		
		return ['result' => $result, 'item' => $item, 'msg' => $msg];
	}
	
	/**
     * Check inherit`s role list.
     * @return array
     */
    public function actionCheckInheritRole()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		
		//Initial vars
		$html = '';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			
			//Get model
			$model = new InheritRoleForm(['scenario' => InheritRoleForm::SCENARIO_CHECK_INHERIT_ROLES]);
				
			//Validate data
			if($model->load($post))
			{	
				if($model->validate())
				{
					//Get inherit roles main role list by main role
					$roleList = AuthItemChild::getRolesListByMainRole($model->role_id);
					
					$html = $this->renderAjax('partial/role_list', [
						'model' => $model,
						'roleList' => $roleList
					]);
				}
			}
		}
		
		return ['html' => $html];
	}
	
	/**
     * Inherit role in Rbac.
     * If creation is successful, the browser will be redirected to the 'create' page.
     * @return array
     */
    public function actionInheritRole()
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
			$roleModel = new InheritRoleForm();
				
			//Validate data
			if($roleModel->load($post))
			{	
				//Save data in model
				if($roleModel->save())
				{
					$result = true;
					$msg = Yii::t('messages', 'Record is added!');
								
					\Yii::$app->getSession()->setFlash('success', Yii::t('messages', $msg));
				}
			}
		}
		else
		{
			$msg = Yii::t('messages', 'Is not ajax!');
		}
		
		return ['result' => $result, 'msg' => $msg, 'url' => $url];
	}
	
	/**
     * Get inherit roles list by main role.
     * If creation is successful, the browser will be redirected to the 'create' page.
     * @return array
     */
    public function actionGetInheritRoles()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		
		//Initial vars
		$html = '';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			
			//Get model
			$model = new InheritRoleForm(['scenario' => InheritRoleForm::SCENARIO_CHECK_INHERIT_ROLES]);
				
			//Validate data
			if($model->load($post))
			{	
				if($model->validate())
				{
					//Get inherit roles main role list by main role
					$roleList = AuthItemChild::getInheritRoles($model->role_id);
					$roleList = (!empty($roleList)) ? ArrayHelper::map($roleList, 'child', 'child') : [];
					
					$html = $this->renderAjax('partial/role_list', [
						'model' => $model,
						'roleList' => $roleList
					]);
				}
			}
		}
		
		return ['html' => $html];
	}
	
	/**
     * Delete inherit roles in Rbac.
     * If creation is successful, the browser will be redirected to the 'create' page.
     * @return array
     */
    public function actionDeleteInheritRoles()
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
			
			//Get model
			$model = new InheritRoleForm(['scenario' => InheritRoleForm::SCENARIO_DEFAULT]);
				
			//Validate data
			if($model->load($post))
			{	
				if($model->validate())
				{	
					//Get role
					$role = $model->role_id;
					
					//Get inherit role list
					$inheritRolesList = $model->inherit_roles;
					
					//Check inherit role list
					if(!empty($inheritRolesList))
					{
						$model = new AuthItemChild;
						
						if($model->deleteChildRoleByParentRole($inheritRolesList, $role))
						{
							$result = true;
							$msg = Yii::t('messages', 'Records are deleted!');
							
							//Get inherit roles main role list by main role
							$roleList = AuthItemChild::getInheritRoles($role);
							$roleList = (!empty($roleList)) ? ArrayHelper::map($roleList, 'child', 'child') : [];
							
							$html = $this->renderAjax('partial/role_list', [
								'model' => $model,
								'roleList' => $roleList
							]);
						}
					}
				}
			}
		}
		
		return ['result' => $result, 'html' => $html, 'msg' => $msg];
	}
}
