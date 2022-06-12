<?php

namespace app\controllers;
use Yii;
use app\models\Esercizio;
use app\models\EsercizioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
/**
 * EsercizioController implements the CRUD actions for Esercizio model.
 */
class EsercizioController extends Controller
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
     * Lists all Esercizio models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EsercizioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Esercizio model.
     * @param int $IdEsercizio Id Esercizio
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($IdEsercizio)
    {
        return $this->render('view', [
            'model' => $this->findModel($IdEsercizio),
        ]);
    }

    /**
     * Creates a new Esercizio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Esercizio();
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
         
            return $this->redirect(['view', 'IdEsercizio' => $model->IdEsercizio]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Esercizio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $IdEsercizio Id Esercizio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($IdEsercizio)
    {
        $model = $this->findModel($IdEsercizio);
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'IdEsercizio' => $model->IdEsercizio]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Esercizio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $IdEsercizio Id Esercizio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($IdEsercizio)
    {
        $this->findModel($IdEsercizio)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Esercizio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $IdEsercizio Id Esercizio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($IdEsercizio)
    {
        if (($model = Esercizio::findOne($IdEsercizio)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
