<?php

namespace infoweb\email\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use infoweb\cms\behaviors\Base64EncodeBehavior;

class Email extends \yii\db\ActiveRecord
{
    // Action types
    const ACTION_SENT     = 'sent';
    const ACTION_RECEIVED = 'received';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emails';
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ],
            'base64encode'    => [
                'class' => Base64EncodeBehavior::className(),
                'attributes' => ['message']
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language', 'from', 'to', 'subject', 'message'], 'required'],
            [['read', 'created_at', 'updated_at', 'read_at'], 'integer'],
            [['from'], 'email'],
            ['read', 'default', 'value' => 0],
            [['to'], 'string'],
            ['action', 'default', 'value' => self::ACTION_RECEIVED]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language' => Yii::t('app', 'Language'),
            'form' => Yii::t('infoweb/email', 'Form'),
            'from' => Yii::t('infoweb/email', 'From'),
            'to' => Yii::t('infoweb/email', 'To'),
            'subject' => Yii::t('infoweb/email', 'Subject'),
            'message' => Yii::t('infoweb/email', 'Message'),
            'read' => Yii::t('infoweb/email', 'Read'),
            'created_at' => Yii::t('infoweb/email', 'Send at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'read_at' => Yii::t('infoweb/email', 'Read at'),
        ];
    }

    /**
     * Returns the actionTypes
     *
     * @return array
     */
    public static function actionTypes()
    {
        return [
            self::ACTION_RECEIVED => Yii::t('infoweb/email', 'Received'),
            self::ACTION_SENT     => Yii::t('infoweb/email', 'Sent')
        ];
    }

    public function markAsRead()
    {
        $this->read = 1;
        $this->read_at = time();

        return $this->save();
    }
}