<?php
namespace common\modules\content\models\forms;

use Yii;
use yii\base\Model;
use common\modules\content\models\MenuCategories;

/**
 * Add menu category in MenuCategories model form
 */
class AddMenuCategoryForm extends Model
{
	public $menu_category_name;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name required
            [['menu_category_name'], 'required'],
            
            // name must be a string value
            ['menu_category_name', 'string'],
        ];
    }
    
    /**
     * Add menu category in MenuCategories model.
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
        $model = new MenuCategories();
        $model->name = $this->menu_category_name;
        
        //save data on model
        if($model->save())
        {
			$result = $model;
		}
        
        return $result;
    }
}
