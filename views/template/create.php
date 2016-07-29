<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model infoweb\email\models\Template */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('infoweb/email', 'Template'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/email', 'Template'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'allowContentDuplication' => $allowContentDuplication
    ]) ?>

</div>