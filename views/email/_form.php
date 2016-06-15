<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use infoweb\email\models\Email;
?>
<div class="email-form">

    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php $form = ActiveForm::begin(['id' => 'email-form', 'enableAjaxValidation' => false, 'enableClientValidation' => false]); ?>

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
    ])->label((Yii::$app->session->get('emails.actionType') != Email::ACTION_SENT) ? Yii::t('infoweb/email', 'Received at') : Yii::t('infoweb/email', 'Send at')); ?>

    <?php if ($model->getHistory()->resent()->count()) : ?>
    <div class="form-group">
        <label class="control-label"><?= Yii::t('infoweb/email', 'History'); ?></label>
        <ul>
            <?php foreach($model->getHistory()->resent()->all() as $history) : ?>
            <li><?= Yii::t('infoweb/email', 'Resent at {date}', ['date' => Yii::$app->formatter->asDate($history->created_at, 'php:d-m-Y H:i')]); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <label class="control-label"><?php echo Yii::t('infoweb/email', 'Message'); ?></label>
        <iframe class="form-control" src="<?php echo Url::to(['message', 'id' => $model->id]); ?>" width="100%" frameborder="0" onload="CMS.autoSizeIframe(this, 20)" style="padding: 0;"></iframe>
    </div>

    <div class="form-group buttons">
        <?php if ($model->action == Email::ACTION_SENT) : ?>
        <?= Html::submitButton(Yii::t('infoweb/email', 'Resend'), ['class' => 'btn btn-primary', 'name' => 'resend']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>