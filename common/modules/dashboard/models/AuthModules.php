<?php

namespace common\modules\dashboard\models;

use Yii;

/**
 * This is the model class for table "auth_modules".
 *
 * @property int $id
 * @property string $name
 */
class AuthModules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_modules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['name'], 'filter', 'filter' => 'trim'],
            [['name'], 'required', 'message' => Yii::t('form', 'Please, fill this field!')],
            [['name'], 'string', 'min' => 2, 'max' => 100],
            [['name'], 'match', 'pattern' => '/^[a-z-\s,]+$/u', 'message' => Yii::t('form', 'Wrong symbols!')],
            ['name', 'unique', 'targetClass' => '\common\modules\dashboard\models\AuthModules', 'targetAttribute' => 'name', 'message' => Yii::t('form', 'This module is existed!')],
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
}
