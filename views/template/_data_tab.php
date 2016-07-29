<?php
use kartik\widgets\SwitchInput;
use kartik\datecontrol\DateControl;
?>
<div class="tab-content data-tab">

    <?= $form->field($model, 'type')->dropDownList([
        'system' => Yii::t('app', 'System'),
        'user-defined' => Yii::t('app', 'User defined')
    ], [
        'options' => [
            'system' => ['disabled' => (Yii::$app->user->can('Superadmin')) ? false : true],
            'user-defined' => ['disabled' => ($model->type == 'system' && !Yii::$app->user->can('Superadmin')) ? true : false],
        ]
    ]); ?>

    <?= $form->field($model, 'action')->dropDownList($model->getActions(), []); ?>

    <?= $form->field($model, "name")->textInput(); ?>

</div>
