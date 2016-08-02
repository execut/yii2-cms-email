<?php
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use infoweb\cms\helpers\LanguageHelper;
?>
<div class="tab-content default-language-tab">

    <div class="template-email-to">
        <?= $form->field($model, "[{$model->language}]to")->textInput([
            'name' => "TemplateLang[{$model->language}][to]"
        ]); ?>
    </div>

    <?= $form->field($model, "[{$model->language}]bcc")->textInput([
        'name' => "TemplateLang[{$model->language}][bcc]"
    ]); ?>

    <?= $form->field($model, "[{$model->language}]from")->textInput([
        'name' => "TemplateLang[{$model->language}][from]"
    ]); ?>

    <?= $form->field($model, "[{$model->language}]subject")->textInput([
        'name' => "TemplateLang[{$model->language}][subject]"
    ]); ?>
    <p style="margin-top: -10px;" class="help-block"><?= Yii::t('infoweb/email', 'Tags'); ?>: <?= isset($template->supported_tags) ? $template->supported_tags : ''; ?></p>

    <?= $form->field($model, "[{$model->language}]message")->widget(CKEditor::className(), [
        'name' => "TemplateLang[{$model->language}][message]",
        'editorOptions' => ArrayHelper::merge(Yii::$app->getModule('cms')->getCKEditorOptions(), Yii::$app->getModule('email')->ckEditorOptions, (LanguageHelper::isRtl($model->language)) ? ['contentsLangDirection' => 'rtl'] : []),
        'options' => ['data-duplicateable' => Yii::$app->getModule('email')->allowContentDuplication ? 'true' : 'false']
    ]); ?>
    <p style="margin-top: -10px;" class="help-block"><?= Yii::t('infoweb/email', 'Tags'); ?>: <?= isset($template->supported_tags) ? $template->supported_tags : ''; ?></p>
</div>