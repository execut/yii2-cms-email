<?php
namespace infoweb\email\assets;

use yii\web\AssetBundle as AssetBundle;

class EmailAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/email/assets/';
    
    public $css = [
        'css/email.css'
    ];
    
    public $js = [
        'js/email.js'
    ];
    
    public $depends = [
        'backend\assets\AppAsset'
    ];
}