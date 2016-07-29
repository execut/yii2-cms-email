<?php
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use infoweb\cms\helpers\LanguageHelper;
?>
<div class="tab-content default-language-tab">

    <div class="template-email-to">
        <?= $form->field($model, "[{$model->language}]to")->textInput([
            'name' => "TemplateLang[{$model->language}][to]",
            'data-duplicateable' => $allowContentDuplication ? 'true' : 'false'
        ]); ?>
    </div>

    <?= $form->field($model, "[{$model->language}]bcc")->textInput([
        'name' => "TemplateLang[{$model->language}][bcc]",
        'data-duplicateable' => $allowContentDuplication ? 'true' : 'false'
    ]); ?>

    <?= $form->field($model, "[{$model->language}]from")->textInput([
        'name' => "TemplateLang[{$model->language}][from]",
        'data-duplicateable' => $allowContentDuplication ? 'true' : 'false'
    ]); ?>

    <?= $form->field($model, "[{$model->language}]subject")->textInput([
        'name' => "TemplateLang[{$model->language}][subject]",
        'data-duplicateable' => $allowContentDuplication ? 'true' : 'false'
    ]); ?>

    <?= $form->field($model, "[{$model->language}]message")->widget(CKEditor::className(), [
        'name' => "TemplateLang[{$model->language}][message]",
        'editorOptions' => ArrayHelper::merge(Yii::$app->getModule('cms')->getCKEditorOptions(), Yii::$app->getModule('email')->ckEditorOptions, (LanguageHelper::isRtl($model->language)) ? ['contentsLangDirection' => 'rtl'] : []),
        'options' => ['data-duplicateable' => $allowContentDuplication ? 'true' : 'false'],
    ]); ?>
</div>