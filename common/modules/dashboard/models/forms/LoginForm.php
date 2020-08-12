<?php
namespace common\modules\dashboard\models\forms;

use Yii;
use yii\base\Model;
use common\modules\dashboard\models\AdminUsers;
use common\modules\dashboard\models\AuthAssignment;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $username;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // login and password are both required
            [['login', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if(!$this->hasErrors())
        {
            $user = $this->getUser();
            
            if($user !== null)
            {
				if (!$user || !$user->validatePassword($this->password)) 
				{
					$this->addError($attribute, Yii::t('form', 'Wrong login or password!'));
				}
				
				if($user && $user->id > 1)
				{	
					$model = new AuthAssignment();
					$result = $model->checkIsExistRoleByUserID($user->id);
					
					if(!$result) 
					{
						$this->addError($attribute, Yii::t('form', 'You have not permissions!'));
					}
				}
			}
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if($this->validate()) 
        {	
			if($this->getUser() !== null)
            {
				return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
			}
        }
        
		return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) 
        {
            $this->_user = AdminUsers::findByUsername($this->login);
        }

        return $this->_user;
    }
}
