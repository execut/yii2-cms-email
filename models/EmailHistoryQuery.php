<?php

namespace infoweb\email\models;

/**
 * This is the ActiveQuery class for [[EmailHistory]].
 *
 * @see EmailHistory
 */
class EmailHistoryQuery extends \yii\db\ActiveQuery
{
    public function resent()
    {
        return $this->andWhere(['action' => EmailHistory::ACTION_RESENT]);
    }

    /**
     * @inheritdoc
     * @return EmailHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EmailHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
