<?php
namespace common\modules\dashboard\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\modules\dashboard\models\AdminUserGroups;
use common\modules\dashboard\models\AuthItem;

/**
 * This is the model class for table "admin_users".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $group_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AdminUsers $group
 * @property AdminUsers[] $adminUsers
 */
class AdminUsers extends \yii\db\ActiveRecord implements IdentityInterface
{
	const SCENARIO_REGISTER = 'register';
	const SCENARIO_UPDATE = 'update';
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    
    public $password;
	public $re_password;
	public $name;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_users';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'email', 'status', 'group_id', 'created_at', 'updated_at'], 'required'],
            [['id', 'group_id', 'created_at', 'updated_at'], 'integer'],
            [['id'],'required','except'=>['register']],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['status'], 'string', 'max' => 4],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdminUsers::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['password', 're_password'],'required','except'=>['update']],
            ['email','email'],
            [['email'],'unique', 'except' => ['update']],
            [['username'], 'unique', 'targetAttribute' => 'login', 'except' => ['update']],
            [['username'], 'match', 'pattern' => '/^[a-z0-9-_\s,]+$/u', 'message' => Yii::t('form', 'Incorrect symbols!')],
            [['password', 're_password'], 'match', 'pattern' => '/^[A-Za-z0-9_\s,]+$/u', 'message' => Yii::t('form', 'Incorrect symbols!')],
            ['re_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'username' => Yii::t('form', 'Username'),
            'auth_key' => Yii::t('form', 'Auth Key'),
            'password' => Yii::t('form', 'Password'),
            're_password' => Yii::t('form', 'Re Password'),
            'password_hash' => Yii::t('form', 'Password Hash'),
            'password_reset_token' => Yii::t('form', 'Password Reset Token'),
            'email' => Yii::t('form', 'Email'),
            'status' => Yii::t('form', 'Status'),
            'group_id' => Yii::t('form', 'Group ID'),
            'created_at' => Yii::t('form', 'Created At'),
            'updated_at' => Yii::t('form', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(AdminUserGroups::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUsers()
    {
        return $this->hasMany(AdminUsers::className(), ['group_id' => 'id']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupName()
	{
		$group = $this->group;
		
		return $group ? $group->name : '';
	}
    
    /**
     * Scenarios
     *
     * @return mixed
     */
    public function scenarios()
	{
		return [
			self::SCENARIO_REGISTER => ['username', 'email', 'group_id', 'password', 're_password'],
			self::SCENARIO_UPDATE => ['id', 'username', 'email', 'password', 're_password']
		];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
		return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
		$this->password_hash = Yii::$app->security->generatePasswordHash($password); 
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * Add hash password before user insert or update
     *
     * @return mixed
     */
    public function beforeSave($insert) 
    {	
		if(isset($this->password)) 
		{	
			$this->password = self::setPassword($this->password);
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterValidate()
    {	
        if($this->scenario == "update" && $this->password) $this->setPassword($this->password);
        parent::afterValidate();
    }
    
    public function afterDelete()
    {	
        if($this->id > 0)
        {
			$authAssignmentModel = new AuthAssignment();
			$authAssignmentModel->deleteItem($this->id);
		}
		
        parent::afterDelete();
    }
    
    /**
     * Get status list of users
     *
     * @return array
     */
	public static function getStatusList()
    {
		return array('0'=>Yii::t('form', 'Not activated'), '1'=>Yii::t('form', 'Activated'));
	}
	
	/**
     * Check has user the access permission to action in controller
     *
     * @param integer $userID pass user ID
     * @param integer $groupID pass group ID
     * @param string $permission pass name of permission
     * @return boolean
     */
    public static function isUserHasAccessPermission($userID, $groupID, $permission)
    {
		$result = false;
		
		if($userID > 0 && $groupID > 0 && $permission != '')
		{
			$model = self::find('group_id')->where('id=:id', [':id' => 1])->one();
			
			if($model !== null)
			{
				$result = ($model->group_id == $groupID) ? true : AuthItem::checkExistPermission($userID, $permission);
			}
		}
		
		return $result;
	}
}
