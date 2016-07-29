<?php
namespace infoweb\email\assets;

use yii\web\AssetBundle as AssetBundle;

class TemplateAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/email/assets/';

    public $css = [
    ];

    public $js = [
        'js/template.js'
    ];

    public $depends = [
        'backend\assets\AppAsset'
    ];
}