<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
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
    
    <?= $form->field($model, 'form')->textInput([
        'readonly' => true
    ]); ?>
    
    <?= $form->field($model, 'created_at')->textInput([
        'readonly' => true,
        'value' => Yii::$app->formatter->asDate($model->created_at, 'php:d-m-Y H:i')
    ]); ?>
       
    <div class="form-group">
        <label class="control-label"><?php echo Yii::t('infoweb/email', 'Message'); ?></label>
        <iframe class="form-control" src="<?php echo Url::to(['message', 'id' => $model->id]); ?>" width="100%" frameborder="0" onload="CMS.autoSizeIframe(this, 20)" style="padding: 0;"></iframe>
    </div>
    
    <div class="form-group buttons">
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>