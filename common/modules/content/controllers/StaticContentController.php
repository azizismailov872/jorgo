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
use common\modules\content\models\StaticContentSearch;
use common\modules\content\models\StaticContent;
use common\modules\content\models\Content;

/**
 * StaticContentC controller for the `content` module
 */
class StaticContentController extends Controller
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
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'staticContentView');
						}
					],
					[
						'allow' => true,
						'actions' => ['view'],
						'matchCallback' => function ($rule, $action)
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'staticContentView');
						}
					],
					[
						'allow' => true,
						'actions' => ['create'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'staticContentCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['edit'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'staticContentUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['update'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'staticContentUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['status'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'staticContentUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'staticContentDelete');
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
     * Lists all StaticContent models.
     * @return mixed
     */
   public function actionIndex()
    {
        //Get search model
		$searchModel = new StaticContentSearch();
		
		//Set data provider
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		//Get pager count
		$pagerCount = Service::getPagerCount('admin-catalog');
		
		//Set pager
        $dataProvider->pagination->pageSize = $pagerCount;
        
        //Set pager list
        $pagerCountList = (isset(\Yii::$app->params['pagerCountList'])) ? \Yii::$app->params['pagerCountList'] : [];
        
        //Set page titles
        $this->view->params['title'] = Yii::t('form', 'Static content');
        
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
     * Displays a single StaticContent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Creates a new StaticContent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$model = new StaticContent();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Record is created!'));
            return $this->redirect(['index']);
        } 
        else 
        {
			//Set page titles
			$this->view->params['title'] = Yii::t('form', 'Create content');
			
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing StaticContent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Change status in StaticContent model through ajax mode.
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
				$status = ($model->status > 0) ? StaticContent::STATUS_NOT_PUBLISH : StaticContent::STATUS_PUBLISH;
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
        if (($model = StaticContent::findOne($id)) !== null) {
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
