<?php
namespace common\modules\content\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\modules\content\models\Menu;
use common\modules\uploads\models\Files;
use common\modules\uploads\models\FileHelper;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property string  $title
 * @property string  $content
 * @property string  $meta_title
 * @property string  $meta_description
 * @property string  $meta_keywords
 * @property integer $status
 * @property string  $credated_at
 * @property string  $updated_at
 */
class Content extends \yii\db\ActiveRecord
{
	const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
    
    public $content_no_wysywig;
	public $content_no_wysywig_on;
	public $image;
	public $category = 'content_item';
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'title', 'content'], 'required'],
            [['content'], 'checkContent', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['title', 'content', 'content_no_wysywig', 'meta_description', 'meta_keywords'], 'string', 'min' => 2, 'message' => Yii::t('messages', 'In this field minimum acceptable symbols are 2!')],
            [['menu_id', 'content_no_wysywig_on', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'meta_title'], 'string', 'max' => 100, 'message' => Yii::t('form', 'In this field maximal acceptable symbols are 100!')],
        ];
    }
    
    public function checkContent($attribute, $param)
    {	
		if($this->content == '' && $this->content_no_wysywig == '')
		{
			$this->addError($attribute, Yii::t('form', 'Fill the field!'));
		}
		elseif($this->content_no_wysywig == '' && $this->content_no_wysywig_on > 0)
		{
			$this->addError($attribute, Yii::t('form', 'Fill the field!'));
		}
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'title' => Yii::t('form', 'Title'),
            'content' => Yii::t('form', 'Content'),
            'content_no_wysywig' => Yii::t('form', 'Content without wysywig'),
            'meta_title' => Yii::t('form', 'Meta title'),
            'meta_description' => Yii::t('form', 'Meta description'),
            'meta_keywords' => Yii::t('form', 'Meta keywords'),
            'publish' => Yii::t('form', 'Publish'),
            'created_at' => Yii::t('form', 'Created at'),
            'updated_at' => Yii::t('form', 'Update at'),
        ];
    }
    
    public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			
			if($this->content_no_wysywig_on > 0)
			{
				$this->content = $this->content_no_wysywig;
			}
	 
			return true;
		}
		return false;
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		$result = Files::uploadImageFromTmpDir($this->category, $this->id, true, true);
		
		parent::afterSave($insert, $changedAttributes);
	}
	
	public function afterDelete()
	{
		$dir = Files::getUploadDir($this->category, false, $this->id);
		$dir = Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir;
		
		if(is_dir($dir))
		{	
			// Remove directory
			FileHelper::removeDirectory($dir);
		}
		
		parent::afterDelete();
	}
    
    /**
     * Relation with menu table by id field
     */
	public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
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
    
    public static function getContentDataByUrl($url)
    {
		return self::find()
		->select('menu.name AS menu_name, content.id, content.title, content.content, content.meta_title, content.meta_description, content.meta_keywords')
		->from('content')
		->leftJoin('menu', 'menu.id = content.menu_id')
		->where('menu.url=:url AND menu.status > :menu_status AND content.status > :content_status', [':url' => $url, ':menu_status' => Menu::STATUS_NOT_PUBLISH, ':content_status' => self::STATUS_NOT_PUBLISH])
		->one();
	}
	
	public static function getContentDataBySearch($search)
    {
		return self::find()
		->select('`content`.`id` AS `content_id`, `content`.`title`, `menu`.`url`')
		->leftJoin('`menu`', '`menu`.`id` = `content`.`menu_id`')
		->where('`content`.`title` LIKE :title OR `content`.`content` LIKE :content', [':title' => '%'.$search.'%', ':content' => '%'.$search.'%'])
		->asArray()
		->all();
	}
}
