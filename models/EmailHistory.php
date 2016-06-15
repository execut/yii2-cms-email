<?php

namespace infoweb\email\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "emails_history".
 *
 * @property integer $email_id
 * @property string $action
 * @property string $created_at
 *
 * @property Emails $email
 */
class EmailHistory extends ActiveRecord
{
    // Action constants
    const ACTION_RESENT = 'resent';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emails_history';
    }

    /**
     * @inheritdoc
     * @return EmailHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmailHistoryQuery(get_called_class());
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function() { return time(); },
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_id', 'action'], 'required'],
            [['email_id', 'created_at'], 'integer'],
            [['action'], 'string', 'max' => 20],
            [['email_id'], 'exist', 'skipOnError' => true, 'targetClass' => Email::className(), 'targetAttribute' => ['email_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email_id' => Yii::t('infoweb/email', 'Email ID'),
            'action' => Yii::t('infoweb/email', 'Action'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmail()
    {
        return $this->hasOne(Email::className(), ['id' => 'email_id']);
    }
}
