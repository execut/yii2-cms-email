<?php

namespace infoweb\email;

use Yii;
use infoweb\email\models\Email;

class Module extends \yii\base\Module
{
    private $_unreadEmails = null;
    
    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config.php'));
    }
    
    public function getUnreadEmails($reload = false)
    {
        if ($this->_unreadEmails === null || $reload) {
            $this->_unreadEmails = Email::find()->where(['read' => 0])->count();    
        }
        
        return $this->_unreadEmails;        
    }
}