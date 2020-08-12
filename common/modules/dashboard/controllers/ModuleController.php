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
use common\modules\dashboard\models\AuthModules;
use common\modules\dashboard\models\AdminUsers;

/**
 * ModuleControler
 */
class ModuleController extends Controller
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
						'actions' => ['create'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'moduleCreate');
						}
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'matchCallback' => function ($rule, $action) 
						{	
							return AdminUsers::isUserHasAccessPermission(\Yii::$app->user->identity->id, \Yii::$app->user->identity->group_id, 'moduleDelete');
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
     * Creates a new module in Rbac.
     * If creation is successful, the browser will be redirected to the 'create' page.
     * @return array
     */
    public function actionCreate()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		$result = false;
		
		//Initial vars
		$errors = [];
		$msg = Yii::t('messages', 'Failure!');
		$moduleID = 0;
		$name = '';
		
		//Check request is ajax
		if($request->isAjax)
		{
			//Get POST data
			$post = Yii::$app->request->post();
			
			//Get models
			$model = new AuthModules();
			
			//Validate data
			if($model->load($post))
			{	
				if($model->validate())
				{			
					//Save data in model
					if($model->save())
					{	
						$result = true;
						$msg = Yii::t('messages', 'Record is created!');
						$moduleID = $model->id;
						$name = $model->name;
					}
				}
				else
				{
					//Get validate errors
					$errors = [0, ActiveForm::validate($model), 'create-module-form'];
				}
			}
		}
		
		return ['result' => $result, 'id' => $moduleID, 'name' => $name, 'errors' => $errors, 'msg' => $msg];
	}
	
	/**
     * Delete item in AuthModules model through ajax mode.
     * If delete is successful, the browser will be displayed the 'create' page.
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
			$id = (isset($post['item'])) ? intval($post['item']) : 0;
			
			//Check id
			if($id > 0)
			{
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
     * Finds the AuthModules model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthModules::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'The requested page does not exist!'));
        }
    }
}
