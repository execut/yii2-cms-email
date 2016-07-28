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