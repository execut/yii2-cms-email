<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\export\ExportMenu;
use infoweb\email\models\Email;
use infoweb\email\assets\EmailAsset;

EmailAsset::register($this);

$this->title = Yii::t('infoweb/email', 'Emails');
$this->params['breadcrumbs'][] = $this->title;

$buttonsTemplate = (Yii::$app->session->get('emails.actionType') != Email::ACTION_SENT) ? '{update} {delete}' : '{update} {delete} {resend}';

// Set the gridColumns
$gridColumns = [
    [
        'class' => '\kartik\grid\CheckboxColumn',
        'visible' => (Yii::$app->session->get('emails.actionType') != Email::ACTION_SENT) ? true : false,
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
        'value' => function ($model, $index, $widget) {
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
    'rep',
    'profession',
    [
        'attribute' => 'registrated',
        'label' => Yii::t('infoweb/email', 'Registrated'),
        'value' => function ($model, $index, $widget) {
            if ($model->form != 'Sanmax app') {
                return '';
            }

            return $model->isProcessedByTheRecipient() ? Yii::t('infoweb/email', 'Yes') : Yii::t('infoweb/email', 'No');
        },
        'filter' => [
            Yii::t('infoweb/email', 'Yes') => Yii::t('infoweb/email', 'Yes'),
            Yii::t('infoweb/email', 'No') => Yii::t('infoweb/email', 'No')
        ],
        'visible' => (Yii::$app->session->get('emails.actionType') != Email::ACTION_RECEIVED) ? true : false
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => $buttonsTemplate,
        'buttons' => [
            'resend' => function ($url, $model) {
                if (Yii::$app->session->get('emails.actionType') != Email::ACTION_SENT) {
                    return false;
                }
                $resent = $model->getHistory()->resent()->count();
                $resentLabel = '';
                if ($resent) {
                    $resentLabel = "&nbsp;<span class=\"label label-danger\">{$resent}</span>";
                }
                return Html::a("<span class=\"glyphicon glyphicon-send\"></span>{$resentLabel}", Url::toRoute(['email/update', 'id' => $model->id]), [
                    'title' => Yii::t('infoweb/email', 'Resend'),
                    'data-pjax' => '0',
                    'data-toggle' => 'tooltip',
                    'class' => 'resend-btn'
                ]);
            },
        ],
        'updateOptions' => ['title' => Yii::t('app', 'View'), 'data-toggle' => 'tooltip'],
        'deleteOptions'=>['title' => Yii::t('app', 'Delete'), 'data-toggle' => 'tooltip'],
        'width' => '120px',
    ],
];
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
        <div class="navbar-right">
            <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'target' => ExportMenu::TARGET_SELF,
                'showConfirmAlert' => false,
                'noExportColumns' => [0,8],
                'fontAwesome' => true,
                'columnBatchToggleSettings' => [
                    'show' => false
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_CSV => [],
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_EXCEL => false,
                    ExportMenu::FORMAT_EXCEL_X => []
                ],
                'filename' => 'export',
                'i18n' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/infoweb-internet-solutions/yii2-cms-email/messages',
                    'forceTranslation' => true,
                ],
                'onRenderDataCell' => function($cell, $content, $model, $key, $index, $grid) {
                    if (!$model->isProcessedByTheRecipient()) {
                        $cell->getStyle()->applyFromArray([
                            'fill' => [
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => ['rgb' => 'F5CACD']
                            ]
                        ]);
                    }
                }
            ]); ?>

            <?= Html::button(Yii::t('infoweb/email', 'Mark as read'), [
                'class' => 'btn btn-info',
                'id' => 'batch-read',
                'data-pjax' => 0,
                'style' => 'display: none;'
            ]) ?>

            <?= Html::button(Yii::t('app', 'Delete'), [
                'class' => 'btn btn-danger',
                'id' => 'batch-delete',
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
        'columns' => $gridColumns,
        'rowOptions' => function($model, $key, $index, $row) {
            $class = (!$model->read && Yii::$app->session->get('emails.actionType') == Email::ACTION_RECEIVED) ? 'unread' : 'read';
            $class .= ($model->isProcessedByTheRecipient()) ? ' processed' : ' not-processed';
            return [
                'class' => $class
            ];
        }
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
<span class="hidden" id="bootbox-batch-read-msg"><?php echo Yii::t('infoweb/email', 'Mark as read'); ?>?</span>
<span class="hidden" id="bootbox-batch-delete-msg"><?php echo Yii::t('app', 'Are you sure you want to delete this item(s)'); ?>?</span>
