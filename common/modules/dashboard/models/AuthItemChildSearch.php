<?php
namespace common\modules\dashboard\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\dashboard\models\AuthItemChild;

/**
 * AuthItemChildSearch represents the model behind the search form about `common\modules\dashboard\models\AuthItemChild`.
 */
class AuthItemChildSearch extends AuthItemChild
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => Yii::t('form', 'Role Name'),
            'child' => Yii::t('form', 'Permission'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		$model = new AuthItemChild();
		$query = $model->getPermissionsList();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		$query->andFilterWhere(['like', 'parent', trim($this->parent)]);
		$query->andFilterWhere(['like', 'child', trim($this->child)]);
       
        return $dataProvider;
    }
}
