<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use infoweb\email\assets\EmailAsset;

EmailAsset::register($this);

$this->title = Yii::t('infoweb/email', 'Emails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-index">

    <?php // Title ?>
    <h1>
        <?= Html::encode($this->title) ?>
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
                'checkboxOptions' => function($model) {
                    return [
                        'disabled' => (!$model->read) ? false : true
                    ];
                }
            ],
            'subject',
            'from',
            'form',
            [
                'attribute'=>'created_at',
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