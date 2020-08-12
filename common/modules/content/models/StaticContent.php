<?php
namespace common\modules\content\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "static_content".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property integer $status
 */
class StaticContent extends \yii\db\ActiveRecord
{
	const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
	
	public $content_no_wysywig;
	public $content_no_wysywig_on;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'static_content';
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
            [['name', 'content'], 'required'],
            [['name', 'content'], 'filter', 'filter' => 'trim'],
            [['name'], 'string', 'max' => 100, 'message' => Yii::t('messages', 'In this field maximal acceptable symbols are 100!')],
            //[['content'], 'checkContent'],
            [['name', 'content', 'content_no_wysywig'], 'string', 'min' => 2, 'message' => Yii::t('form', 'In this field minimum acceptable symbols are 2!')],
            [['status', 'content_no_wysywig_on'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'name' => Yii::t('form', 'Name'),
			'content' => Yii::t('form', 'Content'),
            'content_no_wysywig' => Yii::t('form', 'Content without wysywig'),
            'status' => Yii::t('form', 'Status'),
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
    
    public static function getStaticContentByName($contentArr, $name, $tags = false)
    {
		return isset($contentArr[$name]) ? ($tags) ? strip_tags($contentArr[$name])  : $contentArr[$name] : '';
	}
	
	public static function getStaticContentList($contentList)
    {
		$staticContent = self::find()->select(['name', 'content'])->where(['in', 'name', $contentList])->andWhere(['>', 'status', self::STATUS_NOT_PUBLISH])->asArray()->all();
		
		return (!empty($staticContent)) ? ArrayHelper::map($staticContent, 'name', 'content') : [];
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
}
