<?php

namespace infoweb\email\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\db\ActiveRecord;
use yii\db\Query;

use creocoder\translateable\TranslateableBehavior;

use infoweb\email\models\TemplateLang;

/**
 * This is the model class for table "emails_templates".
 *
 * @property string $id
 * @property string $type
 * @property string $name
 * @property string $supported_tags
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Template extends \yii\db\ActiveRecord
{
    const TYPE_SYSTEM = 'system';
    const TYPE_USER_DEFINED = 'user-defined';

    // Action types
    const ACTION_SENT     = 'sent';
    const ACTION_RECEIVED = 'received';

    public $sendedMail = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emails_templates';
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'to',
                    'bcc',
                    'from',
                    'subject',
                    'message'
                ]
            ],
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
        return [
            [['name', 'action'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['supported_tags', 'action'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('infoweb/email', 'Type'),
            'action' => Yii::t('infoweb/email', 'Actie'),
            'name' => Yii::t('infoweb/email', 'Name'),
            'supported_tags' => Yii::t('infoweb/email', 'Supported tags'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At')
        ];
    }

    public function getActions() {
        return [
            self::ACTION_RECEIVED => Yii::t('infoweb/email', 'Received'),
            self::ACTION_SENT => Yii::t('infoweb/email', 'Sent')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(TemplateLang::className(), ['email_template_id' => 'id']);
    }

     /**
     * Returns all items formatted for usage in a Html::dropDownList widget:
     *      [
     *          'id' => 'name',
     *          'id' => 'name,
     *          ...
     *      ]
     *
     * @return  array
     */
    public function getAllForDropDownList()
    {
        $items = (new Query())
                    ->select('emails_templates.id, emails_templates.name')
                    ->from(['emails_templates' => 'emails_templates'])
                    ->orderBy(['emails_templates.name' => SORT_ASC])
                    ->all();

        return ArrayHelper::map($items, 'id', 'name');
    }
  
    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @return boolean whether the email was sent
     */
    public function sendEmail($data, $template = 'blank', $templateParams = [])
    {
        $mail = Yii::$app->mailer->compose()
            ->setTo(explode(',', str_replace(' ', '', $this->to)))
            ->setFrom($this->from)
            ->setSubject($this->subject);

        if(trim($this->bcc) != '') {
            foreach(explode(',', str_replace(' ', '', $this->bcc)) as $email) {
                $mail->$mail->AddBCC($email);
            }
        }

        $message = $this->message;
        $message = $this->replaceTags($message, $data);

        $body = Yii::$app->mailer->render(
            '@common/mail/'.$template,
            array_merge($templateParams, ['message' => $message]),
            'layouts/html'
        );

        $mail->setHtmlBody($body);

        $this->sendedMail = [
            'language' => Yii::$app->language,
            'form' => $this->name,
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            'message' => $body
        ];

        return $mail->send();
    }

    /**
     * Will process tags and replace them with real content
     * @param array $tags
     * @param object $model
     */
    public function replaceTags($message, $data) {
        return str_replace(array_keys($data), array_values($data), $message);
    }

    /**
     * Save's the form email to the database
     * 
     * @return  boolean
     */
    public function saveEmail()
    {
        $email = new Email($this->sendedMail);
        return $email->save();
    }
}
