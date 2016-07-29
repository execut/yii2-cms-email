<?php

namespace infoweb\email\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use infoweb\email\models\Template;

/**
 * This is the model class for table "emails_templates_lang".
 *
 * @property string $email_template_id
 * @property string $language
 * @property string $to
 * @property string $bbc
 * @property string $from
 * @property string $subject
 * @property string $message
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Template $template
 */
class TemplateLang extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emails_templates_lang';
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
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['language', 'from', 'subject', 'message'], 'required'],
            [['email_template_id', 'created_at', 'updated_at'], 'integer'],
            [['to', 'bcc', 'from', 'subject'], 'string', 'max' => 255],
            [['message'], 'string'],
            [['language'], 'string', 'max' => 2],
            // Only required for existing records
            [['email_template_id'], 'required', 'when' => function($model) {
                return !$model->isNewRecord;
            }]
        ];
 
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email_template_id' => Yii::t('infoweb/email', 'Email template ID'),
            'language' => Yii::t('app', 'Language'),
            'to' => Yii::t('infoweb/email', 'To'),
            'bbc' => Yii::t('infoweb/email', 'BBC'),
            'from' => Yii::t('infoweb/email', 'From'),
            'subject' => Yii::t('infoweb/email', 'Subject'),
            'message' => Yii::t('infoweb/email', 'Bericht'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'email_template_id']);
    }
}
