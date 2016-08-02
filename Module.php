<?php

namespace infoweb\email;

use Yii;
use infoweb\email\models\Email;

class Module extends \yii\base\Module
{
    private $_unreadEmails = null;
    
    /**
     * Module specific configuration of the ckEditor
     * @var array
     */
    public $ckEditorOptions = [
        'height' => 500
    ];

    /**
     * Enable tab link to sent e-mails
     * @var boolean
     */
    public $enableSent = false;

    /**
     * Enable button to templates, will always be activated as superadmin
     * @var boolean
     */
    public $enableTemplates = false;

    /**
     * Enable resend e-mail
     * @var boolean
     */
    public $enableResent = false;

    /**
     * Allow content duplication with the "duplicateable" plugin
     * @var boolean
     */
    public $allowContentDuplication = true;

    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config.php'));
    }

    public function getUnreadEmails($reload = false)
    {
        if ($this->_unreadEmails === null || $reload) {
            $this->_unreadEmails = Email::find()->where(['read' => 0, 'action' => Email::ACTION_RECEIVED])->count();
        }

        return $this->_unreadEmails;
    }
}