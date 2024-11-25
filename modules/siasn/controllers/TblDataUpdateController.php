<?php

namespace app\modules\siasn\controllers;

use Yii;
use app\modules\integrasi\models\TblDataUpdate;
use app\modules\integrasi\models\TblDataUpdateSearch;
use app\modules\integrasi\models\SiasnDataUtama;
use app\modules\integrasi\models\SiasnIntegrasiConfig;
use app\modules\integrasi\models\SiasnRefUnor;
use app\modules\integrasi\models\TblHist;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;

use app\modules\gaji\models\SimasGajiMstpegawai;

use app\modules\simpeg\models\EpsMastfip;
use app\modules\simpeg\models\SimpegEpsMastfip;

use app\modules\sitampan\models\PreskinAsn;

use app\models\Tblasn;
use app\models\User;
use app\models\Fungsi;

use app\modules\integrasi\models\ExcelForm;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * TblDataUpdateController implements the CRUD actions for TblDataUpdate model.
 */
class TblDataUpdateController extends Controller
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
                            if(Yii::$app->session['module'] != 4)  return $this->redirect(['/']);
                            else return Yii::$app->session['module'] == 4;
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
     * Lists all TblDataUpdate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblDataUpdateSearch(['flag' => 0]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy([
            'flag' => SORT_ASC,
            'dataUtama' => SORT_DESC,
        ]);

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDataUpdate model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('view', [
                'model' => $model, 
            ]);
        } 
    }

    /**
     * Creates a new TblDataUpdate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblDataUpdate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Berhasil !!!',
                'text' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
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
     * Updates an existing TblDataUpdate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Berhasil !!!',
                'text' => 'Data berhasil diubah',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
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
     * Deletes an existing TblDataUpdate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('position', [
            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
            'title' => 'Berhasil !!!',
            'text' => 'Data berhasil dihapus',
            'showConfirmButton' => false,
            'timer' => 1000
        ]); 
        return $this->redirect(Url::previous());
    }

    /**
     * Finds the TblDataUpdate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDataUpdate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblDataUpdate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelByNip($id)
    {
        if (($model = TblDataUpdate::find()->where(['nipBaru' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSyncDataUtama($nip)
    {
        $model = $this->findModelByNip($nip);

        $siasn = ['code' => 0, 'data'=> '', 'message' => null];

        $siasn = SiasnIntegrasiConfig::getDataSiasn($nip, 'prod', '/pns/data-utama/');

        if(! array_key_exists('code', $siasn)) {
            $model['flag'] = 99;
            $model->save(false);
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'ERROR',
                'text' => Json::encode($siasn, $asArray = true),
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
            return $this->redirect(Url::previous());
        }

        if($siasn['code'] != 1){
            
            $siasn['data'] = '';
            $model['flag'] = 99;
            $model->save(false);
            
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Data tidak ditemukan',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
        }else{
            //return Json::encode($siasn, $asArray = true);
            
            $data = $siasn['data'];
            $data['updated']  = date('Y-m-d H:i:s');  

            $sapkpns = SiasnDataUtama::findOne($data['id']);
            //$sapkpns = SiasnDataUtama::findOne($nip);

            if($sapkpns === null) $sapkpns = new SiasnDataUtama();

            foreach($data as $attr => $value){
                if($data["$attr"] == 'null') $data["$attr"] = NULL;
                if($attr != 'path'){
                    if(isset($sapkpns["$attr"])){
                        $sapkpns["$attr"] = $data["$attr"];
                    }
                    if($attr == 'tanggal_taspen') $sapkpns["$attr"] = date('Y-m-d H:i:s', strtotime($data["$attr"]));
                }
            }

            $log = new TblHist();
            $log['id'] = $sapkpns['id'];
            $log['oleh'] = Yii::$app->session['username'];
            $log['data'] = 'data-utama';
            $log['aksi'] = 'all';

            $model['id'] = $sapkpns['id'];
            $model['nama'] = $sapkpns['nama'];
            $model['dataUtama'] = date('Y-m-d H:i:s');
            if($sapkpns['kedudukanPnsId'] == '99') {
                $model['flag'] = 99;
                $sapkpns['flag'] = 99;
            }
            
            if($sapkpns->save(false) && $log->save(false) && $model->save(false)){
                
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Berhasil !!!',
                    'text' => 'Data berhasil disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }else{
                $model['flag'] = 99;
                $model->save(false);
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Gagal !!!',
                    'text' => 'Data gagal disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }

        }

        return $this->redirect(['index']);
    }

    public function actionSimpeg()
    {
        $mdl = EpsMastfip::find()->select(['B_02', 'B_03', 'B_08'])
            //->where(['<', 'B_08', 3])
        ;

        foreach($mdl->all() as $dt){
            $nip = $dt['B_02'];
            $up = TblDataUpdate::find()->where(['nipBaru' => $nip])->one();
            
            if($up === null){
                $up = new TblDataUpdate();
                $up['id'] = $nip;
                $up['nipBaru'] = $nip;
                $up['nama'] = $dt['B_03'];
                $up->save(false);
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Berhasil !!!',
                    'text' => 'Data berhasil disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }else{
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Not Found !!!',
                    'text' => 'Data baru tidak ditemukan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }
        }
        
        return $this->redirect(Url::previous());
    }

    public function actionSimgaji()
    {
        $mdl = SimasGajiMstpegawai::find()->select(['NIP', 'KDSTAPEG'])->where(['<', 'KDSTAPEG', 13]);

        foreach($mdl->all() as $dt){
            $nip = $dt['NIP'];
            $up = TblDataUpdate::find()->where(['nipBaru' => $nip])->one();
            
            if($up === null){
                $up = new TblDataUpdate();
                $up['id'] = $nip;            
                $up['nipBaru'] = $nip;
                $up['nama'] = $dt['NAMA'];
                $up->save(false);
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Berhasil !!!',
                    'text' => 'Data berhasil disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }else{
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Not Found !!!',
                    'text' => 'Data baru tidak ditemukan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }

        }
        return $this->redirect(Url::previous());
    }

    public function actionNipId()
    {
        $query = TblDataUpdate::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $searchModel = new TblDataUpdateSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->query->orderBy([
        //     'flag' => SORT_ASC,
        //     'dataUtama' => SORT_DESC,
        // ]);

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSyncAuth($nip)
    {
        $model = $this->findModelByNip($nip);
        $sia = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();

        $data = Tblasn::find()->where(['nipBaru' => $nip])->one();
        if($data === null){
            $data = new Tblasn();
        }
        foreach($data as $attr => $value){
            $data["$attr"] = $sia["$attr"];
        }

        $log = new TblHist();
        $log['id'] = $data['id'];
        $log['oleh'] = Yii::$app->session['username'];
        $log['data'] = 'auth-tblasn';
        $log['aksi'] = 'all';

        $auth = User::find()->where(['pengguna' => $sia['id']])->one();

        if($auth === null){
            $auth = new User();
            
            $count = User::find()->orderBy(['id' => SORT_DESC])->one();
            if($count['id'] === null) $auth['id'] = 1;
            else $auth['id'] = $count['id'] + 1;
        }

        $fip = EpsMastfip::find()->select(['B_02', 'A_01', 'A_02', 'A_03', 'A_04'])->where(['B_02' => $data['nipBaru']])->one();
        if($fip !== null) $tablok = $fip['A_01'].$fip['A_03'].$fip['A_03'].$fip['A_04'];
        else $tablok = '';

        $auth->setPassword(substr($data['nipBaru'],0,8));
        $auth->generateAuthKey();
        $auth['modified'] = date('Y-m-d H:i:s');
        $auth['flag'] = 0;
        $auth['role'] = 2;
        $auth['status'] = 10;
        $auth['pengguna'] = $data['id'];
        $auth['nipBaru'] = $data['nipBaru'];
        $auth['namapengguna'] = $data['nama'];
        $auth['tablok'] = $tablok;
        $auth['namaopd'] = $sia['unorIndukNama']; 
        $auth['updateby'] = Yii::$app->session['nip'].'-'.Yii::$app->session['namapengguna'];
        $auth->username = $data['nipBaru'];

        $auth->save(false);

        $model['auth'] = date('Y-m-d H:i:s');

        if($data->save(false) && $log->save(false) && $model->save(false)){
                
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Berhasil !!!',
                'text' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
        }else{  
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Gagal !!!',
                'text' => 'Data gagal disimpan',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
        }

        return $this->redirect(Url::previous());        
    }
    public function actionSyncSimpeg($nip)
    {
        $sia = SiasnDataUtama::find()
            ->where(['nipBaru' => $nip])
            ->one();

        if($sia !== null){
            
            $sim = SimpegEpsMastfip::find()->where(['B_02' => $nip])->one();

            if($sim === null){
                $sim = new SimpegEpsMastfip();
            }
                
            $sim['B_02'] = $sia['nipBaru'];
            $sim['B_03'] = $sia['nama'];

            $unorinduk = SiasnRefUnor::findOne($sia["unorId"]);
            if($unorinduk !== null) {                         
                $induk = $unorinduk['simpeg'];
                $sim['A_01'] = substr($induk,0,2); 
                $sim['A_02'] = '00'; 
                $sim['A_03'] = substr($induk,4,2); 
                $sim['A_04'] = substr($induk,6,2); 
            }else{
                $sim['A_01'] = '00'; 
                $sim['A_02'] = '00'; 
                $sim['A_03'] = '00';  
                $sim['A_04'] = '00';  
            }
            
            $sia['tglSkCpns'] === null ? $sim['C_01'] = '0000-00-00' : $sim['C_01'] = Fungsi::tglindodibalik($sia['tglSkCpns']);
            $sia['tmtCpns'] === null ? $sim['C_03'] = '0000-00-00' : $sim['C_03'] = Fungsi::tglindodibalik($sia['tmtCpns']);
            $sia['tglSkPns'] === null ? $sim['D_02'] = '0000-00-00' : $sim['D_02'] = Fungsi::tglindodibalik($sia['tglSkPns']);
            $sia['tmtPns'] === null ? $sim['D_04'] = '0000-00-00' : $sim['D_04'] = Fungsi::tglindodibalik($sia['tmtPns']); 
            $sia['tglLahir'] === null ? $sim['B_05'] = '0000-00-00' : $sim['B_05'] = Fungsi::tglindodibalik($sia['tglLahir']); 

            
            if($sia['jenisKelamin'] == 'M') $sim['B_06'] = 1; else $sim['B_06'] = 2;

            $sim['C_02'] = $sia['nomorSkCpns'];
            $sim['D_01'] = $sia['nomorSkPns'];
            $sim['D_03'] = $sia['golRuangAwalId'];
            $sim['B_03A'] = $sia['gelarDepan'];
            $sim['B_03B'] = $sia['gelarBelakang'];
            $sim['B_04'] = $sia['tempatLahir'];
            $sim['B_07'] = $sia['agamaId'];
            $sim['B_10'] = $sia['kedudukanPnsId'];
            $sim['B_11'] = $sia['alamat'];
            $sim['B_09'] = $sia['jenisKawinId'];
            
            $sim->save(false);
    
            $model = $this->findModelByNip($nip);
            $model['simpeg'] = date('Y-m-d H:i:s');
            $model->save();

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Berhasil !!!',
                'text' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
            return $this->redirect(Url::previous());

        }
    }

    public function actionSyncPreskin($nip)
    {
        $sia = SiasnDataUtama::find()
            ->where(['nipBaru' => $nip])
            ->one();

        if($sia !== null){
            
            $sim = PreskinAsn::find()->where(['nip' => $nip])->one();

            if($sim === null){
                $sim = new PreskinAsn();
            }
                
            $sim['nip'] = $sia['nipBaru'];
            $sim['idpns'] = $sia['id'];
            $sim['status'] = 4;
            
            $sim->save(false);
    
            $model = $this->findModelByNip($nip);
            $model['preskin'] = date('Y-m-d H:i:s');
            $model->save();

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Berhasil !!!',
                'text' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
            return $this->redirect(Url::previous());

        }
    }
    
    public function actionExcel()
    {

        $model = new ExcelForm();
    
        if($model->load(Yii::$app->request->post())){
            $file = UploadedFile::getInstance($model,'file');
            $upload = $file->saveAs('unggahan/'.$file);

            if($upload){
                $inputFileName = 'unggahan/'.$file;
                $spreadsheet = IOFactory::load($inputFileName);

                $worksheet = $spreadsheet->setActiveSheetIndex(0);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $jumdata = count($sheetData);     
                
                $baris = 2;
                $kolom = 'A';

                for($ix = $baris; $ix<=$jumdata; $ix++){
                    $nip = $sheetData[$ix][$kolom];
                
                    $cekdata = TblDataUpdate::find()->where([
                        'nipBaru' => $nip,
                    ]);

                    if($cekdata->count() == 0){
                        $entri = new TblDataUpdate;
                        $entri->nipBaru = $nip;
                        $entri->id = $nip;

                        $entri->save(false);
                    }
                }
            }

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Berhasil !!!',
                'text' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
            return $this->redirect(Url::previous());
        }
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('upload',['model'=>$model]);
        }else{
            return $this->render('upload',['model'=>$model]);
        }
    }
}
