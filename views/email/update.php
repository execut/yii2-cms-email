<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'View {modelClass}: ', [
    'modelClass' => Yii::t('infoweb/email', 'Email'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/email', 'Emails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "#{$model->id}", 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<div class="email-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
