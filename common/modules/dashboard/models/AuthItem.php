<?php

namespace common\modules\dashboard\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\modules\dashboard\models\AuthModules;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('form', 'Name'),
            'type' => Yii::t('form', 'Type'),
            'description' => Yii::t('form', 'Description'),
            'rule_name' => Yii::t('form', 'Rule Name'),
            'data' => Yii::t('form', 'Data'),
            'created_at' => Yii::t('form', 'Created At'),
            'updated_at' => Yii::t('form', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }
    
    public static function getPermissionActionsList()
    {
		return [
			1 => Yii::t('form', 'View'),
			2 => Yii::t('form', 'Create'),
			3 => Yii::t('form', 'Update'),
			4 => Yii::t('form', 'Delete'),
		];
	}
	
	public static function setPermissionName($permission, $module)
    {
		$result = '';
		
		if($module > 0 && $permission > 0)
		{
			$permissionActionsList = self::getPermissionActionsList();
			$permission = (isset($permissionActionsList[$permission])) ? $permissionActionsList[$permission] : '';
			
			if($permission != '')
			{
				//Get roles list from model
				$moduleList = AuthModules::find()->select(['id', 'name'])->asArray()->all();
				$moduleList = (!empty($moduleList)) ? ArrayHelper::map($moduleList, 'id', 'name') : [];
				
				$module = (isset($moduleList[$module])) ? $moduleList[$module] : '';
				
				if($module != '')
				{
					if(strpos($module, '-')) 
					{
						$module = explode('-', $module);
						$module = $module[0].ucfirst($module[1]);
					}
					
					$result = $module.$permission;
				}
			}
		}
		
		return $result;
	}
	
	public static function checkExistPermission($userID, $permission)
    {
		$result = false;
		
		//initial auth manager
		$authManager = Yii::$app->getAuthManager();
					
		//Get permissions list by user ID
		$permissionsList = $authManager->getPermissionsByUser($userID);
					
		if(isset($permissionsList[$permission]))
		{
			$result = true;
		}
		
		return $result;
	}
}
