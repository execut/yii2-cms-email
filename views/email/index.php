<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

$this->title = Yii::t('infoweb/email', 'Emails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-index">

    <?php // Title ?>
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    
    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php // Gridview ?>
    <?php echo GridView::widget([
        'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'subject',
            'from',
            [
                'attribute'=>'create_at',
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
                'hAlign' => 'center',
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'read',
                'vAlign'=> 'middle'
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {read}',
                'buttons' => [
                    'read' => function ($url, $model) {
                        if ($model->read == true) {
                            $icon = 'glyphicon-eye-open';
                        } else {
                            $icon = 'glyphicon-eye-close';
                        }

                        return Html::a('<span class="glyphicon ' . $icon . '"></span>', $url, [
                            'title' => ($model->read == true) ? Yii::t('infoweb/email', 'Mark as not read') : Yii::t('infoweb/email', 'Mark as read'),
                            'data-pjax' => '0',
                            'data-toggleable' => 'true',
                            'data-toggle-id' => $model->id,
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                ],
                'updateOptions' => ['title' => Yii::t('app', 'View'), 'data-toggle' => 'tooltip'],
                'width' => '120px',
            ],
        ],
        'responsive' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => 88],
        'hover' => true,
        'pjax' => true,
        'export' => false,
    ]);
    ?>

</div>