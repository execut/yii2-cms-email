<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Tabs;
?>
<div class="email-form">
    
    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php $form = ActiveForm::begin(['id' => 'email-form']); ?>

    <?= $form->field($model, 'subject')->textInput([
        'readonly' => true
    ]); ?>
    
    <?= $form->field($model, 'from')->textInput([
        'readonly' => true
    ]); ?>
    
    <?= $form->field($model, 'to')->textInput([
        'readonly' => true
    ]); ?>
    
    <?= $form->field($model, 'message')->textArea([
        'rows' => 15,
    ]); ?>
    
    <?= $form->field($model, 'created_at')->textInput([
        'readonly' => true,
        'value' => Yii::$app->formatter->asDate($model->created_at)
    ]); ?>
    
    <div class="form-group buttons">
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>