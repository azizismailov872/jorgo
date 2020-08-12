<?php
namespace common\modules\rates\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use common\models\Service;
use common\modules\rates\models\Rates;
use common\modules\rates\models\SearchRates;

/**
 * BackendRatesController implements the CRUD actions for Rates model.
 */
class BackendRatesController extends Controller
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
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'ratesView');
						}
					],
					[
						'allow' => true,
						'actions' => ['view'],
						'matchCallback' => function ($rule, $action)
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'ratesView');
						}
					],
					[
						'allow' => true,
						'actions' => ['create'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'ratesCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['save'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'ratesCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['edit'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'ratesUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['update'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'ratesUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['status'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'ratesUpdate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(self::getUserID(), self::getUserGroup(), 'ratesDelete');
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
		//Get search model
        $searchModel = new SearchRates();
        
        //Set data provider
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		//Get pager count
		$pagerCount = Service::getPagerCount('admin-rates');
		
		//Set pager
        $dataProvider->pagination->pageSize = $pagerCount;
        
        //Set pager list
        $pagerCountList = (isset(\Yii::$app->params['pagerCountList'])) ? \Yii::$app->params['pagerCountList'] : [];
        
        //Set page titles
        $this->view->params['title'] = Yii::t('form', 'Rates');
        
        //Render page
		return $this->render('index', [
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagerCountList' => $pagerCountList,
            'pagerCount' => $pagerCount,
            'statusList' => Rates::getStatusList()
		]);
    }
    
	/**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		return $this->render('view', [
            'model' => News::find()->with('author')->one(),
            'statusList' => News::getStatusList()
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$model = new News();

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
                'category' => 'news_item',
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);
        
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
                'id' => $id,
                'category' => 'news_item',
            ]);
        }
    }
    
    /**
     * Change status in News model through ajax mode.
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
				$status = ($model->status > 0) ? News::STATUS_NOT_PUBLISH : News::STATUS_PUBLISH;
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
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
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
