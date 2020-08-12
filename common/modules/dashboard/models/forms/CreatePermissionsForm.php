<?php
namespace common\modules\dashboard\models\forms;

use Yii;
use yii\base\Model;
use common\modules\dashboard\models\AuthItem;

/**
 * Create permissions form
 */
class CreatePermissionsForm extends Model
{
	public $role_id;
	public $permission;
	public $description;
	public $module_id;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['description'], 'filter', 'filter' => 'trim'],
            [['permission', 'description', 'module_id', 'role_id'], 'required', 'message' => Yii::t('form', 'Please, fill this field!')],
            [['description', 'role_id'], 'string', 'min' => 2, 'max' => 100],
            [['permission', 'module_id'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'role_id' => Yii::t('form', 'Role'),
			'permission' => Yii::t('form', 'Permission'),
			'description' => Yii::t('form', 'Description'),
			'module_id' => Yii::t('form', 'Module')
        ];
    }
    
    /**
     * Add permission in Rbac.
     *
     * @return model
     */
    public function save()
    {
		//validate data
        if(!$this->validate())
        {	
			return false;
        }
        
        $result = false;
        
        //Get role
        $authManager = Yii::$app->getAuthManager();
        $role = $authManager->getRole($this->role_id);
        
        if($role !== null)
        {
			$permission = AuthItem::setPermissionName($this->permission, $this->module_id);
			
			if($permission != '')
			{
				//Set permission
				$permission = $authManager->createPermission($permission);
				$permission->description = $this->description;
				$authManager->add($permission);
				$authManager->addChild($role, $permission);
				
				$result = true;
			}
		}
        
        return $result;
	}
}
