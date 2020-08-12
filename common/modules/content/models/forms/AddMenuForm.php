<?php
namespace common\modules\content\models\forms;

use Yii;
use yii\base\Model;
use common\modules\content\models\Menu;

/**
 * Add menu in Menu model form
 */
class AddMenuForm extends Model
{
	public $menu_type;
	public $menu_category_id;
	public $menu_parent_id;
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
            [['menu_category_id', 'menu_name', 'menu_url'], 'required'],
            
            // name must be a string value
            [['menu_type', 'menu_category_id', 'menu_parent_id', 'menu_status'], 'integer'],
            
            // set default value
            [['menu_status'], 'default', 'value' => '0'],
            
            // name must be a string value
            [['menu_name', 'menu_url'], 'string'],
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
        $model = new Menu();
        $model->category_id = $this->menu_category_id;
        $model->parent_id = ($this->menu_parent_id != '') ? $this->menu_parent_id : 0;
        $model->name = $this->menu_name;
        $model->url = $this->menu_url;
        $model->status = $this->menu_status;
		
        //save data on model
        if($model->save())
        {
			$result = $model;
		}
        
        return $result;
    }
}
