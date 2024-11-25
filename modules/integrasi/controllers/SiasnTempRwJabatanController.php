<?php

namespace app\modules\integrasi\controllers;

use Yii;
use app\modules\integrasi\models\SiasnTempRwJabatan;
use app\modules\integrasi\models\SiasnTempRwJabatanSearch;
use app\modules\integrasi\models\SiasnConfig;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * SiasnTempRwJabatanController implements the CRUD actions for SiasnTempRwJabatan model.
 */
class SiasnTempRwJabatanController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],                   
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
     * Lists all SiasnTempRwJabatan models.
     * @return mixed
     */
    public function actionIndex($nip)
    {
        $searchModel = new SiasnTempRwJabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Url::remember('index');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'nip' => $nip,
        ]);
    }

    /**
     * Displays a single SiasnTempRwJabatan model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new SiasnTempRwJabatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($nip)
    {
        $sess = Yii::$app->session;

        $pns = \app\modules\integrasi\models\SiasnDataUtama::find()
        ->where(['nipBaru' => $nip])->one();

        $model = new SiasnTempRwJabatan([
            'flag' => 0,
            'by' => $sess['nip'],
            'updated' => date('Y-m-d H:i:s'),
            'instansiId' => 'A5EB03E23C55F6A0E040640A040252AD',
            'satuanKerjaId' => 'A8ACA73E4B7F3912E040640A040269BB',
            'pnsId' => $pns['id'],
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $end = '/jabatan/unorjabatan/save';
            $mode = 'prod';

            $ws = SiasnConfig::getTokenWSO2($mode);
            $sso = SiasnConfig::getTokenSSO($sess['nip'], $sess['password'], $mode);

            $result = SiasnConfig::postDok($mode, $ws['token_key'], $sso['token_sso'], $end, $model);
            
            $siasn = Json::decode($result);

            // $model->save();
            Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('create', [
                'model' => $model, 
            ]);
        } 
    }

    /**
     * Updates an existing SiasnTempRwJabatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil diubah.');
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('update', [
                'model' => $model, 
            ]);
        } 
    }

    /**
     * Deletes an existing SiasnTempRwJabatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Data berhasil dihapus.');
        return $this->redirect(Url::previous());
    }

    /**
     * Finds the SiasnTempRwJabatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SiasnTempRwJabatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiasnTempRwJabatan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
