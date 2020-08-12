<?php
namespace common\modules\rates\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rates".
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class Rates extends \yii\db\ActiveRecord
{
	const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rates';
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
            [['name', 'text'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'text'], 'string'],
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
            'text' => Yii::t('form', 'Text'),
            'status' => Yii::t('form', 'Status'),
            'created_at' => Yii::t('form', 'Created at'),
            'updated_at' => Yii::t('form', 'Updated at'),
        ];
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
