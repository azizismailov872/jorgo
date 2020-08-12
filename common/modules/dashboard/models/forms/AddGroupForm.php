<?php
namespace common\modules\dashboard\models\forms;

use Yii;
use yii\base\Model;
use common\modules\dashboard\models\AdminUserGroups;

/**
 * Add group in AdminUserGroups model form
 */
class AddGroupForm extends Model
{
	public $group_name;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name required
            [['group_name'], 'required'],
            
            // name must be a string value
            ['group_name', 'string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'group_name' => Yii::t('form', 'Group Name'),
        ];
    }
    
    /**
     * Add group in AdminUserGroups model.
     *
     * @return model
     */
    public function save()
    {
		//validate data
        if(!$this->validate())
        {	
			return null;
        }
        
        $result = null;
        
        //set data in model
        $model = new AdminUserGroups();
        $model->name = $this->group_name;
        
        //save data on model
        if($model->save())
        {
			$result = $model;
		}
        
        return $result;
    }
}
