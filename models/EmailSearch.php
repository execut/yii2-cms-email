<?php

namespace infoweb\email\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use infoweb\email\models\Email;

class EmailSearch extends Email
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'from', 'to', 'read', 'created_at', 'form', 'rep', 'profession'], 'safe'],
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
            $params['EmailSearch']['created_at'] = strtotime($params['EmailSearch']['created_at']);
        }

        $query = Email::find();

        // Add action filter
        $query->andFilterWhere(['action' => Yii::$app->session->get('emails.actionType')]);

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

        if ($this->created_at) {
            $query->andFilterWhere(['created_at' => $this->created_at]);
        }

        $query->andFilterWhere(['like', 'subject', $this->subject]);
        $query->andFilterWhere(['like', 'from', $this->from]);
        $query->andFilterWhere(['like', 'to', $this->to]);
        $query->andFilterWhere(['like', 'form', $this->form]);
        $query->andFilterWhere(['like', 'rep', $this->rep]);
        $query->andFilterWhere(['like', 'profession', $this->profession]);

        // Format the date for display
        $this->created_at = $origDate;

        return $dataProvider;
    }
}
