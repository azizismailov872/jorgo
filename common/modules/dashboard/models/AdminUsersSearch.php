<?php
namespace common\modules\dashboard\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\dashboard\models\AdminUsers;

/**
 * AdminUsersSearch represents the model behind the search form about `common\modules\dashboard\models\AdminUsers`.
 */
class AdminUsersSearch extends AdminUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'status'], 'integer'],
            [['username', 'email'], 'string'],
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
		$model = new AdminUsers;
        $query = AdminUsers::find()->joinWith(['group'])->where('`admin_users`.`id` != 1');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		$query->andFilterWhere([
            '`admin_users`.`id`' => trim($this->id)
        ]);
        
        $query->andFilterWhere([
            '`admin_users`.`group_id`' => trim($this->group_id)
        ]);
        
        $query->andFilterWhere([
            '`admin_users`.`status`' => trim($this->status)
        ]);
        
        $query->andFilterWhere(['like', 'username', trim($this->username)]);
        $query->andFilterWhere(['like', 'email', trim($this->email)]);
        
        return $dataProvider;
    }
}
