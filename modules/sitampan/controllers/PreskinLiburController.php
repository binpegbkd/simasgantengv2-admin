<?php

namespace app\modules\sitampan\controllers;

use Yii;
use app\modules\sitampan\models\PreskinLibur;
use app\modules\sitampan\models\PreskinLiburSearch;
use app\modules\sitampan\models\PreskinLiburJenis;
use app\models\Fungsi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * PreskinLiburController implements the CRUD actions for PreskinLibur model.
 */
class PreskinLiburController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Lists all PreskinLibur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PreskinLiburSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('PreskinLiburSearch') === null)
            $dataProvider->query->andFilterWhere(['EXTRACT(YEAR FROM DATE(tanggal))' => 2024]);            
        
        $dataProvider->query->orderBy(['tanggal' => SORT_ASC]);

        $ket_libur = PreskinLiburJenis::find()->all();

        Url::remember('index');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ket_libur' => $ket_libur, 
        ]);
    }

    /**
     * Displays a single PreskinLibur model.
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
     * Creates a new PreskinLibur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PreskinLibur();
        $ket_libur = PreskinLiburJenis::find()->where(['>', 'id', 2])->all();

        if ($model->load(Yii::$app->request->post())) {
            $tanggal = $model['tanggal'];
            //$ket = $model['ket_libur'];
            //$det = $model['detail'];
            $cek = PreskinLibur::findOne($tanggal);
            if(!$cek){
                $model->save();
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
            } else Yii::$app->session->setFlash('danger', 'Tanggal : '.Fungsi::tgldmy($tanggal).' sudah ada.');
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model, 'ket_libur' => $ket_libur, 
            ]);
        }else{
            return $this->render('create', [
                'model' => $model, 'ket_libur' => $ket_libur, 
            ]);
        } 
    }

    /**
     * Updates an existing PreskinLibur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $ket_libur = PreskinLiburJenis::find()->where(['>', 'id', 2])->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->session->setFlash('success', 'Data berhasil diubah.'); 
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model, 'ket_libur' => $ket_libur, 
            ]);
        }else{
            return $this->render('update', [
                'model' => $model, 'ket_libur' => $ket_libur, 
            ]);
        } 
    }

    public function actionSabtuMinggu()
    {
        $model = new PreskinLibur();

        if ($model->load(Yii::$app->request->post())) {  
            //$tahun = Yii::$app->request->post('tanggal');     
            $tahun = $model['tanggal'];

            $date1 = "01-01-".$tahun;
            $date2 = "31-12-".$tahun;
            
            // memecah bagian-bagian dari tanggal $date1
            $pecahTgl1 = explode("-", $date1);
            
            // membaca bagian-bagian dari $date1
            $tgl1 = $pecahTgl1[0];
            $bln1 = $pecahTgl1[1];
            $thn1 = $pecahTgl1[2];   
            
            $i = 0; // counter looping
            $sum = 0; // counter untuk jumlah hari sabtu-minggu
            do{
                $tanggal = date("Y-m-d", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)); // mengenerate tanggal berikutnya
                
                // cek jika harinya minggu, maka counter $sum bertambah satu, lalu tampilkan tanggalnya
                if (date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 0 || date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 6){
                    //echo $tanggal."<br>";
                    $cek = PreskinLibur::findOne($tanggal);
                    if(!$cek){
                        if (date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 0) $ket = 2;
                        if (date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 6) $ket = 1;
                        $sum++;

                        $simpan = new PreskinLibur();
                        $simpan['tanggal'] = $tanggal;
                        $simpan['ket_libur'] = $ket;
                        $simpan->save();
                    }
                }                 
                // increment untuk counter looping
                $i++;
            }
            while ($tanggal != Fungsi::tglymd($date2));           
            if($sum != 0)
                Yii::$app->session->setFlash('success', $sum.' data telah ditambahkan');
            else Yii::$app->session->setFlash('danger', 'Data tahun '.$tahun.' sudah ada'); 
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('weekend', [
                'model' => $model,
            ]);
        }else{
            return $this->render('weekend', [
                'model' => $model,
            ]);
        } 
    }

    /**
     * Deletes an existing PreskinLibur model.
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
     * Finds the PreskinLibur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PreskinLibur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PreskinLibur::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
