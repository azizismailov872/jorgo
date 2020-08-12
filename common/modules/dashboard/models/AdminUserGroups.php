<?php

namespace common\modules\dashboard\models;

use Yii;

/**
 * This is the model class for table "admin_user_groups".
 *
 * @property int $id
 * @property string $name
 */
class AdminUserGroups extends \yii\db\ActiveRecord
{
	const SCENARIO_DEFAULT = 'default';
	const SCENARIO_UPDATE = 'update';
	
	public $edit_group_name;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_user_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'name' => Yii::t('form', 'Name')
        ];
    }
    
    /**
     * Scenarios
    */
    public function scenarios()
    {
        return [
			self::SCENARIO_DEFAULT => ['name'],
			self::SCENARIO_UPDATE => ['id', 'name']
        ];
    }
}
