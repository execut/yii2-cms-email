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

    protected function findModel($id)
    {
        if (($model = Email::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
        }
    }
}