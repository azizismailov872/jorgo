<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
	public $layout = 'admin';
	
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {	
		if(!Yii::$app->user->isGuest) 
        {	
			return $this->redirect(\Yii::$app->request->BaseUrl.'/news');
        }
		
        $model = new LoginForm();
       
        if($model->load(Yii::$app->request->post()) && $model->login()) 
        {
			return $this->redirect(\Yii::$app->request->BaseUrl.'/news');
        } 
        else 
        {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) 
        {
			return $this->redirect(\Yii::$app->request->BaseUrl.'/news');
        }
		
		$model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
        {
            return $this->redirect(\Yii::$app->request->BaseUrl.'/news');
        } 
        else 
        {
            $model->password = '';
			
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
