<?php
namespace common\modules\dashboard\models\forms;

use Yii;
use yii\base\Model;
use common\modules\dashboard\models\AdminUserGroups;
use common\modules\dashboard\models\AdminUsers;
use common\modules\dashboard\models\AuthAssignment;

/**
 * Create user form
 */
class CreateUserForm extends Model
{
	const SCENARIO_REGISTER = 'register';
	const SCENARIO_UPDATE = 'update';
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
	
	public $id;
	public $username;
    public $email;
    public $group_id;
	public $password;
    public $re_password;
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 're_password'], 'filter', 'filter' => 'trim'],
            [['username', 'email', 'password', 're_password'], 'required', 'message' => Yii::t('form', 'Please, fill this field!')],
            [['id'],'required','except'=>['register']],
            [['password', 're_password'],'required','except'=>['update']],
            ['username', 'unique', 'targetClass' => '\common\modules\dashboard\models\AdminUsers', 'except' => ['update'], 'message' => Yii::t('form', 'This user is exist!')],
            [['username'], 'match', 'pattern' => '/^[a-z0-9-_\s,]+$/u', 'message' => Yii::t('form', 'Wrong symbols!')],
            [['username'], 'string', 'min' => 2, 'max' => 100],
            [['username'], 'safe'],
            ['email', 'email'],
            ['email', 'string', 'max' => 50],
            [['id', 'group_id'], 'integer'],
            ['email', 'unique', 'targetClass' => '\common\modules\dashboard\models\AdminUsers', 'except' => ['update'], 'message' => Yii::t('form', 'This email is exist!')],
            ['re_password', 'compare', 'compareAttribute' => 'password'],
            [['password', 're_password'], 'match', 'pattern' => '/^[A-Za-z0-9_\s,]+$/u', 'message' => Yii::t('form', 'Wrong symbols!')],
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'username' => Yii::t('form', 'Username'),
			'email' => Yii::t('form', 'Email'),
            'password' => Yii::t('form', 'Password'),
            're_password' => Yii::t('form', 'Repeat password'),
        ];
    }
    
    /**
     * Scenarios
     *
     * @return mixed
     */
    public function scenarios()
	{
		return [
			self::SCENARIO_REGISTER => ['username', 'email', 'password', 're_password'],
			self::SCENARIO_UPDATE => ['id', 'username', 'email', 'password', 're_password']
		];
    }
    
    /**
     * Add group in AdminUserGroups model.
     *
     * @return boolean
     */
    public function save()
    {
		$result = false;
		
		//set data in model
        $model = new AdminUsers();
        $model->username = $this->username;
        $model->email = $this->email;
        $model->group_id = $this->group_id;
        $model->status = self::STATUS_ACTIVE;
        $model->created_at = time();
        $model->setPassword($this->password);
        $model->generateAuthKey();
        
        //save data on model
        if($model->save(false))
        {	
			$userID = $model->id;
			
			if($userID > 0)
			{	
				$authAssignmentModel = new AuthAssignment();
				
				if($authAssignmentModel->setRole($userID))
				{			
					$result = true;
				}
			}
		}
        
        return $result;
    }
}
?>
