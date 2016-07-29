<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use infoweb\email\assets\TemplateAsset;

// Register asset bundle(s)
TemplateAsset::register($this);

?>
<div class="news-form">
    
    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => 'news-form',
        'options'                   => ['class' => 'tabbed-form', 'enctype' => 'multipart/form-data'],
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,
    ]);

    // Initialize the tabs
    $tabs = [];

    // Add the main tabs
    $tabs = [
        [
            'label' => Yii::t('app', 'Settings'),
            'content' => $this->render('_data_tab', [
                'model' => $model,
                'form' => $form,
                'active' => true,
            ]),
        ],
        [
            'label' => Yii::t('app', 'Email'),
            'content' => $this->render('_default_tab', [
                'model' => $model,
                'form' => $form,
                'allowContentDuplication' => $allowContentDuplication
            ]),
        ]
    ];

    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);   
    ?>

    <div class="form-group buttons">
        <?= $this->render('@infoweb/cms/views/ui/formButtons', ['model' => $model]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>