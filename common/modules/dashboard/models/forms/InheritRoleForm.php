<?php
namespace common\modules\dashboard\models\forms;

use Yii;
use yii\base\Model;
use common\modules\dashboard\models\AuthAssignment;
use common\modules\dashboard\models\AuthItemChild;

/**
 * Create form of inheritage role 
 */
class InheritRoleForm extends Model
{
	const SCENARIO_DEFAULT = 'default';
	const SCENARIO_CHECK_INHERIT_ROLES = 'check_inherit_roles';
	
	public $role_id;
	public $inherit_roles;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['role_id', 'inherit_roles'], 'required', 'message' => Yii::t('form', 'Please, fill this field!')],
            [['role_id'], 'string'],
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'role_id' => Yii::t('form', 'Role'),
			'inherit_roles' => Yii::t('form', 'Inherit roles'),
        ];
    }
    
    /**
     * Scenarios
    */
    public function scenarios()
    {
        return [
			self::SCENARIO_DEFAULT => ['role_id', 'inherit_roles'],
			self::SCENARIO_CHECK_INHERIT_ROLES => ['role_id']
        ];
    }
    
    /**
     * Inherit role in Rbac.
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
        
        //Get roles list
        $inheritRoles = $this->inherit_roles;
        
        //check roles
        if(!empty($inheritRoles))
        {
			//initial auth manager
			$authManager = Yii::$app->getAuthManager();
			
			//Get main role
			$mainRole = $authManager->getRole($this->role_id);
			
			if($mainRole !== null)
			{
				//Get role from role list
				foreach($inheritRoles as $key => $value)
				{
					$assignResult = false;
					
					//Get inherit role
					$inheritRole = $authManager->getRole($value);
					
					//Check iherit role
					if($inheritRole !== null)
					{
						$assignResult = $authManager->addChild($mainRole, $inheritRole);
					}
					
					if(!$assignResult)
					{
						break;
					}
				}
				
				$result = $assignResult;
			}
		}
		
        return $result;
    }
}
