<?php
namespace common\modules\news\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\modules\dashboard\models\AdminUsers;
use common\modules\uploads\models\Files;
use common\modules\uploads\models\FileHelper;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property integer $author_id
 * @property string $title
 * @property string $short_text
 * @property string $text
 * @property string  $meta_title
 * @property string  $meta_description
 * @property string  $meta_keywords
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 */
class News extends \yii\db\ActiveRecord
{
	public $username;
	public $image;
	public $category = 'news_item';
	
	const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
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
            [['title', 'short_text', 'text'], 'required'],
            [['author_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['short_text', 'text', 'meta_description', 'meta_keywords'], 'string'],
            [['title', 'meta_title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'author_id' => Yii::t('form', 'Author'),
            'title' => Yii::t('form', 'Title'),
            'short_text' => Yii::t('form', 'Short text'),
            'text' => Yii::t('form', 'Text'),
            'meta_title' => Yii::t('form', 'Meta title'),
            'meta_description' => Yii::t('form', 'Meta description'),
            'meta_keywords' => Yii::t('form', 'Meta keywords'),
            'status' => Yii::t('form', 'Status'),
            'created_at' => Yii::t('form', 'Created at'),
            'updated_at' => Yii::t('form', 'Updated at'),
        ];
    }
    
    /**
     * Add menu id before user insert or update
     *
     * @return mixed
     */
    public function beforeSave($insert) 
    {
		if(parent::beforeSave($insert)) 
		{	
			if($this->isNewRecord) 
			{	
				$this->author_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
			}
		}
		
		return parent::beforeSave($insert);
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
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(AdminUsers::className(), ['id' => 'author_id']);
    }
    
    public function getAuthorName()
	{
		$author = $this->author;
 
		return $author ? $author->username : '';
	}
    
    public function getNewsList()
    {
		return self::find()
		->select('user.username AS author, news.id, news.title, news.status, news.created_at, news.updated_at')
		->leftJoin('user', 'user.id = news.author_id');
	}
	
	public static function getNewsDataBySearch($search)
    {
		return self::find()
		->select('`id`, `title`')
		->where('`title` LIKE :title OR `text` LIKE :content', [':title' => '%'.$search.'%', ':content' => '%'.$search.'%'])
		->asArray()
		->all();
	}
	
	/**
     * Get status list of news
     *
     * @return array
     */
	public static function getStatusList()
    {
		return array(self::STATUS_NOT_PUBLISH => Yii::t('form', 'Not publish'), self::STATUS_PUBLISH => Yii::t('form', 'Publish'));
	}
}
