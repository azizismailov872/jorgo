<?php
namespace common\modules\dashboard\models\forms;

use Yii;
use yii\base\Model;
use common\modules\dashboard\models\AuthAssignment;

/**
 * Create role form
 */
class CreateRoleForm extends Model
{
	public $role;
	public $description;
	public $group_id;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['role', 'description'], 'filter', 'filter' => 'trim'],
            [['role', 'description', 'group_id'], 'required', 'message' => Yii::t('form', 'Please, fill this field!')],
            [['role', 'description'], 'string', 'min' => 2, 'max' => 100],
            [['group_id'], 'integer'],
            [['role'], 'match', 'pattern' => '/^[a-z0-9\s,]+$/u', 'message' => Yii::t('form', 'Wrong symbols!')],
            ['role', 'unique', 'targetClass' => '\common\modules\dashboard\models\AuthItem', 'targetAttribute' => 'name', 'message' => Yii::t('form', 'This role is existed!')],
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'role' => Yii::t('form', 'Role'),
			'description' => Yii::t('form', 'Description'),
			'group_id' => Yii::t('form', 'Group name')
        ];
    }
    
    /**
     * Add role in Rbac.
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
		$authAssignmentModel = new AuthAssignment();
		
		if($authAssignmentModel->setRoleByGroupID($this->role, $this->description, $this->group_id))
		{			
			$result = true;
		}
        
        return $result;
    }
}
