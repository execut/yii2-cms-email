<?php

namespace infoweb\emails\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use infoweb\emails\models\Email;

class EmailSearch extends Email
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'from', 'read', 'created_at'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // Transform the date to a unix timestamp for usage in the search query
        if (isset($params['EmailSearch']['created_at'])) {
            $origDate = $params['EmailSearch']['created_at'];
            $params['EmailSearch']['create_at'] = strtotime($params['EmailSearch']['created_at']);
        }
        
        $query = Email::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['created_at' => $this->created_at]);
        $query->andFilterWhere(['like', 'subject', $this->subject]);
        $query->andFilterWhere(['like', 'from', $this->from]);
        
        // Format the date for display
        $this->created_at = $origDate;

        return $dataProvider;
    }
}
