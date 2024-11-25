<?php

namespace app\modules\integrasi\controllers;

use Yii;
use app\modules\integrasi\models\SiasnDataUtama;
use app\modules\integrasi\models\SiasnDataUtamaSearch;
use app\modules\integrasi\models\SiasnConfig;
use app\modules\integrasi\models\SiasnRwGolongan;
use app\modules\integrasi\models\SiasnRwJabatan;
use app\modules\integrasi\models\SiasnRwPendidikan;
use app\modules\integrasi\models\SiasnRwDiklat;
use app\modules\integrasi\models\SiasnRwKursus;
use app\modules\integrasi\models\SiasnRwAngkakredit;
use app\modules\integrasi\models\SiasnRwHarga;
use app\modules\integrasi\models\SiasnRwHukdis;
use app\modules\integrasi\models\SiasnRwSkp;
use app\modules\integrasi\models\SiasnRwSkp22;

use app\modules\simpeg\models\EpsTablokb;

use app\modules\efi\models\EfiFiles;
use app\modules\efi\models\EfiDok;

use app\models\Fungsi;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\FileHelper;

/**
 * SiasnDataUtamaController implements the CRUD actions for SiasnDataUtama model.
 */
class SiasnGetDataController extends Controller
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
                            if(Yii::$app->session['module'] != 3)  return $this->redirect(['/']);
                            else return Yii::$app->session['module'] == 3;
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

    public function actionIndex($id, $nip)
    { 
        $id =  $this->dekrip($id);
        $nip = $this->dekrip($nip);

        $sess = Yii::$app->session;
        $mode = 'prod';

        $ws = SiasnConfig::getTokenWSO2($mode);
        $sso = SiasnConfig::getTokenSSO($sess['nip'], $sess['password'], $mode);

        $model = $this->findModelByNip($nip);

        $siasn = ['code' => 0, 'data'=> '', 'message' => null];

        if($id == '/pns/photo/'){
          
            $result = SiasnConfig::apiResult($mode, $ws['token_key'], $sso['token_sso'], $id, $model['id']);
            $path = 'efi/'.$model['id'];

            if (!file_exists($path)) FileHelper::createDirectory($path);

            $file = $path.'/FOTO_'.$nip.'.jpg';

            file_put_contents($file, $result);
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil !!!',
                'text' => 'Foto berhasil diunduh',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
            return $this->redirect(['/integrasi/siasn-data-utama/profil', 'id' => $nip]);
        }

        $result = SiasnConfig::apiResult($mode, $ws['token_key'], $sso['token_sso'], $id, $nip);
        // return $result;

        if(!$result){
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'ERROR',
                'text' => "Server tidak terkoneksi : $result",
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
            return $this->redirect(Url::previous());
        }


        $siasn = Json::decode($result);

        // return $siasn['data']['validNik'];

        if(!array_key_exists('code', $siasn)) {
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
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Data tidak ditemukan',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 

        }else{
            if($siasn['data'] == ''){
                $siasn['data'] = '';            
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Data tidak ditemukan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
                return $this->redirect(Url::previous());

            }

            if($id == '/pns/data-utama/') $model = $this->getDataUtama($siasn['data']);
            if($id == '/pns/photo/') $model = $this->getPhoto($siasn['data']);
            if($id == '/pns/rw-golongan/') $model = $this->getRwGolongan($siasn['data'], $model['id']);
            if($id == '/pns/rw-jabatan/') $model = $this->getRwJabatan($siasn['data'], $model['id']);
            if($id == '/pns/rw-pendidikan/') $model = $this->getRwPendidikan($siasn['data'], $model['id']);
            if($id == '/pns/rw-diklat/') $model = $this->getRwDiklat($siasn['data'], $model['id']);
            if($id == '/pns/rw-kursus/') $model = $this->getRwKursus($siasn['data'], $model['id']);
            if($id == '/pns/rw-angkakredit/') $model = $this->getRwAk($siasn['data'], $model['id'], $model['nipBaru']);
            if($id == '/pns/rw-penghargaan/') $model = $this->getRwHarga($siasn['data'], $model['id'], $model['nipBaru']);
            if($id == '/pns/rw-hukdis/') $model = $this->getRwHukdis($siasn['data'], $model['id'], $model['nipBaru']);
            if($id == '/pns/rw-skp/') $model = $this->getRwSkp($siasn['data'], $model['id'], $model['nipBaru']);
            if($id == '/pns/rw-skp22/') $model = $this->getRwSkp22($siasn['data'], $model['id'], $model['nipBaru']);

            // return $model;

            if($model == 1){
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                    'title' => 'Berhasil !!!',
                    'text' => 'Data berhasil disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 

            }else{
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                    'title' => 'Gagal !!!',
                    'text' => 'Data gagal disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }
            return $this->redirect(Url::previous());
        }
    }

    public function actionGetDok($id, $nip)
    { 
        $id =  $this->dekrip($id);
        $nip = $this->dekrip($nip);

        // return $id.' --- '.$nip;

        $dok = EfiFiles::findOne($id);

        $msg = 0;
        if($dok !== null){
            $url = $dok['siasn_path'];
            if($url !== null || $url != ''){
                $sess = Yii::$app->session;
                $mode = 'prod';

                $ws = SiasnConfig::getTokenWSO2($mode);
                $sso = SiasnConfig::getTokenSSO($sess['nip'], $sess['password'], $mode);

                $result = SiasnConfig::apiDok($mode, $ws['token_key'], $sso['token_sso'], $url);
                
                if($result == ''){
                    Yii::$app->session->setFlash('position', [
                        'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                        'title' => 'Error !!!',
                        'text' => 'No response download document',
                        'showConfirmButton' => false,
                        'timer' => 2000
                    ]); 

                    return $this->redirect(Url::previous());

                }

                $path = 'efi/'.$nip;

                if (!file_exists($path)) FileHelper::createDirectory($path);

                $file = $path.'/'.$id;

                if(file_put_contents($file, $result)) $msg = 1;
            }
        }

        if($msg == 1){
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil !!!',
                'text' => 'Dokumen berhasil didownload',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
        }else{
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                'title' => 'Gagal !!!',
                'text' => 'Dokumen gagal didownload',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
        }
        return $this->redirect(Url::previous());
    }

    public function actionShowDok($id, $nip)
    { 
        $id =  $this->dekrip($id);
        $nip = $this->dekrip($nip);
        
        $file = 'efi/'.$nip.'/'.$id;

        return Yii::$app->response->sendFile($file, $id);

    }

    protected function dekrip($val){
        $val = explode('#',base64_decode(base64_decode($val)))[1];
        return $val;
    }

    protected function findModelByNip($id)
    {
        if (($model = SiasnDataUtama::find()->where(['nipBaru' => $id])->one()) !== null) {
            return $model;
        }
        else return '';
    }

    protected function getDataUtama($id)
    {
        $data = $id;
        $data['updated']  = date('Y-m-d H:i:s');  

        $model = SiasnDataUtama::findOne($data['id']);

        $path = 'efi/'.$data['id'];
        if (!file_exists($path)) FileHelper::createDirectory($path);

        if($model === null) $model = new SiasnDataUtama();

        foreach($data as $attr => $value){
            if($attr != 'path'){
                    $model["$attr"] = $data["$attr"];
                if($attr == 'tanggal_taspen') $model["$attr"] = date('Y-m-d H:i:s', strtotime($data["$attr"]));
                // if($data["$attr"] == 'null') $data["$attr"] = NULL;

            }
        }

        $dap = Json::decode($data['path']);
        $path = [];

        if($dap != ''){
        
            if(array_key_exists('887', $dap)){
                $path['dok_cpns'] = $dap['887']['dok_uri'];
                $efi = EfiFiles::findOne('SK_PNS_'.$data['nipBaru'].'.pdf');
                if($efi === null) $efi = new EfiFiles(['nama_file' => 'SK_PNS_'.$data['nipBaru'].'.pdf']);
                
                $efi['nip'] = $data['nipBaru'];
                $efi['id_dok'] = 1;
                $efi['siasn_path'] = $path['dok_cpns'];
                $efi['siasn_id'] = explode("/", $path['dok_cpns'])[2];
                $efi->save(false);
            }
            
            if(array_key_exists('888', $dap)){
                $path['dok_spmt'] = $dap['888']['dok_uri'];
                $efi = EfiFiles::findOne('SPMT_'.$data['nipBaru'].'.pdf');
                if($efi === null) $efi = new EfiFiles(['nama_file' => 'SPMT_'.$data['nipBaru'].'.pdf']);
                
                $efi['nip'] = $data['nipBaru'];
                $efi['id_dok'] = 45;
                $efi['siasn_path'] = $path['dok_spmt'];
                $efi['siasn_id'] = explode("/", $path['dok_spmt'])[2];
                $efi->save(false);
            }
            
            if(array_key_exists('889', $dap)){
                $path['dok_pns'] = $dap['889']['dok_uri'];
                $efi = EfiFiles::findOne('SK_CPNS_'.$data['nipBaru'].'.pdf');
                if($efi === null) $efi = new EfiFiles(['nama_file' => 'SK_CPNS_'.$data['nipBaru'].'.pdf']);
                
                $efi['nip'] = $data['nipBaru'];
                $efi['id_dok'] = 2;
                $efi['siasn_path'] = $path['dok_pns'];
                $efi['siasn_id'] = explode("/", $path['dok_pns'])[2];
                $efi->save(false);
            }
            // return Json::encode($model, $asArray = true);
        }
        if($model->save(false) ) return 1;else return 0;
    }

    protected function getRwGolongan($id, $nip)
    {
        $response = 0;    
        $rw = $id;

        $path = 'efi/'.$nip;
        if (!file_exists($path)) FileHelper::createDirectory($path);

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            $model = SiasnRwGolongan::findOne($data['id']);

            if($model === null) $model = new SiasnRwGolongan();

            foreach($data as $attr => $value){
                if($attr != 'path'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;            
            if($data['path'] !== null){
                $dap = $data['path'];
                $dpath['dok_sk_kp'] = $dap['858']['dok_uri'];
                $fnm = 'SK_KP_'.$data['golonganId'].'_'.$data['nipBaru'].'.pdf';
                $efidok = EfiDok::find()->where(['nama' => 'SK_KP_'.$data['golonganId'].'_$NIP'])->one();
                if($efidok === null) $id_dok = 0;else $id_dok = $efidok['id'];

                $efi = EfiFiles::findOne($fnm);
                if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                
                $efi['nip'] = $data['nipBaru'];
                $efi['id_dok'] = $id_dok;
                $efi['siasn_path'] = $dpath['dok_sk_kp'];
                $efi['siasn_id'] = explode("/", $dpath['dok_sk_kp'])[2];
                $efi->save(false);
            }
        }
        // return json_encode($dpath);
        return $response;
    }

    protected function getRwJabatan($id, $nip)
    {
        $response = 0;    
        $rw = $id;

        $path = 'efi/'.$nip;
        if (!file_exists($path)) FileHelper::createDirectory($path);

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            $model = SiasnRwJabatan::findOne($data['id']);

            if($model === null) $model = new SiasnRwJabatan();

            foreach($data as $attr => $value){
                if($attr != 'path'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;            
            if($data['path'] !== null){
                $tahun = substr($data['tmtJabatan'],6,4);
                $dap = $data['path'];
                $dpath['dok_sk_jab'] = $dap['872']['dok_uri'];
                $fnm = 'SK_JABATAN_'.$tahun.'_'.$data['nipBaru'].'.pdf';
                // $efidok = EfiDok::find()->where(['nama' => 'SK_JABATAN_'.$tahun.'_$NIP'])->one();
                // if($efidok === null) $id_dok = 0;else $id_dok = $efidok['id'];
                $id_dok = 32;

                $efi = EfiFiles::findOne($fnm);
                if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                
                $efi['nip'] = $data['nipBaru'];
                $efi['id_dok'] = $id_dok;
                $efi['siasn_path'] = $dpath['dok_sk_jab'];
                $efi['siasn_id'] = explode("/", $dpath['dok_sk_jab'])[2];
                $efi->save(false);
            }
        }
        // return json_encode($dpath);
        return $response;
    }

    protected function getRwPendidikan($id, $nip)
    {
        $response = 0;    
        $rw = $id;

        // $path = 'efi/'.$nip;
        // if (!file_exists($path)) FileHelper::createDirectory($path);

        // $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            
            $model = SiasnRwPendidikan::findOne($data['id']);

            if($model === null) $model = new SiasnRwPendidikan();

            foreach($data as $attr => $value){
                if($attr != 'path'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;   

            // if($data['path'] !== null){
            //     $tahun = substr($data['tmtJabatan'],6,4);
            //     $dap = $data['path'];
            //     $dpath['dok_sk_jab'] = $dap['872']['dok_uri'];
            //     $fnm = 'IJASAH_'.$tahun.'_'.$data['nipBaru'].'.pdf';
            //     // $efidok = EfiDok::find()->where(['nama' => 'IJASAH_'.$tahun.'_$NIP'])->one();
            //     // if($efidok === null) $id_dok = 0;else $id_dok = $efidok['id'];
            //     $id_dok = 32;

            //     $efi = EfiFiles::findOne($fnm);
            //     if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                
            //     $efi['nip'] = $data['nipBaru'];
            //     $efi['id_dok'] = $id_dok;
            //     $efi['siasn_path'] = $dpath['dok_sk_jab'];
            //     $efi['siasn_id'] = explode("/", $dpath['dok_sk_jab'])[2];
            //     $efi->save(false);
            // }
        }
        // return json_encode($model);

        return $response;
    }

    protected function getRwDiklat($id, $nip)
    {
        $response = 0;    
        $rw = $id;

        $path = 'efi/'.$nip;
        if (!file_exists($path)) FileHelper::createDirectory($path);

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            
            $model = SiasnRwDiklat::findOne($data['id']);

            if($model === null) $model = new SiasnRwDiklat();

            foreach($data as $attr => $value){
                if($attr != 'path'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;   

            if($data['path'] !== null){
                $tahun = $data['tahun'];
                $dap = $data['path'];
                $dpath['dok_sk_diklat'] = $dap['874']['dok_uri'];
                $fnm = 'SK_DIKLAT_'.$tahun.'_'.$data['nipBaru'].'.pdf';
                $id_dok = 0;

                $efi = EfiFiles::findOne($fnm);
                if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                
                $efi['nip'] = $data['nipBaru'];
                $efi['id_dok'] = $id_dok;
                $efi['siasn_path'] = $dpath['dok_sk_diklat'];
                $efi['siasn_id'] = explode("/", $dpath['dok_sk_diklat'])[2];
                $efi->save(false);
            }
        }
        // return json_encode($model);

        return $response;
    }

    protected function getRwKursus($id, $nip)
    {
        $response = 0;    
        $rw = $id;

        $path = 'efi/'.$nip;
        if (!file_exists($path)) FileHelper::createDirectory($path);

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            
            $model = SiasnRwKursus::findOne($data['id']);

            if($model === null) $model = new SiasnRwKursus();

            foreach($data as $attr => $value){
                if($attr != 'path'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;   

            if($data['path'] !== null){
                $tahun = $data['tanggalKursus'];
                foreach($data['path'] as $key => $val){
                    $dpath['id'] = $key;
                    $dpath['dok_sk_jab'] = $data['path'][$key]['dok_uri'];
                    
                    $fnm = 'SK_KURSUS_'.$tahun.'_'.$data['nipBaru'].'.pdf';

                    $efi = EfiFiles::findOne($fnm);
                    if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                    
                    $efi['nip'] = $data['nipBaru'];
                    $efi['id_dok'] = $key;
                    $efi['siasn_path'] = $dpath['dok_sk_jab'];
                    $efi['siasn_id'] = explode("/", $dpath['dok_sk_jab'])[2];
                    $efi->save(false);
                }

                // if($data['path']['874'] !== null) $dpath['dok_sk_jab'] = $data['path']['874']['dok_uri'];
                // elseif($data['path']['881'] !== null) $dpath['dok_sk_jab'] = $data['path']['881']['dok_uri'];

                // $fnm = 'SK_KURSUS_'.$tahun.'_'.$data['nipBaru'].'.pdf';
                // $id_dok = 0;

                // $efi = EfiFiles::findOne($fnm);
                // if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                
                // $efi['nip'] = $data['nipBaru'];
                // $efi['id_dok'] = $id_dok;
                // $efi['siasn_path'] = $dpath['dok_sk_jab'];
                // $efi['siasn_id'] = explode("/", $dpath['dok_sk_jab'])[2];
                // $efi->save(false);
            }
        }
        // return json_encode($dpath);

        return $response;
    }

    protected function getRwAk($id, $nip, $idu)
    {
        $response = 0;    
        $rw = $id;

        // $path = 'efi/'.$nip;
        // if (!file_exists($path)) FileHelper::createDirectory($path);

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            
            $model = SiasnRwAngkaKredit::findOne($data['id']);

            if($model === null) $model = new SiasnRwAngkaKredit();

            foreach($data as $attr => $value){
                if($attr != 'path' && $attr != 'created_at' && $attr != 'updated_at'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            $model['tanggalSk'] = Fungsi::tglymd($data['tanggalSk']);
            $model['created_at'] = date('Y-m-d H:i:s', strtotime($data['created_at']));
            $model['updated_at'] = date('Y-m-d H:i:s', strtotime($data['updated_at']));
            
            if($model->save(false)) $response = 1;   

            // if($data['path'] !== null){
            //     $tahun = $data['tanggalSk'];
            //     $dap = $data['path'];

            //     if($dap['879'] !== null) $dpath['dok_sk_jab'] = $dap['879']['dok_uri'];
            //     else $dpath['dok_sk_jab'] = [];

            //     if($dap['880'] !== null) $dpath['dok_sk_jab'] = $dap['880']['dok_uri'];
            //     else $dpath['dok_sk_jab'] = [];

            //     $fnm = 'PAK_'.$tahun.'_'.$idu.'.pdf';
            //     $id_dok = 0;

            //     if($dpath != []) {
                
            //         $efi = EfiFiles::findOne($fnm);
            //         if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                    
            //         $efi['nip'] = $idu;
            //         $efi['id_dok'] = $id_dok;
            //         $efi['siasn_path'] = $dpath['dok_sk_jab'];
            //         $efi['siasn_id'] = explode("/", $dpath['dok_sk_jab'])[2];
            //         $efi->save(false);
            //     }
            // }
        }
        // return json_encode($data['path']);

        return $response;
    }

    protected function getRwHukdis($id, $nip, $idu)
    {
        $response = 0;    
        $rw = $id;

        // $path = 'efi/'.$nip;
        // if (!file_exists($path)) FileHelper::createDirectory($path);

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            
            $model = SiasnRwHukdis::findOne($data['id']);

            if($model === null) $model = new SiasnRwHukdis();

            foreach($data as $attr => $value){
                if($attr != 'path'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;   

            // if($data['path'] !== null){
            //     $tahun = $data['tanggalSk'];
            //     $dap = $data['path'];

            //     if($dap['879'] !== null) $dpath['dok_sk_jab'] = $dap['879']['dok_uri'];
            //     else $dpath['dok_sk_jab'] = [];

            //     if($dap['880'] !== null) $dpath['dok_sk_jab'] = $dap['880']['dok_uri'];
            //     else $dpath['dok_sk_jab'] = [];

            //     $fnm = 'PAK_'.$tahun.'_'.$idu.'.pdf';
            //     $id_dok = 0;

            //     if($dpath != []) {
                
            //         $efi = EfiFiles::findOne($fnm);
            //         if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                    
            //         $efi['nip'] = $idu;
            //         $efi['id_dok'] = $id_dok;
            //         $efi['siasn_path'] = $dpath['dok_sk_jab'];
            //         $efi['siasn_id'] = explode("/", $dpath['dok_sk_jab'])[2];
            //         $efi->save(false);
            //     }
            // }
        }
        // return json_encode($data['path']);

        return $response;
    }

    protected function getRwHarga($id, $nip, $idu)
    {
        $response = 0;    
        $rw = $id;

        $path = 'efi/'.$nip;
        if (!file_exists($path)) FileHelper::createDirectory($path);

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            
            $model = SiasnRwHarga::findOne($data['ID']);

            if($model === null) $model = new SiasnRwHarga();

            foreach($data as $attr => $value){
                if($attr != 'path'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;   

            if($data['path'] !== null){
                $tahun = $data['tahun'];
                $dap = $data['path'];

                $dpath['dok_sk_jab'] = $dap['892']['dok_uri'];

                $fnm = 'PENGHARGAAN_'.$tahun.'_'.$idu.'.pdf';
                $id_dok = 0;

                if($dpath != []) {
                
                    $efi = EfiFiles::findOne($fnm);
                    if($efi === null) $efi = new EfiFiles(['nama_file' => $fnm]);
                    
                    $efi['nip'] = $idu;
                    $efi['id_dok'] = $id_dok;
                    $efi['siasn_path'] = $dpath['dok_sk_jab'];
                    $efi['siasn_id'] = explode("/", $dpath['dok_sk_jab'])[2];
                    $efi->save(false);
                }
            }
        }
        // return json_encode($data['path']);

        return $response;
    }

    protected function getRwSkp($id, $nip, $idu)
    {
        $response = 0;    
        $rw = $id;

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            
            $model = SiasnRwSkp::findOne($data['id']);

            if($model === null) $model = new SiasnRwSkp();

            foreach($data as $attr => $value){
                if($attr != 'path' && $attr != 'created_at' && $attr != 'updated_at'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;   
            
        }

        return $response;
    }

    protected function getRwSkp22($id, $nip, $idu)
    {
        $response = 0;    
        $rw = $id;

        $dpath = [];

        foreach($rw as $data){
            $data['updated'] = date('Y-m-d H:i:s');  
            
            $model = SiasnRwSkp22::findOne($data['id']);

            if($model === null) $model = new SiasnRwSkp22();

            foreach($data as $attr => $value){
                if($attr != 'path' && $attr != 'created_at' && $attr != 'updated_at'){
                    $model["$attr"] = $data["$attr"];
                }
            }
            
            if($model->save(false)) $response = 1;   
            
        }

        return $response;
    }
}
