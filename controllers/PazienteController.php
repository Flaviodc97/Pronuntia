<?php

namespace app\controllers;

use app\models\Paziente;
use app\models\PazienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PazienteController implements the CRUD actions for Paziente model.
 */
class PazienteController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete','view', 'index'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete', 'view', 'index'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Paziente models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PazienteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Paziente model.
     * @param int $IdPaziente Id Paziente
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($IdPaziente)
    {
        return $this->render('view', [
            'model' => $this->findModel($IdPaziente),
        ]);
    }

    /**
     * Creates a new Paziente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Paziente();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'IdPaziente' => $model->IdPaziente]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Paziente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $IdPaziente Id Paziente
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($IdPaziente)
    {
        $model = $this->findModel($IdPaziente);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'IdPaziente' => $model->IdPaziente]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Paziente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $IdPaziente Id Paziente
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($IdPaziente)
    {
        $this->findModel($IdPaziente)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Paziente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $IdPaziente Id Paziente
     * @return Paziente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($IdPaziente)
    {
        if (($model = Paziente::findOne(['IdPaziente' => $IdPaziente])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
