<?php

namespace infoweb\email\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\base\Model;
use infoweb\email\models\Email;
use infoweb\email\models\EmailSearch;

class EmailController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'read' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        // Store the action type if it is provided through the url
        if (Yii::$app->request->get('actionType', null) !== null) {
            Yii::$app->session->set('emails.actionType', Yii::$app->request->get('actionType'));;
        }

        // Fall back to the default action if no action type is set
        if (Yii::$app->session->get('emails.actionType', null) === null) {
            Yii::$app->session->set('emails.actionType', Email::ACTION_RECEIVED);
        }

        $searchModel = new EmailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $languages = Yii::$app->params['languages'];
        $model = $this->findModel($id);

        // If the message is not read yet, mark it as read
        if (!$model->read) {
            $model->markAsRead();
        }

        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();

            if (isset($post['close']))
                return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionRead()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->read = 1;
        $model->read_at = time();

        return $model->save();
    }

    public function actionMessage($id)
    {
        $model = $this->findModel($id);

        return $model->message;
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        // Set flash message
        Yii::$app->getSession()->setFlash('email', Yii::t('app', 'The item has been deleted'));

        return $this->redirect(['index']);
    }

    public function actionBatchDelete()
    {
        $data = [
            'status'    => 0,
            'message'   => '',
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            $ids = Yii::$app->request->post('ids');

            Email::deleteAll(['in', 'id', $ids]);

            // Set flash message
            $data = [
                'status'    => 1,
                'message'   => '',
            ];
        }

        return $data;
    }

    public function actionBatchRead()
    {
        $data = [
            'status'    => 0,
            'message'   => '',
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            $ids = Yii::$app->request->post('ids');

            Email::updateAll(['read' => 1, 'read_at' => time()], ['id' => $ids]);

            // Set flash message
            $data = [
                'status'    => 1,
                'message'   => '',
            ];
        }

        return $data;
    }

    protected function findModel($id)
    {
        if (($model = Email::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
        }
    }
}