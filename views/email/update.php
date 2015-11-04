<?php
use yii\helpers\Html;
use infoweb\email\models\Email;

$this->title = Yii::t('app', 'View {modelClass}', [
    'modelClass' => Yii::t('infoweb/email', 'Email'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/email', 'Emails') . ': ' . strtolower(Email::actionTypes()[Yii::$app->session->get('emails.actionType')]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "#{$model->id}", 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<div class="email-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
