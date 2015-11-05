<?php

use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\helpers\Html;
use infoweb\email\models\Email;
use infoweb\email\assets\EmailAsset;

EmailAsset::register($this);

$this->title = Yii::t('infoweb/email', 'Emails');
$this->params['breadcrumbs'][] = $this->title . ': ' . strtolower(Email::actionTypes()[Yii::$app->session->get('emails.actionType')]);
?>
<div class="email-index">

    <?php // Title ?>
    <h1>
        <?= Html::encode($this->title) ?>
        : <?php echo Html::radioButtonGroup(
            'actionType',
            Yii::$app->session->get('emails.actionType'),
            Email::actionTypes()
        ); ?>
        <?php // Buttons ?>
        <div class="pull-right">
            <?= Html::button(Yii::t('infoweb/email', 'Mark as read'), [
                'class' => 'btn btn-danger',
                'id' => 'batch-read',
                'data-pjax' => 0,
                'style' => 'display: none;'
            ]) ?>
        </div>
    </h1>

    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php // Gridview ?>
    <?php Pjax::begin(['id'=>'grid-pjax']); ?>
    <?php echo GridView::widget([
        'id' => 'gridview',
        'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'visible' => (Yii::$app->session->get('emails.actionType') != Email::ACTION_SENT) ? true : false,
                'checkboxOptions' => function($model) {
                    return [
                        'disabled' => (!$model->read) ? false : true
                    ];
                }
            ],
            'subject',
            [
                'attribute' => (Yii::$app->session->get('emails.actionType') != Email::ACTION_SENT) ? 'from' : 'to',
                'label' => (Yii::$app->session->get('emails.actionType') != Email::ACTION_SENT) ? Yii::t('infoweb/email', 'From') : Yii::t('infoweb/email', 'To'),
            ],
            'form',
            [
                'attribute'=>'created_at',
                'label' => (Yii::$app->session->get('emails.actionType') != Email::ACTION_SENT) ? Yii::t('infoweb/email', 'Received at') : Yii::t('infoweb/email', 'Send at'),
                'value'=>function ($model, $index, $widget) {
                    return Yii::$app->formatter->asDate($model->created_at);
                },
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ],
                'width' => '160px',
                'hAlign' => 'center'
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'updateOptions' => ['title' => Yii::t('app', 'View'), 'data-toggle' => 'tooltip'],
                'deleteOptions'=>['title' => Yii::t('app', 'Delete'), 'data-toggle' => 'tooltip'],
                'width' => '120px',
            ],
        ],
        'responsive' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => 88],
        'hover' => true,
        'export' => false,
        'rowOptions' => function($model, $key, $index, $row) {
            return [
                'class' => (!$model->read) ? 'unread' : 'read'
            ];
        }
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
<span class="hidden" id="bootbox-batch-read-msg"><?php echo Yii::t('infoweb/email', 'Mark as read'); ?>?</span>