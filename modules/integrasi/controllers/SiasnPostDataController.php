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

use app\modules\integrasi\models\SiasnTempDatautama;
use app\modules\integrasi\models\SiasnTempCpns;
use app\modules\integrasi\models\SiasnTempRwHukdis;
use app\modules\integrasi\models\SiasnTempRwDiklat;
use app\modules\integrasi\models\SiasnTempRwKursus;
use app\modules\integrasi\models\SiasnTempRwJabatan;
use app\modules\integrasi\models\SiasnTempRwAk;
use app\modules\integrasi\models\SiasnTempRwHarga;
use app\modules\integrasi\models\SiasnTempRwSkp;
use app\modules\integrasi\models\SiasnTempSkp21;
use app\modules\integrasi\models\SiasnTempSkp22;

use app\modules\integrasi\models\FileForm;

use app\modules\simpeg\models\EpsTablokb;

use app\modules\efi\models\EfiFiles;
use app\modules\efi\models\EfiDok;

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
class SiasnPostDataController extends Controller
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
        $id = $this->dekrip($id);
        $nip = $this->dekrip($nip);

        $sess = Yii::$app->session;
        $mode = 'prod';

        $ws = SiasnConfig::getTokenWSO2($mode);
        $sso = SiasnConfig::getTokenSSO($sess['nip'], $sess['password'], $mode);

        // $tabel = SiasnTemp.ucfirst($id); 
        $pid = 'siasn-temp-'.$id;

        switch ($id) {
            case 'cpns':
                $url = "/cpns/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempCpns([
                    "id" => $mod['id'],
                    "kartu_pegawai" => $mod['KartuAsn'],
                    "nama_jabatan_angkat_cpns" => NULL,
                    "nomor_dokter_pns" => $mod['noSuratKeteranganDokter'],
                    "nomor_sk_cpns" => $mod['nomorSkCpns'],
                    "nomor_sk_pns" => $mod['nomorSkPns'],
                    "nomor_spmt" => $mod['noSpmt'],
                    "nomor_sttpl" => $mod['nomorSttpl'],
                    "path" => NULL,
                    "pertek_cpns_pns_l2th_nomor"=> $mod['pertek_cpns_pns_l2th_nomor'],
                    "pertek_cpns_pns_l2th_tanggal"=> $mod['pertek_cpns_pns_l2th_tanggal'],
                    "pns_orang_id"=> $mod['id'],
                    "status_cpns_pns"=> $mod['statusPegawai'],
                    "tanggal_dokter_pns"=> $mod['tglSuratKeteranganDokter'],
                    "tgl_sk_cpns"=> $mod['tglSkCpns'],
                    "tgl_sk_pns"=> $mod['tglSkPns'],
                    "tgl_sttpl"=> $mod['tglSttpl'],
                    "tmt_pns"=> $mod['tmtPns']
                ]);
                break;

            case 'datautama':
                $url = "/pns/data-utama-update";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempDataUtama([
                    // 'flag' => 0,
                    // 'updated' => date('Y-m-d H:i:s'),
                    // 'by' => $sess['nip'],
                    "agama_id" => $mod['agamaId'],
                    "alamat" => $mod['alamat'],
                    "email" => $mod['email'],
                    "email_gov" => $mod['emailGov'],
                    "kabupaten_id" => '',
                    "karis_karsu" => $mod['karis_karsu'],
                    "kelas_jabatan" => $mod['kelas_jabatan'],
                    "kpkn_id" => $mod['kpknId'],
                    "lokasi_kerja_id" => $mod['lokasiKerjaId'],
                    "nomor_bpjs" => $mod['bpjs'],
                    "nomor_hp" => $mod['noHp'],
                    "nomor_telpon" => $mod['noTelp'],
                    "npwp_nomor" => $mod['noNpwp'],
                    "npwp_tanggal" => $mod['tglNpwp'],
                    "pns_orang_id" => $mod['id'],
                    "tanggal_taspen" => '',
                    "tapera_nomor" => '',
                    // "nik" => $mod['nik'],
                    // "nomor_kk" => '',
                    "taspen_nomor" => $mod['noTaspen']
                ]);
                
                break;    

            case 'rw-ak':
                $url = "/angkakredit/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwAk([

                ]);
                break;

            case 'rw-diklat':
                $url = "/diklat/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwDiklat([
                    "bobot" => 0,
                    "id" => NULL,
                    "institusiPenyelenggara" => NULL,
                    "jenisKompetensi" => NULL,
                    "jumlahJam" => 0,
                    "latihanStrukturalId" => NULL,
                    "nomor" => NULL,
                    "pnsOrangId" => $mod['id'],
                    "tahun" => 0,
                    "tanggal" => NULL,
                    "tanggalSelesai" => NULL 
                ]);
                break;
            
            case 'rw-kursus':
                $url = "/kursus/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwKursus([
                    "id" => NULL,
                    "instansiId"=> 'A5EB03E23C55F6A0E040640A040252AD',
                    "institusiPenyelenggara" => NULL,
                    "jenisDiklatId" => NULL,
                    "jenisKursus" => NULL,
                    "jenisKursusSertipikat" => NULL,
                    "jumlahJam" => 0,
                    "lokasiId" => NULL,
                    "namaKursus" => NULL,
                    "nomorSertipikat" => NULL,
                    "pnsOrangId" => $mod['id'],
                    "tahunKursus" => 0,
                    "tanggalKursus" => NULL,
                    "tanggalSelesaiKursus" => NULL
                ]);
                break;    

            case 'rw-harga':
                $url = "/penghargaan/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwHarga([
                    "hargaId" => NULL,
                    "id" => NULL,
                    // "path" => NULL,
                    "pnsOrangId"=> $mod['id'],
                    "skDate"=> NULL,
                    "skNomor"=> NULL,
                    "tahun"=> NULL
                ]);
                break;

            case 'rw-hukdis':
                $url = "/hukdis/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwHukdis();
                break;

            case 'rw-jabatan':
                $url = "/jabatan/unorjabatan/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwJabatan([
                    "id"=> NULL,
                    "eselonId"=> NULL,
                    "instansiId"=> 'A5EB03E23C55F6A0E040640A040252AD',
                    "instansiIndukId" => 'A5EB03E23C55F6A0E040640A040252AD',
                    "jabatanFungsionalId"=> NULL,
                    "jabatanFungsionalUmumId"=> NULL,
                    "jenisJabatan"=> NULL,
                    "jenisMutasiId"=> NULL,
                    "jenisPenugasanId"=> NULL,
                    "nomorSk"=> NULL,
                    "pnsId"=> $mod['id'],
                    "satuanKerjaId"=> 'A5EB03E241F7F6A0E040640A040252AD',
                    "subJabatanId"=> NULL,
                    "tanggalSk"=> NULL,
                    "tmtJabatan"=> NULL,
                    "tmtMutasi"=> NULL,
                    "tmtPelantikan"=> NULL,
                    "unorId"=> NULL
                ]);
                break;

            case 'rw-skp':
                $url = "/skp/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwSkp();
                break;                  
            
            case 'rw-skp21':
                $url = "/skp/21/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwSkp21();
                break;                  
            
            case 'rw-skp22':
                $url = "/skp22/save";
                $mod = SiasnDataUtama::find()->where(['nipBaru' => $nip])->one();
                $model = new SiasnTempRwSkp22();
                break;                                      
        }

        if ($model->load($this->request->post())){ 
            
            if($url == '/penghargaan/save') $model['tahun'] = (int)$model['tahun'];
            if($url == '/diklat/save'){
                $model['tahun'] = (int)$model['tahun'];
                $model['bobot'] = (int)$model['bobot'];
                $model['jumlahJam'] = (int)$model['jumlahJam'];
            }

            if($url == '/kursus/save'){
                $model['tahunKursus'] = (int)$model['tahunKursus'];
                $model['jumlahJam'] = (int)$model['jumlahJam'];
            }

            $data = Json::encode($model, $asArray = true);

            $post = SiasnConfig::resultPost($mode, $url, $data,'application/json', $ws['token_key'], $sso['token_sso']);
            $model->save();

            // return $post;
           
            if(!$post){
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'ERROR',
                    'text' => "Server tidak terkoneksi : $post",
                    'showConfirmButton' => true,
                ]); 
                return $this->redirect(Url::previous());
            }

            $siasn = Json::decode($post);

            if(!array_key_exists('code', $siasn)) {

                if(array_key_exists('success', $siasn)) {
                    if($siasn['success'] == true){
                        Yii::$app->session->setFlash('position', [
                            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                            'title' => 'Berhasil',
                            'text' => Json::encode($siasn, $asArray = true),
                            'showConfirmButton' => true,
                        ]); 
                    }else{
                        Yii::$app->session->setFlash('position', [
                            'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                            'title' => 'Gagal',
                            'text' => Json::encode($siasn, $asArray = true),
                            'showConfirmButton' => true,
                        ]);  
                    }
                    // return $this->redirect(Url::previous());
                }else{
                    Yii::$app->session->setFlash('position', [
                        'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                        'title' => 'ERROR',
                        'text' => Json::encode($siasn, $asArray = true),
                        'showConfirmButton' => true,
                    ]); 
                }
                return $this->redirect(Url::previous());
            }

            if($siasn['code'] != 1){
                $siasn['data'] = '';            
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Gagal ',
                    'showConfirmButton' => true,
                ]); 
    
            }else{
            
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                    'title' => 'Berhasil !!!',
                    'text' => $siasn['message'],
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }

            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) 
            return $this->renderAjax("$pid-form",['model' => $model]);   
        else return $this->render("$pid-form",['model' => $model]);  
    }

    public function actionUploadDok($nip, $id_dok)
    { 
        $nip = $this->dekrip($nip);
        $id_dok = (int)$this->dekrip($id_dok);

        $model = new FileForm();

        if (Yii::$app->request->isPost) {
            // return $id.' --- '.$nip.' --- '.$id_dok;

            $file = UploadedFile::getInstance($model, 'file');

            $path = 'efi/'.$nip;
            if (!file_exists($path)) FileHelper::createDirectory($path);
            $nmfile = $path.'/'.str_replace(" ", "_", $file);
            $file->saveAs($nmfile);

            $sess = Yii::$app->session;
            $mode = 'prod';

            $ws = SiasnConfig::getTokenWSO2($mode);
            $sso = SiasnConfig::getTokenSSO($sess['nip'], $sess['password'], $mode);

            $result = SiasnConfig::postDok($mode, $ws['token_key'], $sso['token_sso'], $nmfile, $id_dok);
            
            $siasn = Json::decode($result);
            unlink($nmfile);

            if(!array_key_exists('code', $siasn)) {
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'ERROR',
                    'text' => Json::encode($siasn, $asArray = true),
                    'showConfirmButton' => true,
                ]); 
                return $this->redirect(Url::previous());
            }

            if($siasn['code'] != 1){
                $siasn['data'] = '';            
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Gagal Upload',
                    'text' => Json::encode($siasn, $asArray = true),
                    'showConfirmButton' => true,
                ]); 

            }else{
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Berhasil ',
                    'text' => 'Berhasil Upload File',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }

            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) 
            return $this->renderAjax('upload-dok',['model' => $model]);   
        else return $this->render('upload-dok',['model' => $model]);   
    }

    public function actionUploadDokRw($id, $nip, $id_dok)
    { 
        $id =  $this->dekrip($id);
        $nip = $this->dekrip($nip);
        $id_dok = (int)$this->dekrip($id_dok);

        $model = new FileForm();

        if (Yii::$app->request->isPost) {
            // return $id.' --- '.$nip.' --- '.$id_dok;

            $file = UploadedFile::getInstance($model, 'file');

            $path = 'efi/'.$nip;
            if (!file_exists($path)) FileHelper::createDirectory($path);
            $nmfile = $path.'/'.str_replace(" ", "_", $file);
            $file->saveAs($nmfile);

            $sess = Yii::$app->session;
            $mode = 'prod';

            $ws = SiasnConfig::getTokenWSO2($mode);
            $sso = SiasnConfig::getTokenSSO($sess['nip'], $sess['password'], $mode);

            $result = SiasnConfig::postDokRw($mode, $ws['token_key'], $sso['token_sso'], $nmfile, $id, $id_dok);
            
            $siasn = Json::decode($result);
            unlink($nmfile);

            if(!array_key_exists('code', $siasn)) {
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'ERROR',
                    'text' => Json::encode($siasn, $asArray = true),
                    'showConfirmButton' => true,
                ]); 
                return $this->redirect(Url::previous());
            }

            if($siasn['code'] != 1){
                $siasn['data'] = '';            
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Gagal Upload',
                    'text' => Json::encode($siasn, $asArray = true),
                    'showConfirmButton' => true,
                ]); 

            }else{
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Berhasil ',
                    'text' => 'Berhasil Upload File',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }

            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) 
            return $this->renderAjax('upload-dok',['model' => $model]);   
        else return $this->render('upload-dok',['model' => $model]);   
    }

    public function actionPostDataUtama($nip)
    {
        $model = $this->findDautByNip($nip);

         if ($model->load($this->request->post())){ 
            $arr = [
                "agama_id" => $model['agamaId'],
                "alamat" => $model['alamat'],
                "email" => $model['email'],
                "email_gov" => $model['emailGov'],
                "kabupaten_id" => 'A8ACA73E4B7F3912E040640A040269BB',
                "karis_karsu" => $model['karis_karsu'],
                "kelas_jabatan" => $model['kelas_jabatan'],
                "kpkn_id" => $model['kpknId'],
                "lokasi_kerja_id" => $model['lokasiKerjaId'],
                "nomor_bpjs" => $model['bpjs'],
                "nomor_hp" => $model['noHp'],
                "nomor_telpon" => $model['noTelp'],
                "npwp_nomor" => $model['noNpwp'],
                "npwp_tanggal" => $model['tglNpwp'],
                "pns_orang_id" => $model[''],
                "tanggal_taspen" => $model['tanggal_taspen'],
                "tapera_nomor" => $model['tabrum2'],
                "taspen_nomor" => $model['noTaspen']
            ];

            $sess = Yii::$app->session;
            $mode = 'prod';

            $ws = SiasnConfig::getTokenWSO2($mode);
            $sso = SiasnConfig::getTokenSSO($sess['nip'], $sess['password'], $mode);

            $result = SiasnConfig::apiResultPost($mode, $ws['token_key'], $sso['token_sso'], $arr, '/pns/data-utama-update');

            $data = json_encode($nilai);
            $data_view = json_encode($nilai, JSON_PRETTY_PRINT);
            $resultApi = apiResultPost($base_url.'skp22/save', $data,'application/json');
            
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) 
            return $this->renderAjax('data-utama',['model' => $model]);   
        else return $this->render('data-utama',['model' => $model]);   

      }

    protected function dekrip($val){
        $val = explode('#',base64_decode(base64_decode($val)))[1];
        return $val;
    }

    protected function findDautByNip($id)
    {
        if (($model = SiasnDataUtama::find()->where(['nipBaru' => $id])->one()) !== null) {
            return $model;
        }
        else return '';
    }

}
