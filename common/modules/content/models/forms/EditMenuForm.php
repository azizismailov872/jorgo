<?php
namespace common\modules\content\models\forms;

use Yii;
use yii\base\Model;
use common\modules\content\models\Menu;

/**
 * Edit menu in Menu model form
 */
class EditMenuForm extends Model
{
	const SCENARIO_DEFAULT = 'default';
	const SCENARIO_UPDATE = 'update';
	
	public $edit_menu_name;
	public $menu_name;
	public $menu_url;
	public $menu_status;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name required
            [['menu_name', 'menu_url'], 'required'],
            
            // name must be a string value
            [['menu_status'], 'integer'],
            
            // set default value
            [['menu_status'], 'default', 'value' => '0'],
            
            // name must be a string value
            [['menu_name', 'menu_url'], 'string'],
        ];
    }
    
    /**
     * Scenarios
    */
    public function scenarios()
    {
        return [
			self::SCENARIO_DEFAULT => ['menu_name', 'menu_url'],
			self::SCENARIO_UPDATE => ['id', 'name']
        ];
    }
}
