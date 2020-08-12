<?php
namespace common\modules\dashboard\models\forms;

use Yii;
use yii\base\Model;

/**
 * Create module form
 */
class CreateModuleForm extends Model
{
	public $name;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['name'], 'filter', 'filter' => 'trim'],
            [['name'], 'required', 'message' => Yii::t('form', 'Please, fill this field!')],
            [['name'], 'string', 'min' => 2, 'max' => 100],
            [['name'], 'match', 'pattern' => '/^[a-z0-9\s,]+$/u', 'message' => Yii::t('form', 'Wrong symbols!')],
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'name' => Yii::t('form', 'Name')
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
