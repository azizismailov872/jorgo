<?php
namespace common\modules\content\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\content\models\Content;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $url
 */
class Menu extends \yii\db\ActiveRecord
{
	const SCENARIO_DEFAULT = 'default';
	const SCENARIO_CREATE = 'create';
	const SCENARIO_UPDATE = 'update';
	const SCENARIO_PUBLISH = 'publish';
	const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
	
	public $edit_menu_name;
	public $edit_menu_url;
	public $menu;
	public $category_name;
	public $parent_menu_name;
	public $content_name;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'name'], 'required', 'message' => Yii::t('form', 'Fill the field!')],
            //['name', 'checkUniqueMenu'],
            [['name', 'url'], 'string', 'max' => 100, 'message' => Yii::t('form', 'In this field maximal acceptable symbols are 100!')],
            [['name'], 'string', 'min' => 2, 'message' => Yii::t('form', 'In this field minimum acceptable symbols are 2!')],
            //['url', 'checkUniqueUrl'],
            [['category_id', 'parent_id', 'status', 'order'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'parent_id' => Yii::t('form', 'Parent menu'),
            'category_id' => Yii::t('form', 'Category'),
            'category_name' => Yii::t('form', 'Category name'),
            'parent_menu_name' => Yii::t('form', 'Parent menu'),
            'name' => Yii::t('form', 'Menu name'),
            'url' => Yii::t('form', 'Url'),
            'menu' => Yii::t('form', 'Menu'),
        ];
    }
    
    /**
     * Scenarios
    */
    public function scenarios()
    {
        return [
			self::SCENARIO_DEFAULT => ['name', 'url'],
			self::SCENARIO_CREATE => ['category_id', 'parent_id', 'name', 'url', 'status'],
			self::SCENARIO_UPDATE => ['id', 'name'],
			self::SCENARIO_PUBLISH => ['status']
        ];
    }
    
    /**
     * Relation with menu table by id field
     */
	public function getContent()
    {
        return $this->hasOne(Content::className(), ['id' => 'content_id']);
    }
    
    /**
     * Add hash password before user insert or update
     *
     * @return mixed
     */
    public function beforeSave($insert) 
    {	
		if(isset($this->parent_id)) 
		{	
			$this->parent_id = ($this->parent_id != NULL) ? $this->parent_id : 0;
		}
		
		return parent::beforeSave($insert);
	}
    
    public function checkUniqueMenu()
    {	
		$condition = ($this->id > 0) ? ' AND id != :id' : '';
		$params = ($this->id > 0) ? array(':name' => $this->name, ':id' => $this->id) : array(':name' => $this->name);
		
		$data = self::find()
		->select('id')
		->from('menu')
		->where('name=:name'.$condition, $params)
		->one();
		
		if($data !== null)
		{
			$this->addError('name', Yii::t('form', 'This menu is exist!'));
		}
    }
    
    public function checkUniqueUrl()
    {	
		$condition = ($this->id > 0) ? ' AND id != :id' : '';
		$params = ($this->id > 0) ? array(':url' => $this->url, ':id' => $this->id) : array(':url' => $this->url);
		
		$data = self::find()
		->select('id')
		->from('menu')
		->where('url=:url'.$condition, $params)
		->one();
		
		if($data !== null)
		{
			$this->addError('url', Yii::t('form', 'This url is exist!'));
		}
	}
	
	public static function getMenuList()
    {
		return self::find()
		->select('menu_1.name AS parent_menu_name, menu_2.id, menu_2.name, menu_2.url, menu_2.status, menu_categories.name as category_name')
		->from('menu menu_1')
		->rightJoin('menu menu_2', 'menu_1.id = menu_2.parent_id')
		->leftJoin('menu_categories', 'menu_categories.id = menu_2.category_id');
	}
	
	public static function getMenuListByCategory($categoryName)
    {
		return self::find()->select(['menu.id', 'menu.parent_id', 'menu.name', 'menu.url'])
		->leftJoin('menu_categories', 'menu_categories.id = menu.category_id')
		->where('menu.parent_id = 0 AND menu_categories.name = :category_name AND menu.status != :status', ['category_name'=>$categoryName, 'status'=>0])
		->asArray()
		->all();
	}
	
	public static function formMenuList($categoryName, $backoffice = false, $partner = true)
    {
		$result = [];
		
		//Get menu list
		$menuList = self::getMenuListByCategory($categoryName);
		
		foreach($menuList as $i => $menu)
		{
			$result[] = [
				'label' => Yii::t('menu', $menu['name']), 
				'url' => \Yii::$app->request->BaseUrl.DIRECTORY_SEPARATOR.$menu['url'], 
				'options'=> [],
				'template' => ($backoffice) ? '<a href="{url}">
					{label}
				</a>' :
				'<a href="{url}">
					{label}
				</a>',
			];
		}
		
		return $result;
	}
	
	public static function getMenuDataByIDInUpdateMode($id)
    {
		return self::find()
		->select('menu_1.name AS parent_menu_name, menu_2.id, menu_2.name, menu_2.url, menu_categories.name as category_name')
		->from('menu menu_1')
		->rightJoin('menu menu_2', 'menu_1.id = menu_2.parent_id')
		->leftJoin('menu_categories', 'menu_categories.id = menu_2.category_id')
		->where('menu_2.id=:id', [':id' => $id])
		->one();
	}
	
	/**
     * Get status list of content
     *
     * @return array
     */
	public static function getStatusList()
    {
		return array(self::STATUS_NOT_PUBLISH=>Yii::t('form', 'Unpublished'), self::STATUS_PUBLISH=>Yii::t('form', 'Published'));
	}
	
	public static function orderMove($model, $id, $order)
    {
		echo $model->order;
		$sign = ($order == 'up') ? '<' : '>';
		$orderBy = ($order == 'up') ? 'DESC' : 'DESC';
		$orderModel = self::find()
		->select('id, order')
		->from('menu')
		->where('`order` '.$sign.' :order', [':order' => $model->order])
		->orderBy('order DESC')
		->limit(1)
		->one();
		echo $sign;
		echo $orderModel->id;
		$previousOrder = $orderModel->order;
		$orderModel->order = $model->order;
		
		if($orderModel->save(false))
		{
			echo 'Work!';
			$model->order = $previousOrder;
			
			if($model->save(false))
			{	echo 'Work2!';
				$result = true;
				$msg = Yii::t('messages', 'Record is updated!');
			}
		}
	}
	
	public function deleteMenu($id)
    {
		if(($model = Menu::findOne($id)) !== null) 
		{
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
			
			try 
			{
				$menu_model = $connection->createCommand()
				->delete(self::tableName(), [
					'parent_id' => $model->id
				])
				->execute();
	
				$connection->createCommand()
				->delete(self::tableName(), [
					'id' => $model->id
				])
				->execute();
	
				$transaction->commit();
				
				return true;
			} 
			catch(Exception $e) 
			{
				$transaction->rollback();
				
				return false;
			}
        }
	}
}
