<?php
namespace common\modules\content\models\forms;

use Yii;
use yii\base\Model;
use common\modules\content\models\MenuCategories;

/**
 * Edit in menu category in MenuCategories model form
 */
class EditMenuCategoryForm extends Model
{
	const SCENARIO_DEFAULT = 'default';
	const SCENARIO_UPDATE = 'update';
	
	public $edit_menu_category_name;
	public $id;
	public $name;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // required
            [['menu_category_name', 'except' => 'update'], 'required'],
            [['id', 'name', 'except' => 'default'], 'required'],
            // integer
            [['menu_category_name', 'id'], 'integer'],
            // name must be a string value
            [['name'], 'string'],
            [['id', 'name'], 'safe'],
        ];
    }
    
    /**
     * Scenarios
    */
    public function scenarios()
    {
        return [
			self::SCENARIO_DEFAULT => ['menu_category_name'],
			self::SCENARIO_UPDATE => ['id', 'name']
        ];
    }
}
