<?php
namespace common\modules\content\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\content\models\Menu;

/**
 * MenuSearch represents the model behind the search form about `app\models\Menu`.
 */
class MenuSearch extends Menu
{
	public $category_id;
	public $name;
	public $url;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'status'], 'integer'],
            [['name', 'url'], 'string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'category_id' => Yii::t('form', 'Category'),
            'name' => Yii::t('form', 'Menu name'),
            'status' => Yii::t('form', 'Status'),
            'url' => Yii::t('form', 'Url'),
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
        $query = Menu::getMenuList();

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
            '`menu_2`.`id`' => $this->id,
            '`menu_2`.`category_id`' => $this->category_id,
            '`menu_2`.`status`' => trim($this->status)
        ]);
		
        $query->andFilterWhere(['like', '`menu_2`.`name`', $this->name]);
        
        $query->andFilterWhere(['like', '`menu_2`.`url`', $this->url]);
        
        $query->orderBy('`menu_2`.`id` DESC, `menu_2`.`order` ASC, `menu_2`.`parent_id`');

        return $dataProvider;
    }
}
