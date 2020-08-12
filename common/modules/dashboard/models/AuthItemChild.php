<?php

namespace common\modules\dashboard\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parent0
 * @property AuthItem $child0
 */
class AuthItemChild extends \yii\db\ActiveRecord
{
	public $role_name;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['parent' => 'name']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['child' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_name' => Yii::t('form', 'Role'),
            'parent' => Yii::t('form', 'Role'),
            'child' => Yii::t('form', 'Permission'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'child']);
    }
    
    /**
     * Get inherit roles by main role
     * @param string $role
     * @return array
     */
    public static function getInheritRolesByMainRole($role)
    {
		return self::find()
		->select('`child`')
		->where('`parent` = :parent', [':parent' => $role])
		->asArray()
		->all();
	}
	
	/**
     * Get inherit roles main role list by main role
     * @param string $role
     * @return array
     */
    public static function getRolesListByMainRole($role)
    {
		$result = [];
		
		//Get role list is there main role
		$mainRoleList = self::find()
		->select('`parent`')
		->where('`child` = :child', [':child' => $role])
		->asArray()
		->all();
		$mainRoleList = (!empty($mainRoleList)) ? ArrayHelper::index($mainRoleList, 'parent') : [];
		
		//Get inherit role list
		$inheritRolesList = self::getInheritRolesByMainRole($role);
		$inheritRolesList = (!empty($inheritRolesList)) ? ArrayHelper::index($inheritRolesList, 'child') : [];
		
		//Get main role list
		$mainRoleList = (!empty($mainRoleList)) ? array_merge($mainRoleList, $inheritRolesList) : $inheritRolesList;
		$mainRoleList[$role] = [];
		
		//Get roles list from model
        $roleList = AuthItem::find()->select(['name'])->where(['type'=>'1'])->asArray()->all();
		$roleList = (!empty($roleList)) ? ArrayHelper::map($roleList, 'name', 'name') : [];
		
		$result = (!empty($roleList)) ? array_diff_key($roleList, $mainRoleList) : $mainRoleList;
		
		return $result;
	}
	
	/**
     * Get inherent roles list
     * @return array
     */
    public static function getInheritRoles($parent)
    {
		return self::find()
		->select('`auth_item_child`.`child`')
		->leftJoin('`auth_item`', '`auth_item`.`name` = `auth_item_child`.`child`')
		->where('`auth_item`.`type` = :type AND `auth_item_child`.`parent` = :parent', [':type' => 1, ':parent' => $parent])
		->asArray()
		->all();
	}
	
	/**
     * Get permissions list
     * @return object
     */
    public static function getPermissionsList()
    {
		return self::find()
		->select('`auth_item`.`name`, `auth_item_child`.`parent` AS `role_name`, `auth_item_child`.`child`')
		->leftJoin('`auth_item`', '`auth_item`.`name` = `auth_item_child`.`child`')
		->where('`auth_item`.`type` = :type', [':type' => 2])
		->orderBy('`auth_item`.`created_at`');
	}
	
	/**
     * Delete child role by parent role from inherent role list
     * @param array $inheritRolesList
     * @param string $parent
     * @return bool
     */
    public function deleteChildRoleByParentRole($inheritRolesList, $parent)
    {
		$result = false;
		
		if(!empty($inheritRolesList))
		{	
			$result = self::deleteAll(['and', 
				'parent = :parent', 
				['in', 'child', $inheritRolesList]], 
				[':parent' => $parent]
			);
		}
		
		return $result;
	}
}
