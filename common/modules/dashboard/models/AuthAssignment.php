<?php

namespace common\modules\dashboard\models;

use Yii;
use common\modules\dashboard\models\AdminUsers;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('form', 'Item Name'),
            'user_id' => Yii::t('form', 'User ID'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }
    
    /**
     * Set role for user
     * @param int $userID
     * @return bool
     */
    public function setRoleByGroupID($role, $description, $groupID)
    {
		$result = false;
		
		//initial auth manager
        $authManager = Yii::$app->getAuthManager();
        
        //Create role
        $role = $authManager->createRole($role);
		$role->description = $description;
		$addResult = $authManager->add($role);
		
		if($addResult)
		{	
			$result = true;
			$userList = AdminUsers::find()->select('id')->where('`group_id` = :group_id AND `id` != 1', ['group_id'=>$groupID])->all();
			
			if(!empty($userList))
			{
				$assignResult = null;
				
				foreach($userList as $key=>$model)
				{
					if(isset($model->id) && $model->id > 0)
					{	
						$assignResult = $authManager->assign($role, $model->id);
						
						if($assignResult == null)
						{	
							break;
						}
					}
				}
				
				$result = ($assignResult !== null) ? true : false;
			}
		}
		
		return $result;
	}
	
	/**
     * Chek is exist role by user ID
     * @param int $userID
     * @return bool
     */
    public function checkIsExistRoleByUserID($userID)
    {
		$result = false;
		
		//initial auth manager
        $authManager = Yii::$app->getAuthManager();
		
		if($authManager !== null)
		{
			//Get roles by user id		
			$roles = $authManager->getRolesByUser($userID);
			
			//Check roles
			if(!empty($roles))
			{
				$result = true;
			}
		}
		
		return $result;
	}
	
    /**
     * Set role for user
     * @param int $userID
     * @return bool
     */
    public function setRole($userID)
    {
		$result = true;
		
		//initial auth manager
        $authManager = Yii::$app->getAuthManager();
		
		//Get roles by user id		
		$roles = $authManager->getRolesByUser($userID);
		
		//Check roles
		if(empty($roles))
		{	
			//Get role by user id
			$roles = $this->getRoleByUserID($userID);
			
			//Check role data
			if($roles !== null && isset($roles->item_name))
			{	
				//Get role
				$authorRole = $authManager->getRole($roles->item_name);
				
				if($authorRole !== null)
				{
					//Set role by user id
					$result = $authManager->assign($authorRole, $userID);
				}
			}
		}
		
		return $result;
    }
    
    /**
     * Delete item by user id
     * @param int $userID
     * @return bool
     */
    public function deleteItem($userID)
    {
		$result = false;
		
		$connection = \Yii::$app->db;
		$model = $connection->createCommand('DELETE FROM `'.self::tableName().'` WHERE `user_id` = :user_id');
		$model->bindParam(':user_id', $userID);
		$result = $model->execute();
		
		return $result;
    }
    
    /**
     * Get role by user id
     * @param int $userID
     * @return object
     */
    public function getRoleByUserID($userID)
    {
		return self::find()
		->select('`auth_assignment`.`item_name`, `admin_users2`.`group_id`')
		->leftJoin('`admin_users`', '`admin_users`.`id` = `auth_assignment`.`user_id`')
		->leftJoin('`admin_users` `admin_users2`', '`admin_users2`.`group_id` = `admin_users`.`group_id`')
		->where('`admin_users2`.`id` = :id', [':id' => $userID])
		->orderBy('`auth_assignment`.`created_at`')
		->limit(1)
		->one();
	}
}
