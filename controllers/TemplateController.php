<?php

namespace infoweb\email\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\base\Model;

use infoweb\email\models\Template;
use infoweb\email\models\TemplateLang;
use infoweb\email\models\TemplateSearch;

/**
 * TemplateController implements the CRUD actions for Template model.
 */
class TemplateController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' news.
     * @return mixed
     */
    public function actionCreate()
    {
        $languages = Yii::$app->params['languages'];

        // Load the model and it's default values
        $model = new Template([]);

        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
            
            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {

                // Populate the model with the POST data
                $model->load($post);

                // Create an array of translation models
                $translationModels = [];

                foreach ($languages as $languageId => $languageName) {
                    $translationModels[$languageId] = new TemplateLang(['language' => $languageId]);
                }

                // Populate the translation models
                Model::loadMultiple($translationModels, $post);

                // Validate the model and translation models
                $response = array_merge(
                    ActiveForm::validate($model),
                    ActiveForm::validateMultiple($translationModels)
                );
                
                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            
            // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();                
                
                // Save the main model
                if (!$model->load($post) || !$model->save()) {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                // Save the translations
                foreach ($languages as $languageId => $languageName) {
                    
                    $data = $post['TemplateLang'][$languageId];
                    
                    // Set the translation language and attributes                    
                    $model->language = $languageId;
                    $model->to = $data['to'];
                    $model->bcc = $data['bcc'];
                    $model->from = $data['from'];
                    $model->subject = $data['subject'];
                    $model->message = $data['message'];  
                    
                    if (!$model->saveTranslation()) {
                        return $this->render('create', [
                            'model' => $model,
                        ]);    
                    }
                }

                $transaction->commit();

                // Switch back to the main language
                $model->language = Yii::$app->language;

                // Set flash message
                Yii::$app->getSession()->setFlash('news', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));

                // Take appropriate action based on the pushed button
                if (isset($post['close'])) {
                    return $this->redirect(['index']);
                } elseif (isset($post['new'])) {
                    return $this->redirect(['create']);
                } else {
                    return $this->redirect(['update', 'id' => $model->id]);
                }   
            }    
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' news.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $languages = Yii::$app->params['languages'];
        $model = $this->findModel($id);

        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();

            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {
                               
                // Populate the model with the POST data
                $model->load($post);
                
                // Create an array of translation models
                $translationModels = [];
                
                foreach ($languages as $languageId => $languageName) {
                    $translationModels[$languageId] = $model->getTranslation($languageId);
                }
                
                // Populate the translation models
                Model::loadMultiple($translationModels, $post);

                // Validate the model and translation and alias models
                $response = array_merge(
                    ActiveForm::validate($model),
                    ActiveForm::validateMultiple($translationModels)
                );
                
                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;

            // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();                
                
                // Save the main model
                if (!$model->load($post) || !$model->save()) {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                } 

                // Save the translation models and seo tags
                foreach ($languages as $languageId => $languageName) {
                    // Save the translation
                    $data = $post['TemplateLang'][$languageId];

                    // Set the translation language and attributes  
                    $model->language = $languageId;
                    $model->to = $data['to'];
                    $model->bcc = $data['bcc'];
                    $model->from = $data['from'];
                    $model->subject = $data['subject'];
                    $model->message = $data['message'];

                    if (!$model->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                        ]);    
                    }
                }

                $transaction->commit();

                // Switch back to the main language
                $model->language = Yii::$app->language;

                // Set flash message
                Yii::$app->getSession()->setFlash('news', Yii::t('app', '"{item}" has been updated', ['item' => $model->name]));

                // Take appropriate action based on the pushed button
                if (isset($post['close'])) {
                    return $this->redirect(['index']);
                } elseif (isset($post['new'])) {
                    return $this->redirect(['create']);
                } else {
                    return $this->redirect(['update', 'id' => $model->id]);
                }    
            }    
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' news.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        
        // Set flash message
        Yii::$app->getSession()->setFlash('news', Yii::t('app', '{item} has been deleted', ['item' => $model->name]));

        return $this->redirect(['index']);
    }
    
    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
        }
    }
}
