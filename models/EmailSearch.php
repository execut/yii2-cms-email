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
            [['subject', 'from', 'to', 'read', 'created_at', 'form'], 'safe'],
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
        if (isset($params['EmailSearch']['created_at']) && trim($params['EmailSearch']['created_at']) != '') {
            $origDate = $params['EmailSearch']['created_at'];
            $dateCreated = strtotime($origDate);
            $dateCreatedBeginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($dateCreated)->format('Y-m-d 00:00:00'))->getTimestamp();
            $dateCreatedEndOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($dateCreated)->format('Y-m-d 23:59:59'))->getTimestamp();
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

        if (isset($dateCreatedBeginOfDay) && isset($dateCreatedEndOfDay)) {
            $query->andFilterWhere(['between', 'created_at', $dateCreatedBeginOfDay, $dateCreatedEndOfDay]);

            // Format the date for display
            $this->created_at = date('d-m-Y', $dateCreated);
        }

        $query->andFilterWhere(['like', 'subject', $this->subject]);
        $query->andFilterWhere(['like', 'from', $this->from]);
        $query->andFilterWhere(['like', 'to', $this->to]);
        $query->andFilterWhere(['like', 'form', $this->form]);

        return $dataProvider;
    }
}
