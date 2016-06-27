<?php

namespace infoweb\email\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use infoweb\email\models\Email;
use yii\db\Expression;

class EmailSearch extends Email
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'from', 'to', 'read', 'created_at', 'form', 'rep', 'profession', 'registrated'], 'safe'],
            ['registrated', 'string']
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
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                //['registrated']
            ],
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

        if($this->registrated) {
            if(strtolower($this->registrated) == strtolower(Yii::t('infoweb/email', 'Yes'))) {
                $query->innerJoin('user', new Expression('emails.to = user.email COLLATE utf8_unicode_ci'));
                $query->andFilterWhere(['=', 'form', 'Sanmax app']);
                $query->andWhere(new Expression('NOT ISNULL (`user`.`id`)'));
            }
            elseif(strtolower($this->registrated) == strtolower(Yii::t('infoweb/email', 'No'))) {
                $query->leftJoin('user', new Expression('emails.to = user.email COLLATE utf8_unicode_ci'));
                $query->andFilterWhere(['=', 'form', 'Sanmax app']);
                $query->andWhere(new Expression('ISNULL (`user`.`id`)'));
            }
        }

        // Format the date for display
        $this->created_at = $origDate;

        return $dataProvider;
    }
}
