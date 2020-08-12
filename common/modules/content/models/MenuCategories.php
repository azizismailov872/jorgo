<?php
namespace common\modules\content\models;

use Yii;

/**
 * This is the model class for table "menu_categories".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Menu[] $menus
 */
class MenuCategories extends \yii\db\ActiveRecord
{
	const SCENARIO_DEFAULT = 'default';
	const SCENARIO_UPDATE = 'update';
	
	public $edit_menu_category_name;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['id'], 'integer'],
            [['name'], 'required'],
            ['name', 'unique', 'targetAttribute' => 'name', 'on'=>'insert'],
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
            'name' => Yii::t('form', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['category_id' => 'id']);
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
