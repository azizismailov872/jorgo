<?php
namespace common\modules\dashboard\models\forms;

use Yii;
use yii\base\Model;
use common\modules\dashboard\models\AdminUserGroups;

/**
 * Edit group in AdminUserGroups model form
 */
class EditGroupForm extends Model
{
	const SCENARIO_DEFAULT = 'default';
	const SCENARIO_UPDATE = 'update';
	
	public $edit_group_name;
	public $id;
	public $name;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // required
            [['edit_group_name', 'except' => 'update'], 'required'],
            [['id', 'name', 'except' => 'default'], 'required'],
            // integer
            [['edit_group_name', 'id'], 'integer'],
            // name must be a string value
            [['name'], 'string'],
            [['id', 'name'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'edit_group_name' => Yii::t('form', 'Group Name'),
        ];
    }
    
    /**
     * Scenarios
    */
    public function scenarios()
    {
        return [
			self::SCENARIO_DEFAULT => ['edit_group_name'],
			self::SCENARIO_UPDATE => ['id', 'name']
        ];
    }
}
