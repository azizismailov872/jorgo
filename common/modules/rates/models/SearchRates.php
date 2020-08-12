<?php
namespace common\modules\rates\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\rates\models\Rates;

/**
 * SearchRates represents the model behind the search form about `common\modules\news\models\Rates`.
 */
class SearchRates extends Rates
{
	public $date_from;
	public $date_to;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name'], 'string'],
            [['date_from', 'date_to'], 'checkDate', 'skipOnEmpty' => false, 'skipOnError' => false],
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
            'status' => Yii::t('form', 'Status'),
            'date_from' => Yii::t('form', 'Date from'),
            'date_to' => Yii::t('form', 'Date to'),
        ];
    }
    
    public function checkDate($attribute, $param)
    {
		if($this->date_from != '' || $this->date_to != '') 
		{
			if($this->date_from == '' || $this->date_to == '') 
			{
				$this->addError($attribute, Yii::t('form', 'Please, fill this field!'));
			}
			else
			{
				$date_from = \DateTime::createFromFormat('d-m-Y', $this->date_from)->format('d-m-Y') == $this->date_from;
				$date_to = \DateTime::createFromFormat('d-m-Y', $this->date_to)->format('d-m-Y') == $this->date_to;
				
				if(!$date_from || !$date_to)
				{
					$this->addError($attribute, Yii::t('form', 'Incorrect characters are entered!'));
				}
				else
				{
					if(strtotime($this->date_from) > strtotime($this->date_to))
					{
						$this->addError($attribute, Yii::t('form', 'Incorrect date is entered!'));
					}
				}
			}
		}
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
        $query = Rates::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		$query->andFilterWhere([
            'id' => trim($this->id),
            'status' => trim($this->status),
        ]);
        
        if($this->date_from != '' && $this->date_to != '') 
		{
			$query->andFilterWhere(['between', 'created_at', trim(strtotime($this->date_from)), trim(strtotime($this->date_to))]);
		}

        $query->andFilterWhere(['like', 'name', trim($this->title)]);
        
        return $dataProvider;
    }
}
