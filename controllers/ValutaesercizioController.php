<?php

namespace app\controllers;

use app\models\Valutaesercizio;
use app\models\ValutaesercizioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;

/**
 * ValutaesercizioController implements the CRUD actions for Valutaesercizio model.
 */
class ValutaesercizioController extends Controller
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
     * Lists all Valutaesercizio models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ValutaesercizioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Valutaesercizio model.
     * @param int $IdCaregiver Id Caregiver
     * @param int $IdEsercizio Id Esercizio
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($IdCaregiver, $IdEsercizio)
    {
        return $this->render('view', [
            'model' => $this->findModel($IdCaregiver, $IdEsercizio),
        ]);
    }

    /**
     * Creates a new Valutaesercizio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Valutaesercizio();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'IdCaregiver' => $model->IdCaregiver, 'IdEsercizio' => $model->IdEsercizio]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Valutaesercizio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $IdCaregiver Id Caregiver
     * @param int $IdEsercizio Id Esercizio
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($IdCaregiver, $IdEsercizio)
    {
        $model = $this->findModel($IdCaregiver, $IdEsercizio);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'IdCaregiver' => $model->IdCaregiver, 'IdEsercizio' => $model->IdEsercizio]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Valutaesercizio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $IdCaregiver Id Caregiver
     * @param int $IdEsercizio Id Esercizio
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($IdCaregiver, $IdEsercizio)
    {
        $this->findModel($IdCaregiver, $IdEsercizio)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Valutaesercizio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $IdCaregiver Id Caregiver
     * @param int $IdEsercizio Id Esercizio
     * @return Valutaesercizio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($IdCaregiver, $IdEsercizio)
    {
        if (($model = Valutaesercizio::findOne(['IdCaregiver' => $IdCaregiver, 'IdEsercizio' => $IdEsercizio])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
