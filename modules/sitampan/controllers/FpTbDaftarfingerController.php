<?php

namespace app\modules\sitampan\controllers;

use Yii;
use app\modules\sitampan\models\FpTbDaftarfinger;
use app\modules\sitampan\models\FpTbDaftarfingerSearch;
use app\modules\sitampan\models\FpTbAlamat2;
use app\modules\sitampan\models\FpLogAktivitas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * FpTbDaftarfingerController implements the CRUD actions for FpTbDaftarfinger model.
 */
class FpTbDaftarfingerController extends Controller
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
                        'matchCallback'=>function(){
                            if(Yii::$app->session['module'] != 7)  return $this->redirect(['/']);
                            else return (Yii::$app->session['module'] == 7);
                        },
                    ],
                ],                   
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'device' => ['POST'],
                    'password' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all FpTbDaftarfinger models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FpTbDaftarfingerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['kodealamat' => SORT_ASC]);

        $lokasi = FpTbAlamat2::find()->orderBy(['kodealamat' => SORT_ASC])->all();

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'lokasi' => $lokasi,
        ]);
    }

    /**
     * Displays a single FpTbDaftarfinger model.
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
     * Creates a new FpTbDaftarfinger model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FpTbDaftarfinger();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
     * Updates an existing FpTbDaftarfinger model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPassword($id)
    {
        $model = $this->findModel($id);
        $model['password'] = md5(substr($model['nip'],0,8));
        $model->save(false);

        $log = new FpLogAktivitas();
        $log['kode'] = $model['nip'].'-'.time();
        $log['nip'] = $model['nip'];
        $log['tgl'] = date('Y-m-d H:i:s');
        $log['aktivitas'] = 4;
        $log->save(false);

        Yii::$app->session->setFlash('position', [
            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
            'title' => 'Berhasil',
            'text' => 'Reset password berhasil !!!',
            'showConfirmButton' => false,
            'timer' => 1000
        ]);
        return $this->redirect(Url::previous());
    }

    public function actionDevice($id)
    {
        $model = $this->findModel($id);
        $model['device'] = '';
        $model->save(false);

        $log = new FpLogAktivitas();
        $log['kode'] = $model['nip'].'-'.time();
        $log['nip'] = $model['nip'];
        $log['tgl'] = date('Y-m-d H:i:s');
        $log['aktivitas'] = 3;
        $log->save(false);

        Yii::$app->session->setFlash('position', [
            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
            'title' => 'Berhasil',
            'text' => 'Reset Device berhasil !!!',
            'showConfirmButton' => false,
            'timer' => 1000
        ]);
        return $this->redirect(Url::previous());
    }

    public function actionLokasi($id)
    {
        $model = $this->findModel($id);

        $lok = FpTbAlamat2::find()->orderBy(['alamat' => SORT_ASC])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $log = new FpLogAktivitas();
            $log['kode'] = $model['nip'].'-'.time();
            $log['nip'] = $model['nip'];
            $log['tgl'] = date('Y-m-d H:i:s');
            $log['aktivitas'] = 2;
            $log->save(false);

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Lokasi Presensi Berhasil diubah !!!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('lokasi', [
                'model' => $model, 
                'lok' => $lok,
            ]);
        }else{
            return $this->render('lokasi', [
                'model' => $model, 
                'lok' => $lok,
            ]);
        } 
    }

    /**
     * Deletes an existing FpTbDaftarfinger model.
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
     * Finds the FpTbDaftarfinger model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FpTbDaftarfinger the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FpTbDaftarfinger::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
