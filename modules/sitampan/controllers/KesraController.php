<?php

namespace app\modules\sitampan\controllers;

use Yii;
use app\modules\sitampan\models\PresHarian;
use app\modules\sitampan\models\PresHarianSearch;
use app\modules\sitampan\models\PreskinAsn;
use app\modules\sitampan\models\PreskinAsnSearch;
use app\modules\sitampan\models\PreskinHariKerja;
use app\modules\sitampan\models\PreskinPaguTpp;
use app\modules\sitampan\models\FpPresensiCheckinoutHp;
use app\modules\sitampan\models\VpresensiAdms;
use app\modules\sitampan\models\KinHarian;
use app\modules\sitampan\models\KinHarianSearch;
use app\modules\sitampan\models\PreskinTppHitung;
use app\modules\sitampan\models\PreskinTppHitungSearch;
use app\modules\sitampan\models\PreskinGroupCetak;
use app\modules\sitampan\models\FpTbDaftarfinger;
use app\modules\sitampan\models\FpTbDaftarfingerSearch;
use app\modules\sitampan\models\FpTbAlamat2;
use app\modules\sitampan\models\FpLogAktivitas;

use app\modules\integrasi\models\SiasnDataUtama;
use app\modules\gaji\models\SimasGajiStapegTbl;

use app\modules\simpeg\models\EpsTablokb;
use app\modules\simpeg\models\EpsMastfip;

use app\models\Fungsi;
use app\models\User;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;
use kartik\mpdf\Pdf;


/**
 * PresHarianController implements the CRUD actions for PresHarian model.
 */
class KesraController extends Controller
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
                            if(Yii::$app->session['module'] != 10)  return $this->redirect(['/']);
                            else return (Yii::$app->session['module'] == 10);
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
                    // 'simasganteng' => ['POST'],
                ],
            ],
        ];
    }

    protected function findModel($id)
    {
        if (($model = PresHarian::findOne($id)) !== null) {
            return $model;
        }else return '';

        // throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findAsn($id)
    {
        if (($model = PreskinAsn::findOne($id)) !== null) {
            return $model;
        }else return '';

        // throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelPres($id)
    {
        if (($model = FpTbDaftarfinger::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findSimas($id)
    {
        if (($model = User::find()->where(['nipBaru' => $id])->one()) !== null) {
            return $model;
        }else return '';

        // throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findSiasn($id)
    {
        if (($model = SiasnDataUtama::find()->where(['nipBaru' => $id])->one()) !== null) {
            return $model;
        }else return '';

    }

    public function actionHasil()
    {
        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->orderBy(['KOLOK' => SORT_ASC])
        ->all();
        
        $searchModel = new PreskinAsnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        
        $dataProvider->query->andWhere(['OR',['<', 'status', 13],['>', 'tmt_stop', date('Y-m-01')], ['tmt_stop' => null]]);

        $dataProvider->query->orderBy([
            'status' => SORT_ASC,
            'a.E_04' => SORT_DESC, 
            'a.A_01' => SORT_ASC,
            'a.A_03' => SORT_ASC,
            'a.A_01' => SORT_ASC,
            'a.G_06' => SORT_ASC,  
        ]);

        if(isset($searchModel)){
            if($searchModel['opd'] == '' && $searchModel['nip'] == '' && $searchModel['nama'] == '') 
                $dataProvider->query->andFilterWhere(['a.A_01' => '931']);
        }else $dataProvider->query->andFilterWhere(['a.A_01' => '931']);

        $bln = date('n');
        $thn = date('Y');

        $req = Yii::$app->request;
        if($req->get()){
            if($req->get('periode') !== null){
                $per = explode('-',$req->get('periode'));
                $bln = $per[0];
                $thn = $per[1];
            }
        }

        $data = $dataProvider->query->all();
        if($data !== null){
            $dt = [];
            foreach($data as $dat){
                $presensi = PresHarian::find()
                ->select(['tgl', 'kd_pr_masuk', 'kd_pr_pulang'])
                ->where([
                    'nip' => $dat['nip'], 
                    'EXTRACT(month FROM tgl::date)' => $bln, 
                    'EXTRACT(year FROM tgl::date)' => $thn,
                ]);

                if($presensi->count() > 0){
                    $pr = [];     
                    foreach($presensi->all() as $pre){
                        $pr[] = [
                            'tgl' => $pre['tgl'],
                            'kd_pr_masuk' => $pre['kd_pr_masuk'],
                            'kd_pr_pulang' => $pre['kd_pr_pulang'],
                        ];
                    }
                }else $pr = [];
                
                $dt[] = [
                    'nip' => $dat['nip'],
                    'kode_jadwal' => $dat['kode_jadwal'],
                    'nama' => PreskinAsn::getFipNamaByNip($dat['nip']), 
                    'unor' => PreskinAsn::getFipUnorByNip($dat['nip']),
                    'presensi' => $pr,
                ];
            }
        }

        $dataProvider = new ArrayDataProvider(['allModels' => $dt]);

        Url::remember();
        return $this->render('index-presensi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd, 'thn' => $thn, 'bln' => $bln,
            'bul' => strtoupper(Fungsi::NmBulan(intval($bln))),
            'namapd' => '',//$namapd,
        ]);
    }
    
    public function actionCheckinout($id, $bulan, $tahun)
    { 
        $asn = $this->findAsn($id);
        
        if($asn['asnJadwalKerja'] === null) $jk = $dt['kode_jadwal'];
        else $jk = $asn['asnJadwalKerja']['jenis'];

        $data = [
            'nip' => $id,
            'nama' => $asn['fipNama'],
            'jabatan' => $asn['fipJabatan'],
            'unor' => $asn['fipUnor'],
            'tahun' => $tahun, 
            'bulan' => $bulan,
            'bul' => ucwords(Fungsi::NmBulan(intval($bulan))),
            'jk' => $jk,
        ];

        $model = FpPresensiCheckinoutHp::find()
        ->where([
            'EXTRACT(month FROM DATE(checktime))' => $bulan, 
            'EXTRACT(year FROM DATE (checktime))' => $tahun,
            'nip' => $id,
        ])
        ->orderBy(['checktime' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('data-checkinout', [
                'data' => $data,
                'dataProvider' => $dataProvider, 
            ]);
        }else{
            return $this->render('data-checkinout', [
                'data' => $data,
                'dataProvider' => $dataProvider, 
            ]);
        } 
    }

    public function actionDetail($id, $bulan, $tahun)
    {
        $asn = $this->findAsn($id);

        if($asn['asnJadwalKerja'] === null) $jk = $dt['kode_jadwal'];
        else $jk = $asn['asnJadwalKerja']['jenis'];

        $model = FpPresensiCheckinoutHp::find()
        ->where([
            'EXTRACT(month FROM DATE(checktime))' => $bulan, 
            'EXTRACT(year FROM DATE (checktime))' => $tahun,
            'nip' => $id,
        ])
        ->orderBy(['checktime' => SORT_ASC]);

        $data = [
            'nip' => $id,
            'nama' => $asn['fipNama'],
            'jabatan' => $asn['fipJabatan'],
            'unor' => $asn['fipUnor'],
            'tahun' => $tahun, 
            'bulan' => $bulan,
            'bul' => ucwords(Fungsi::NmBulan(intval($bulan))),
            'jk' => $jk,
        ];

        $model = PresHarian::find()
        ->where([
            'EXTRACT(month FROM DATE(tgl))' => $bulan, 
            'EXTRACT(year FROM DATE (tgl))' => $tahun,
            'nip' => $id,
        ])
        ->orderBy(['tgl' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('data-detail', [
                'data' => $data,
                'dataProvider' => $dataProvider, 
            ]);
        }else{
            return $this->render('data-detail', [
                'data' => $data,
                'dataProvider' => $dataProvider, 
            ]);
        } 
    }

    public function actionPresensiAsn()
    {
        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->orderBy(['KOLOK' => SORT_ASC])
        ->all();
        
        $searchModel = new PreskinAsnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        
        //$dataProvider->query->andFilterWhere(['<', 'status', 13]);
        $dataProvider->query->andWhere(['OR',['<', 'status', 13],['>', 'tmt_stop', date('Y-m-01')], ['tmt_stop' => null]]);

        $dataProvider->query->orderBy([
            'status' => SORT_ASC,
            'a.E_04' => SORT_DESC, 
            'a.A_01' => SORT_ASC,
            'a.A_03' => SORT_ASC,
            'a.A_01' => SORT_ASC,
            'a.G_06' => SORT_ASC,  
        ]);

        if(isset($searchModel)){
            if($searchModel['opd'] == '' && $searchModel['nip'] == '' && $searchModel['nama'] == '') 
                $dataProvider->query->andFilterWhere(['a.A_01' => '931']);
        }else $dataProvider->query->andFilterWhere(['a.A_01' => '931']);

        $bln = date('n');
        $thn = date('Y');

        $req = Yii::$app->request;
        if($req->get()){
            if($req->get('periode') !== null){
                $per = explode('-',$req->get('periode'));
                $bln = $per[0];
                $thn = $per[1];
            }
        }

        $searchModel['bulan'] = $bln;
        $searchModel['tahun'] = $thn;

        $data = $dataProvider->query->all();

        if($data !== null){
            $dt = [];
            foreach($data as $dat){
                $pr = PresHarian::find()
                ->select(['SUM(pot_masuk) AS jml_pot_masuk', 'SUM(pot_pulang) AS jml_pot_pulang'])
                ->asArray()
                ->where([
                    'nip' => $dat['nip'], 
                    'EXTRACT(month FROM tgl::date)' => $bln, 
                    'EXTRACT(year FROM tgl::date)' => $thn,
                ])->one();

                if($pr !== null){      
                    $dt[] = [
                        'nip' => $dat['nip'],
                        'nama' => PreskinAsn::getFipNamaByNip($dat['nip']), 
                        'unor' => PreskinAsn::getFipUnorByNip($dat['nip']),
                        'pot_masuk' => round($pr['jml_pot_masuk'],2),
                        'pot_pulang' => round($pr['jml_pot_pulang'],2),
                    ];
                }else{
                    $dt[] = [
                        'nip' => $dat['nip'],
                        'nama' => PreskinAsn::getFipNamaByNip($dat['nip']), 
                        'unor' => PreskinAsn::getFipUnorByNip($dat['nip']),
                        'pot_masuk' => 0,
                        'pot_pulang' => 0,
                    ];
                }
            }
        }

        $dataProvider = new ArrayDataProvider(['allModels' => $dt]);

        Url::remember();
        return $this->render('index-presensi-asn', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd, 'thn' => $thn, 'bln' => $bln,
            'bul' => strtoupper(Fungsi::NmBulan(intval($bln))),
            'namapd' => '',//$namapd,
        ]);
    }

    public function actionAdms($id, $bulan, $tahun)
    {
        if(strlen($bulan) == 1) $bulan = '0'.$bulan;
        $data = [
            'nip' => $id,
            'nama' => PreskinAsn::getFipNamaByNip($id), 
            'unor' => PreskinAsn::getFipUnorByNip($id),
            'tahun' => $tahun, 'bulan' => $bulan,
            'bul' => strtoupper(Fungsi::NmBulan(intval($bulan))),
        ];
                        
        $adms = VpresensiAdms::find()
            ->where(['like', 'checktime', "$tahun-$bulan-"])
            ->andWhere(['badgenumber' => $id])
            ->orderBy(['checktime' => SORT_ASC])
        ;


        $dataProvider = new ActiveDataProvider([
            'query' => $adms,
            'pagination' => [
                'pageSize' => false,
            ],
        ]);

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('data-adms', [
                'data' => $data,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return $this->render('data-adms', [
                'data' => $data,
                'dataProvider' => $dataProvider,
            ]);
        } 
    }

    protected function findPotPres($id, $bulan, $tahun)
    {
        $model = PresHarian::find()
        ->select(['SUM(pot_masuk) AS jml_pot_masuk', 'SUM(pot_pulang) AS jml_pot_pulang'])
        ->where([
            'nip' => $id,
            'EXTRACT(month FROM tgl::date)' => $bulan, 
            'EXTRACT(year FROM tgl::date)' => $tahun,
        ])->asArray()
        ->one();
        
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionKinerja()
    {
        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->all();
        
        $searchModel = new PreskinAsnSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        
        $dataProvider->query->andFilterWhere(['<', 'status', 13]);

        $dataProvider->query->orderBy([
            'status' => SORT_ASC,
            'a.E_04' => SORT_DESC, 
            'a.A_01' => SORT_ASC,
            'a.A_03' => SORT_ASC,
            'a.A_01' => SORT_ASC,
            'a.G_06' => SORT_ASC,  
        ]);

        if(isset($searchModel)){
            if($searchModel['opd'] == '' && $searchModel['nip'] == '' && $searchModel['nama'] == '') 
                $dataProvider->query->andFilterWhere(['a.A_01' => '931']);
        }else $dataProvider->query->andFilterWhere(['a.A_01' => '931']);


        $bln = date('n');
        $thn = date('Y');

        $req = Yii::$app->request;
        if($req->get()){
            if($req->get('periode') !== null){
                $per = explode('-',$req->get('periode'));
                $bln = $per[0];
                $thn = $per[1];
            }
        }

        $nbln = $bln+1;

        $data = $dataProvider->query->all();
        if($data !== null){
            $dt = [];
            foreach($data as $dat){
                $presensi = KinHarian::find()
                ->select(['penilai_nip', 'penilai_nama', 'SUM("target_waktu_h") AS target', 'SUM("real_waktu_h") AS realisasi', 'SUM("ok_waktu_h") AS hasil'])
                ->where([
                    'nip' => $dat['nip'], 
                    'EXTRACT(month FROM tgl::date)' => $bln, 
                    'EXTRACT(year FROM tgl::date)' => $thn,
                ])
                ->andWhere(['<', 'tgl_ok', "$thn-$nbln-03 23:59:59"])
                ->asArray()
                ->groupBy(['nip', 'penilai_nip', 'penilai_nama']);

                $target = 0;
                $realisasi = 0;
                $hasil = 0;  
                $nip_atasan = '';
                $nama_atasan = '';
                if($presensi->count() > 0){ 
                    foreach($presensi->all() as $pre){
                        $target = $pre['target'];
                        $realisasi = $pre['realisasi'];
                        $hasil = $pre['hasil'];
                        $nip_atasan = $pre['penilai_nip'];
                        $nama_atasan = $pre['penilai_nama'];
                    }
                }
                
                $dt[] = [
                    'nip' => $dat['nip'],
                    'nama' => PreskinAsn::getFipNamaByNip($dat['nip']), 
                    'unor' => PreskinAsn::getFipUnorByNip($dat['nip']),
                    'thn' => $thn, 'bln' => $bln,
                    'target' => $target,
                    'realisasi' => $realisasi,
                    'hasil' => $hasil,  
                    'nip_atasan' => $nip_atasan,
                    'nama_atasan' => $nama_atasan,
                ];
            }
        }

        $dataProvider = new ArrayDataProvider(['allModels' => $dt]);

        Url::remember();
        return $this->render('data-kinerja', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd, 'thn' => $thn, 'bln' => $bln,
            'bul' => strtoupper(Fungsi::NmBulan(intval($bln))),
            'namapd' => '',
        ]);
    }

    public function actionDetailKinerja($id, $bulan, $tahun)
    {        
        $searchModel = new KinHarianSearch([
            'nip' =>  $id,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);  
        $dataProvider->query->orderBy(['tgl' => SORT_ASC]);      

        $nbln = $bulan+1;

        $sum_target = 0;
        $sum_real = 0;
        $sum_ok = 0;
        foreach ($dataProvider->getModels() as  $value) {
            $sum_target += $value['target_waktu_h'];
            $sum_real += $value['real_waktu_h'];
            $sum_ok += $value['ok_waktu_h'];
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('detail-kinerja', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'sum_target' => $sum_target,
                'sum_real' => $sum_real,
                'sum_ok' => $sum_ok,
                //'thn' => $thn, 'bln' => $bln,
                //'bul' => strtoupper(Fungsi::NmBulan(intval($bln))),
                //'namapd' => '',
            ]);
        }else{
            return $this->render('detail-kinerja', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'sum_target' => $sum_target,
                'sum_real' => $sum_real,
                'sum_ok' => $sum_ok,
            ]);
        }
    }

    public function actionTpp()
    {
        $arbul = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            13 => 'Bulan Ke 13',
            14 => 'Bulan Ke 14 /THR',
        ];

        //$arta = [];
        //for ($y = date('Y'); $y >= date('Y') - 5; $y--){
        //}
        $varta = date('Y') - 4;
        $arta = [];
        for ($y = $varta; $y < $varta + 5; $y++){
            $arta["$y"] = $y;
        }

        $opdlist = PreskinGroupCetak::find()
        ->select(['cetak_group', 'opd', "CONCAT(cetak_group,' ', opd) AS nama_opd"])
        ->asArray()
        ->groupBy(['cetak_group', 'opd'])
        ->orderby(['cetak_group' => SORT_ASC])
        ->all();   

        return $this->render('form-cetak-tpp', [
            'arta' => $arta, 'arbul' => $arbul, 'opdlist' => $opdlist,
        ]);
    }

    public function actionCetakPdf()
    {
        $post = Yii::$app->request->post();
        $tahun = $post['tahun'];
        $bulan = $post['bulan'];
        $opd = $post['opd'];
        $status = $post['status'];

        if($bulan == 1) $bulan_huruf = 'Bulan Januari';
        elseif($bulan == 2) $bulan_huruf = 'Bulan Februari';
        elseif($bulan == 3) $bulan_huruf = 'Bulan Maret';
        elseif($bulan == 4) $bulan_huruf = 'Bulan April';
        elseif($bulan == 5) $bulan_huruf = 'Bulan Mei';
        elseif($bulan == 6) $bulan_huruf = 'Bulan Juni';
        elseif($bulan == 7) $bulan_huruf = 'Bulan Juli';
        elseif($bulan == 8) $bulan_huruf = 'Bulan Agustus';
        elseif($bulan == 9) $bulan_huruf = 'Bulan September';
        elseif($bulan == 10) $bulan_huruf = 'Bulan Oktober';
        elseif($bulan == 11) $bulan_huruf = 'Bulan November';
        elseif($bulan == 12) $bulan_huruf = 'Bulan Desember';
        elseif($bulan == 13) $bulan_huruf = 'Ketiga Belas Tahun';
        elseif($bulan == 14) $bulan_huruf= 'Tunjangan Hari Raya Tahun';

        $group_cetak = PreskinGroupCetak::find()
        ->select(['cetak_group', 'opd_group', 'opd'])
        ->where(['cetak_group' => $opd])
        ->groupBy(['cetak_group', 'opd_group', 'opd'])
        ->orderBy(['cetak_group' => SORT_ASC]);
        
        $group = [];
        foreach($group_cetak->all() as $gcet){
            $group[] = [
                'cetak_group' => $gcet['cetak_group'],
                'opd_group_nama' => $gcet['opd'],
            ];
        }

        //return Json::encode($group);

        $rum = [];
        $trekap = [];
        $tdetail = [];
        $no = 0;
        $nd = 0;
        for ($i = 4; $i > 0; $i--) {
            if($i == 1) {
                $x = 'I';
                $awal = 11;
                $akhir = 14;
            }elseif($i == 2){ 
                $x = 'II';
                $awal = 21;
                $akhir = 24;
            }elseif($i == 3){ 
                $x = 'III';
                $awal = 31;
                $akhir = 34;
            }elseif($i == 4){ 
                $x = 'IV';
                $awal = 41;
                $akhir = 45;
            }

            $tpp_rekap = PreskinTppHitung::find()
            ->select([
                'count(nip) as jml_pegawai',
                'sum(pagu_total) as jml_pagu_total',
                'sum(pagu_tpp) as jml_pagu_tpp',
                'sum(beban_kerja) as jml_beban_kerja',
                'sum(produktivitas_kerja) as jml_prod_kerja',
                'sum(kinerja_rp) as jml_kinerja_rp',
                'sum(presensi_rp) as jml_presensi_rp',
                'sum(sakip_rp) as jml_sakip_rp',
                'sum(rb_rp) as jml_rb_rp',
                'sum(tpp_jumlah) as jml_tpp_jumlah',
                'sum(cuti_rp) as jml_cuti_rp',
                'sum(hukdis_rp) as jml_hukdis_rp',
                'sum(tgr_rp) as jml_tgr_rp',
                'sum(pengurangan) as jml_pengurangan',
                'sum(tpp_total) as jml_tpp_total',
                'sum(pph_rp) as jml_pph_rp',
                'sum(bpjs4) as jml_bpjs4',
                'sum(tpp_bruto) as jml_tpp_bruto',
                'sum(bpjs1) as jml_bpjs1',
                'sum(pot_jml) as jml_pot_jml',
                'sum(tpp_net) as jml_tpp_net',
            ])->asArray()
            ->where([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'tablok' => $opd,
            ])
            ->andWhere([
                'and',['<=', 'gol', $akhir],['>=', 'gol', $awal]
            ])
            //->andWhere(['<', 'status', 99])
            ->andWhere(['status' => $status])
            ->all();

            $rekap = [];
            foreach($tpp_rekap as $trek){
                $no = $no + 1;
                $rekap = [
                    'no' => $no,
                    'gg' => $i,
                    'grum' => $x,
                    'jml_pegawai' => $trek['jml_pegawai'],
                    'jml_pagu_total' => $trek['jml_pagu_total'],
                    'jml_pagu_tpp' => $trek['jml_pagu_tpp'],
                    'jml_beban_kerja' => $trek['jml_beban_kerja'],
                    'jml_prod_kerja' => $trek['jml_prod_kerja'],
                    'jml_kinerja_rp' => $trek['jml_kinerja_rp'],
                    'jml_presensi_rp' => $trek['jml_presensi_rp'],
                    'jml_sakip_rp' => $trek['jml_sakip_rp'],
                    'jml_rb_rp' => $trek['jml_rb_rp'],
                    'jml_tpp_jumlah' => $trek['jml_tpp_jumlah'],
                    'jml_cuti_rp' => $trek['jml_cuti_rp'],
                    'jml_hukdis_rp' => $trek['jml_hukdis_rp'],
                    'jml_tgr_rp' => $trek['jml_tgr_rp'],
                    'jml_pengurangan' => $trek['jml_pengurangan'],
                    'jml_tpp_total' => $trek['jml_tpp_total'],
                    'jml_pph_rp' => $trek['jml_pph_rp'],
                    'jml_bpjs4' => $trek['jml_bpjs4'],
                    'jml_tpp_bruto' => $trek['jml_tpp_bruto'],
                    'jml_bpjs1' => $trek['jml_bpjs1'],
                    'jml_pot_jml' => $trek['jml_pot_jml'],
                    'jml_tpp_net' => $trek['jml_tpp_net'],
                ];
            }  

            $trekap[] = $rekap;

            //detail tpp per pegawai
            $tpp_detail = PreskinTppHitung::find()
            ->select([
                'nip',
                'nama',
                'nama_gol',
                'kelas_jab_tpp',
                'nama_kelas',
                'nama_jab',
                'pagu_total',
                'persen_tpp',
                'pagu_tpp',
                'beban_kerja',
                'produktivitas_kerja as prestasi_kerja',
                'kinerja',
                'kinerja_rp',
                'presensi',
                'presensi_rp',
                'sakip',
                'sakip_rp',
                'rb',
                'rb_rp',
                'cuti',
                'cuti_rp',
                'hukdis',
                'hukdis_rp',
                'tgr',
                'tgr_rp',
                'tpp_jumlah',
                'bpjs4',
                'bpjs1',
                'pengurangan',
                'tpp_total',
                'pph',
                'pph_rp',
                'tpp_bruto',
                'pot_jml',
                'tpp_net',
            ])->asArray()
            ->where([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'tablok' => $opd,
            ])
            ->andWhere([
                'and',['<=', 'gol', $akhir],['>=', 'gol', $awal]
            ])
            //->andWhere(['<', 'status', 99])
            ->andWhere(['status' => $status])
            ->orderBy(['gol' => SORT_DESC, 'kelas_jab_tpp' => SORT_DESC, 'tablokb' => SORT_ASC])
            ->all();

            $rinci = [];
            $dj_asn = 0;
            $dj_pagu = 0;
            $dj_beban = 0;
            $dj_prestasi = 0;
            $dj_kinerja = 0;
            $dj_presensi = 0;
            $dj_sakip = 0;
            $dj_rb = 0;
            $dj_cuti = 0;
            $dj_hukdis = 0;
            $dj_tgr = 0;
            $dj_total = 0;
            $dj_bpjs4 = 0;
            $dj_bpjs1 = 0;
            $dj_bruto = 0;
            $dj_pph = 0;
            $dj_pot = 0;
            $dj_net = 0;
            
            foreach($tpp_detail as $det){
                $nd = $nd + 1;
                // $tppkelas = \app\modules\tpp\models\TppPaguKelas::findOne($det['kode_kelas_jab']);
                // if($tppkelas !== null) $kelasjab = $tppkelas['kelas'];
                // else $kelasjab = "-";  

                $rinci[] = [
                    'nd' => $nd,
                    'nip' => $det['nip'],
                    'nama' => $det['nama'],
                    'nama_gol' => $det['nama_gol'],
                    'nama_jab' => $det['nama_jab'],
                    'kode_kelas_jab' => $det['kelas_jab_tpp'],
                    'kelasjab' => $det['nama_kelas'],
                    'pagu_total' => $det['pagu_total'],
                    'persen_tpp' => $det['persen_tpp'],
                    'pagu_tpp' => $det['pagu_tpp'],
                    'beban_kerja' => $det['beban_kerja'],
                    'prestasi_kerja' => $det['prestasi_kerja'],
                    'kinerja' => $det['kinerja'],
                    'kinerja_rp' => $det['kinerja_rp'],
                    'presensi' => $det['presensi'],
                    'presensi_rp' => $det['presensi_rp'],
                    'sakip' => $det['sakip'],
                    'sakip_rp' => $det['sakip_rp'],
                    'rb' => $det['rb'],
                    'rb_rp' => $det['rb_rp'],
                    'cuti' => $det['cuti'],
                    'cuti_rp' => $det['cuti_rp'],
                    'hukdis' => $det['hukdis'],
                    'hukdis_rp' => $det['hukdis_rp'],
                    'tgr' => $det['tgr'],
                    'tgr_rp' => $det['tgr_rp'],
                    'tpp_jumlah' => $det['tpp_jumlah'],
                    'bpjs4' => $det['bpjs4'],
                    'bpjs1' => $det['bpjs1'],
                    'pengurangan' => $det['pengurangan'],
                    'tpp_total' => $det['tpp_total'],
                    'pph' => $det['pph'],
                    'pph_rp' => $det['pph_rp'],
                    'tpp_bruto' => $det['tpp_bruto'],
                    'pot_jml' => $det['pot_jml'],
                    'tpp_net' => $det['tpp_net'],                    
                ];
                $dj_asn = $dj_asn + 1;
                $dj_pagu = $dj_pagu + $det['pagu_tpp'];
                $dj_beban = $dj_beban + $det['beban_kerja'];
                $dj_prestasi = $dj_prestasi + $det['prestasi_kerja'];
                $dj_kinerja = $dj_kinerja + $det['kinerja_rp'];
                $dj_presensi = $dj_presensi + $det['presensi_rp'];
                $dj_sakip = $dj_sakip + $det['sakip_rp'];
                $dj_rb = $dj_rb + $det['rb_rp'];
                $dj_cuti = $dj_cuti + $det['cuti_rp'];
                $dj_hukdis = $dj_hukdis + $det['hukdis_rp'];
                $dj_tgr = $dj_tgr + $det['tgr_rp'];
                $dj_total = $dj_total + $det['tpp_total'];
                $dj_bpjs4 = $dj_bpjs4 + $det['bpjs4'];
                $dj_bpjs1 = $dj_bpjs1 + $det['bpjs1'];
                $dj_bruto = $dj_bruto + $det['tpp_bruto'];
                $dj_pph = $dj_pph + $det['pph_rp'];
                $dj_pot = $dj_pot + $det['pot_jml'];
                $dj_net = $dj_net + $det['tpp_net'];
            }  

            $tdetail[] = [
                'gd' => $i,
                'gdrum' => $x,
                'dj_asn' => $dj_asn,
                'dj_pagu' => $dj_pagu,
                'dj_beban' => $dj_beban,
                'dj_prestasi' => $dj_prestasi,
                'dj_kinerja' => $dj_kinerja,
                'dj_presensi' => $dj_presensi,
                'dj_sakip' => $dj_sakip,
                'dj_rb' => $dj_rb,
                'dj_cuti' => $dj_cuti,
                'dj_hukdis' => $dj_hukdis,
                'dj_tgr' => $dj_tgr,
                'dj_total' => $dj_total,
                'dj_bpjs4' => $dj_bpjs4,
                'dj_bpjs1' => $dj_bpjs1,
                'dj_bruto' => $dj_bruto,
                'dj_pph' => $dj_pph,
                'dj_pot' => $dj_pot,
                'dj_net' => $dj_net,
                'rinci' => $rinci,
            ];
            // end detail tpp
        }

        $data[] = [
            'bulan' => $bulan,
            'bulan_huruf' => $bulan_huruf,
            'tahun' => $tahun,
            'opd_nama' => $group[0]['opd_group_nama'],
            'rekap' => $trekap,
            'detail' => $tdetail,
        ];
        
        //return Json::encode($data);
        
        
        $content =  $this->renderPartial('tpp-cetak-pdf', [
            'data' => $data, 'group' => $group,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, 
            'format' => Pdf::FORMAT_FOLIO, 
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            'destination' => Pdf::DEST_BROWSER, 
            'content' => $content,  
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:12px}', 
            'options' => ['title' => 'Cetak TPP '.$group[0]['opd_group_nama']],
            'methods' => [ 
                //'SetHeader'=>['TPP '.ucfirst(Fungsi::NmBulan($bulan).' '.$tahun)], 
                'SetFooter'=>['
                <div align="right"> '.$group[0]['opd_group_nama'].' : Hal. {PAGENO}</div>
                '],
            ]
        ]);

        if($status == 0) {
            $mpdf = $pdf->getApi();
            $mpdf->SetWatermarkText(' - -  D R A F T   - - ');
            $mpdf->showWatermarkText = true;
        }
        return $pdf->render(); 
    }

    public function actionReset()
    {
        $searchModel = new FpTbDaftarfingerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['kodealamat' => SORT_ASC]);

        $lokasi = FpTbAlamat2::find()->orderBy(['kodealamat' => SORT_ASC])->all();

        if(isset($searchModel)){
            if($searchModel['kodealamat'] == '' && $searchModel['nip'] == '' && $searchModel['nama'] == '') 
                $dataProvider->query->andFilterWhere(['kodealamat' => '9999999']);
        }else $dataProvider->query->andFilterWhere(['kodealamat' => '9999999']);

        Url::remember();
        return $this->render('reset', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'lokasi' => $lokasi,
        ]);
    }

    public function actionPassword($id)
    {
        $model = $this->findModelPres($id);
        $model['password'] = md5(substr($model['nip'],0,8));
        if($model->save(false)){

            $log = new FpLogAktivitas();
            $log['kode'] = $model['nip'].'-'.time();
            $log['nip'] = $model['nip'];
            $log['tgl'] = date('Y-m-d H:i:s');
            $log['aktivitas'] = 4;
            $log->save(false);

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Reset Password APK berhasil !!!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }else{
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                'title' => 'GAGAL',
                'text' => 'Reset Password APK Gagal !!!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
        }
    }
    
    public function actionDevice($id)
    {
        $model = $this->findModelPres($id);
        $model['device'] = '';
        if($model->save(false)){
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
        }else{
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                'title' => 'GAGAL',
                'text' => 'Reset Device Gagal !!!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
        }
    }

    public function actionSimasganteng($id)
    {
        $model = $this->findSimas($id);
        $pres = $this->findModelPres($id);

        if($model == '') {
            $nor = User::find()->select(['id'])->orderBy(['id' => SORT_DESC])->one();
            $no = $nor['id'] + 1;
            $model = new User(['id' => $no]);
            $model['username'] = $id;
            $model['nipBaru'] = $id;
            
            $siasn = $this->findSiasn($id);

            if($siasn == ''){
                return Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR,
                    'title' => 'Gagal',
                    'text' => 'Data ASN tidak ditemukan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }

            $model['pengguna'] = $siasn['id'];
            $model['namapengguna'] = $siasn['nama'];
            $model['tablok'] = $pres['fpTablokb'];
            $model['namaopd'] = $pres['fpUnor'];

            $model->setPassword(substr($id,0,8));
            $model->generateAuthKey();
            $model['modified'] = date('Y-m-d H:i:s');
            $model['flag'] = 0;
            $model['role'] = 2;
            $model['status'] = 10;
            $model['updateby'] = Yii::$app->session['nip'].'-'.Yii::$app->session['namapengguna'];
            
            if($model->save(false)) {
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                    'title' => 'Berhasil',
                    'text' => 'Data berhasil disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000,
                ]);   
            }else{
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR,
                    'title' => 'Gagal',
                    'text' => 'Data gagal disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }
            return $this->redirect(Url::previous());
        }

        $iduser = $model['id'];
        User::defaultPassword($iduser);

        Yii::$app->session->setFlash('position', [
            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
            'title' => 'Berhasil',
            'text' => 'Reset Password Simasganteng berhasil !!!',
            'showConfirmButton' => false,
            'timer' => 1000
        ]);
        return $this->redirect(Url::previous());
    }

    public function actionTambah()
    {
        $model = new FpTbDaftarfinger();
        $lokasi = FpTbAlamat2::find()->orderBy(['kodealamat' => SORT_ASC])->all();

        if ($model->load(Yii::$app->request->post())) {
            
            $cek = FpTbDaftarfinger::findOne($model['nip']);

            if($cek === null){
                $model->save(false);

                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Berhasil',
                    'text' => 'Data berhasil disimpan',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }else{
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Gagal',
                    'text' => 'Data NIP.'. $model['nip'] .' sudah ada',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]); 
            }

            return $this->redirect(Url::previous());
        }
        
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('tambah', [
                'model' => $model, 'lokasi' => $lokasi
            ]);
        }else{
            return $this->render('tambah', [
                'model' => $model, 'lokasi' => $lokasi
            ]);
        } 
    }

    public function actionPreskinAsn(){

        $searchModel = new PreskinAsnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if(isset($searchModel['status'])){
            if($searchModel['status'] == '') {
                $dataProvider->query->andWhere(['OR',['<', 'status', 13],['>', 'tmt_stop', date('Y-m-01')], ['tmt_stop' => null]]);
            }
        }else{
            $dataProvider->query->andWhere(['OR',['<', 'status', 13],['>', 'tmt_stop', date('Y-m-01')], ['tmt_stop' => null]]);
        }

        $dataProvider->query->orderBy([
            'status' => SORT_ASC,
            'a.E_04' => SORT_DESC, 
            'a.A_01' => SORT_ASC,
            'a.A_03' => SORT_ASC,
            'a.A_01' => SORT_ASC,
            'a.G_06' => SORT_ASC, 
            'nip' => SORT_ASC, 
 
        ]);
        
        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->all();

        $sta = SimasGajiStapegTbl::find()
        ->select(['KDSTAPEG', 'NMSTAPEG'])
        ->orderBy(['NMSTAPEG' => SORT_ASC])
        ->all();

        $jad = PreskinHariKerja::find()->orderBy(['id' => SORT_ASC])->all();
        $hk[0] = ['id' => 0, 'jenis' => '0 - Belum dibuat jadwal'];
        foreach($jad as $h){
            $hk[] = ['id' => $h['id'], 'jenis' => $h['id'].' - '.$h['jenis']];
        }

        if(isset($searchModel)){
            if($searchModel['opd'] == '' && $searchModel['nip'] == '' && $searchModel['nama'] == '') 
                $dataProvider->query
                ->andFilterWhere(['OR',['CONCAT("a"."A_01",a."A_02",a."A_03",a."A_04")' => '9999999']]);
        }

        Url::remember();
        return $this->render('preskin-asn', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd,
            'sta' => $sta,
            'hk' => $hk,
        ]);
    }

    public function actionPreskinCreate()
    {
        $model = new PreskinAsn();

        $jad = PreskinHariKerja::find()->all();
        $kelas = PreskinPaguTpp::find()->all();
        $sta = SimasGajiStapegTbl::find()
        ->select(['KDSTAPEG', 'NMSTAPEG'])
        ->orderBy(['NMSTAPEG' => SORT_ASC])
        ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data berhasil disimpan !!!',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('preskin-create', [
                'model' => $model, 'jad' => $jad, 'kelas' => $kelas, 'sta' => $sta,
            ]);
        }else{
            return $this->render('preskin-create', [
                'model' => $model, 'jad' => $jad, 'kelas' => $kelas, 'sta' => $sta,
            ]);
        } 
    }

    public function actionPreskinUpdate($id)
    {
        $model =  $this->findAsn($id);

        $jad = PreskinHariKerja::find()->all();
        $kelas = PreskinPaguTpp::find()->all();
        $sta = SimasGajiStapegTbl::find()
        ->select(['KDSTAPEG', 'NMSTAPEG'])
        ->orderBy(['NMSTAPEG' => SORT_ASC])
        ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data berhasil disimpan !!!',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('preskin-update', [
                'model' => $model, 'jad' => $jad, 'kelas' => $kelas, 'sta' => $sta,
            ]);
        }else{
            return $this->render('preskin-update', [
                'model' => $model, 'jad' => $jad, 'kelas' => $kelas, 'sta' => $sta,
            ]);
        } 
    }

    public function actionGetAsn($zipId){
        $fip = EpsMastfip::find()->where(['B_02' => $zipId])->one();  
        
        if($fip === null) $data = '';
        else{  
            $data = [
                "nipAtasan" => $fip['B_02'],
                "namaAtasan" => $fip['B_03'],
                "tablokAtasan" => $fip->fipNalok($fip['A_01'].'00'.$fip['A_03'].$fip['A_04']).
                    ' - '.$fip->fipNalok($fip['A_01'].'000000'),    
            ];
            return Json::encode($data);
        }
 
    }
}
