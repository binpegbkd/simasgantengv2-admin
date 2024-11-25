<?php

namespace app\modules\sitampan\controllers;

use Yii;
use app\modules\sitampan\models\PreskinTppHitung;
use app\modules\sitampan\models\PreskinTppHitungSearch;
use app\modules\sitampan\models\PreskinGroupCetak;
use app\modules\sitampan\models\PreskinAsn;
use app\modules\sitampan\models\PreskinAsnSearch;
use app\modules\sitampan\models\PreskinPaguTpp;
use app\modules\sitampan\models\PreskinTambahTppOpd;
use app\modules\sitampan\models\PreskinTambahTppJab;
use app\modules\sitampan\models\PresHarian;
use app\modules\sitampan\models\KinHarian;
use app\modules\simpeg\models\EpsMastfip;
use app\modules\simpeg\models\EpsTablokb;
use app\modules\integrasi\models\SiasnDataUtama;
use app\modules\integrasi\models\SiasnRefGol;
use app\modules\gaji\models\DbgajidoFgaji;

use app\models\Fungsi;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;
use kartik\mpdf\Pdf;

/**
 * PreskinTppHitungController implements the CRUD actions for PreskinTppHitung model.
 */
class PreskinTppHitungController extends Controller
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
                    'final' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PreskinTppHitung models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PreskinTppHitungSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $arbul = Fungsi::CariBulan();
        $arbul[13] = 'Bulan ke-13';
        $arbul[14] = 'Bulan ke-14';

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'arbul' => $arbul,
        ]);
    }

    public function actionTppPlt()
    {
        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->all();
        
        $searchModel = new PreskinTppHitungSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);     
        $dataProvider->query->andFilterWhere(['jtrans' => 4]);   
        $dataProvider->query->andFilterWhere(['<', 'status', 99]);

        $dataProvider->query->orderBy([
            'status' => SORT_ASC,
            'gol' => SORT_DESC, 
            'tablok' => SORT_ASC,
            'tablokb' => SORT_ASC,
            'jenis_jab' => SORT_ASC,  
        ]);

        if(strtotime(date('Y-m-d H:i:s')) < strtotime(date('Y-m-04 00:00:00'))) $bln = date('n')-1;
        else $bln = date('n');
        
        $thn = date('Y');
        if($bln == 0){
            $bln = 12;
            $thn = $thn - 1;
        }

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
        $dataProvider->query->andFilterWhere(['bulan' => $bln, 'tahun' => $thn]);
        
        $pd = EpsTablokb::findOne($searchModel['tablok']);
        if($pd !== null) $namapd = $pd['NALOK']; else $namapd = ''; 

        Url::remember();
        return $this->render('tpp-plt', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd, 'thn' => $thn, 'bln' => $bln,
            'bul' => strtoupper(Fungsi::NmBulan(intval($bln))),
            'namapd' => $namapd,
        ]);
    }

    public function actionPns()
    {
        $searchModel = new PreskinTppHitungSearch(['tahun' => date('Y'), 'bulan' => date('n')-1]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['<>', 'status', 99]);
        $dataProvider->query->orderBy(['kelas_jab_tpp' => SORT_DESC, 'gol' => SORT_DESC, 'tablokb' => SORT_ASC, ]);

        $arbul = Fungsi::CariBulan();
        $arbul[13] = 'Bulan ke-13';
        $arbul[14] = 'Bulan ke-14';

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'arbul' => $arbul,
        ]);
    }

    

    /**
     * Displays a single PreskinTppHitung model.
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
     * Creates a new PreskinTppHitung model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new PreskinTppHitung();
        $model['tahun'] = date('Y');
        $model['bulan'] = date('n')-1;
        $model['jtrans'] = $id;

        if ($model->load(Yii::$app->request->post())) {
            $model['id'] =  $model['tahun']."-".$model['bulan']."-".$model['jtrans']."-".$model['nip'];
            $nip = $model['nip'];

            $fip = EpsMastfip::find()->where(['B_02' => $nip])->one();

            if($fip === null){
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                    'title' => 'Gagal',
                    'text' => 'Data pegawai pada database simpeg tidak ditemukan ...!',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]);
            }else{
                $model['nama'] = $fip['namaPegawai'];
                $model['tablok'] = $fip['A_01'].'000000';
                $model['tablokb'] = $fip['A_01'].$fip['A_02'].$fip['A_03'].$fip['A_04'];
                $model['jenis_jab'] = $fip['G_05A'];
                $model['kode_jab'] = $fip['G_05'];
                $model['nama_jab'] = $fip['G_05B'];
                $model['gol'] = $fip['E_04'];

                $asn = PreskinAsn::find()->where(['nip' => $nip])->one();

                $model['kelas_jab_tpp'] = $asn['kode_kelas_jab'];
                $model['idpns'] = $asn['idpns'];

                $nalok = $this->getTablok($model['tablok']);
                $model['nama_opd'] = $nalok;

                $nalok = $this->getTablok($model['tablokb']);
                $model['nama_unor'] = $nalok;

                $pagutpp = $this->getPaguTpp($model['kelas_jab_tpp']);
                if($pagutpp != 0){
                    $model['pagu'] = $pagutpp['pagu'];
                    $model['nama_kelas'] = $pagutpp['kelas'];
                }

                $model->save();

                $this->cekTambahJab($model['id']);
                $this->cekTambahOpd($model['id']);    

                $tp = $this->findModel($model['id']);
                

                $pagu_tpp = $tp['pagu_tpp'];
                $beban_kerja = round($pagu_tpp*0.7,0);  
                $produktivitas_kerja = $pagu_tpp - $beban_kerja; 
                $pagu_presensi = round($produktivitas_kerja * 0.4,0);
                $pagu_kinerja = $produktivitas_kerja - $pagu_presensi; 

                $tp['beban_kerja'] = $beban_kerja;  
                $tp['produktivitas_kerja'] = $produktivitas_kerja; 

                $dtpres = PresHarian::find()
                ->select(['SUM("pot_masuk") as "mas"', 'SUM("pot_pulang") as "pul"'])
                ->where([
                    'nip' => $nip,
                    'EXTRACT(MONTH FROM tgl::DATE)' => $model['bulan'],
                    'EXTRACT(YEAR FROM tgl::DATE)' => $model['tahun'],
                ])->asArray()->one();

                $jml = PresHarian::find()
                ->where([
                    'nip' => $nip,
                    'EXTRACT(MONTH FROM tgl::DATE)' => $model['bulan'],
                    'EXTRACT(YEAR FROM tgl::DATE)' => $model['tahun'],
                ])->count();
                $batas = $jml * 0.5;

                if($dtpres === null) $hpres = 0;
                else{
                    $sumpres = $dtpres['mas'] + $dtpres['pul'];
                    if($sumpres == $batas) $hpres = 0;
                    else $hpres = 100 - $sumpres;
                }

                if($hpres < 0) $hpres = 0;
                
                $tp['presensi'] = $hpres;
                $tp['presensi_rp'] = round($tp['presensi']/100 * $pagu_presensi,0);

                if($tp['persen_tpp'] != 15){
                    $jmlcuti = PresHarian::find()
                    ->where([
                        'nip' => $nip,
                        'EXTRACT(MONTH FROM tgl::DATE)' => $model['bulan'],
                        'EXTRACT(YEAR FROM tgl::DATE)' => $model['tahun'],
                    ])
                    ->andWhere(['ilike', 'kd_pr_masuk', 'C'])->count();

                    if($jmlcuti >= 13) $tp['cuti'] = 10;
                    elseif($jmlcuti >= 18) $tp['cuti'] = 20;
                    elseif($jmlcuti > 21) $tp['cuti'] = 30;

                }else{
                    // nakes
                    $tp['presensi'] = 100;
                    $tp['presensi_rp'] = round($tp['presensi']/100 * $pagu_presensi,0);
                    $tp['beban_kerja'] = $beban_kerja;
                    // April 2024
                    // $jml = 16;
                    // $jmlcuti = 16;
                }
                
                if($jml == $jmlcuti) $tp['beban_kerja'] = 0;

                if($tp['cuti'] > 0){
                    $tp['presensi'] = $tp['presensi'] - $tp['cuti'];
                    $tp['cuti_rp'] = round($pagu_presensi * $tp['cuti']/100,0);
                    $tp['presensi_rp'] = $pagu_presensi - $tp['cuti_rp'];
                    
                    if($tp['cuti'] >= 30) $tp['beban_kerja'] = 0;
                }
                //$tp['beban_kerja'] = 0;

                $tp['sakip'] = 100; //lihat di tabel tpp_sakip_rb
                $tp['sakip_rp'] = round($tp['sakip']/100 * $pagu_kinerja * 0.1,0);

                //kinerja
                //$tp['kinerja'] = 100; //kinerja 100 %

                $nb = $model['bulan'] + 1;
                if($nb == 13) $nt = $model['tahun'] + 1; else $nt = $model['tahun'];
                $dtkin = KinHarian::find()
                ->select(['SUM("ok_waktu_h") as "totkin"'])
                ->where([
                    'nip' => $model['nip'],
                    'EXTRACT(MONTH FROM tgl::DATE)' => $model['bulan'],
                    'EXTRACT(YEAR FROM tgl::DATE)' => $model['tahun'],
                ])
                ->andWhere(['<', 'tgl_ok', "$nt-$nb-04"])
                ->asArray()->one();

                $tp['kinerja'] = round($dtkin['totkin']/ 6750 * 100,2);
                if($tp['kinerja'] > 100) $tp['kinerja'] = 100;

                $tp->save();


                $this->getHit($model['id']);

                $model = $this->findModel($model['id']);

                $model['bpjs1'] = round(($model['tpp_total'] * 0.01),0);
                $model['bpjs4'] = round(($model['tpp_total'] * 0.04),0);
                
                $model['status'] = 0;
                $model['tpp_bruto'] = $model['tpp_total'] + $model['bpjs4'];

                $model['pot_jml'] = $model['pph_rp'] + $model['bpjs4'] + $model['bpjs1'];
                $model['tpp_net'] = $model['tpp_bruto'] - $model['pot_jml'];

                $model->save(false);

                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                    'title' => 'Berhasil',
                    'text' => 'Data baru berhasil disimpan ...!',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]);
            }

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
     * Updates an existing PreskinTppHitung model.
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
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data berhasil diubah ...!',
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
     * Deletes an existing PreskinTppHitung model.
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
            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
            'title' => 'Berhasil',
            'text' => 'Data berhasil dihapus ...!',
            'showConfirmButton' => false,
            'timer' => 1000
        ]);
        return $this->redirect(Url::previous());
    }

    /**
     * Finds the PreskinTppHitung model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PreskinTppHitung the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PreskinTppHitung::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCetakForm()
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

        return $this->render('form-cetak', [
            'arta' => $arta, 'arbul' => $arbul, 'opdlist' => $opdlist,
        ]);
    }

    public function actionCetak()
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
            ->orderBy(['gol' => SORT_DESC, 'kelas_jab_tpp' => SORT_DESC, 'tablokb' => SORT_ASC, 'nama_jab' => SORT_ASC, 'nip' => SORT_ASC,])
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
                // $tppkelas = \app\modules\tpp\models\TppPaguKelas::findOne($det['kode_PreskinGroupCetakb']);
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
        
        return $this->renderPartial('tpp-ref-cetak2023', [
            'data' => $data, 'group' => $group,
        ]);
    }

    public function actionCetakFormPdf()
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
            13 => 'Ketiga belas/ 13',
            14 => 'Keempat belas/ THR',
        ];

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
        
        //return Json::encode($opdlist);

        return $this->render('form-cetak-pdf', [
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
        
        
        $content =  $this->renderPartial('tpp-pdf-cetak2023', [
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

    public function actionPenerimaTpp()
    {
        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->all();
        
        $searchModel = new PreskinTppHitungSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        
        $dataProvider->query->andFilterWhere(['<', 'status', 99]);

        $dataProvider->query->orderBy([
            'status' => SORT_ASC,
            'gol' => SORT_DESC, 
            'tablok' => SORT_ASC,
            'tablokb' => SORT_ASC,
            'jenis_jab' => SORT_ASC,  
        ]);

        if(strtotime(date('Y-m-d H:i:s')) < strtotime(date('Y-m-04 00:00:00'))) $bln = date('n')-1;
        else $bln = date('n');
        
        $thn = date('Y');
        if($bln == 0){
            $bln = 12;
            $thn = $thn - 1;
        }

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
        $dataProvider->query->andFilterWhere(['bulan' => $bln, 'tahun' => $thn]);
        
        $pd = EpsTablokb::findOne($searchModel['tablok']);
        if($pd !== null) $namapd = $pd['NALOK']; else $namapd = ''; 

        Url::remember();
        return $this->render('index-tpp-penerima', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd, 'thn' => $thn, 'bln' => $bln,
            'bul' => strtoupper(Fungsi::NmBulan(intval($bln))),
            'namapd' => $namapd,
        ]);
    }

    public function actionFinal($id)
    {
        $model = $this->findModel($id);
        $model['status'] = 90;
        $model['tgl_cetak'] = date('Y-m-d H:i:s');
        if($model->save(false)){
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Status TPP '.$model['nip'].' '.$model['nama'].' telah Final',
                'showConfirmButton' => false,
                'timer' => 3000
            ]);
        };

        return $this->redirect(Url::previous());
    }

    public function actionHitung($id)
    {
        $tp = $this->findModel($id);
        if($tp !== null){
            $nip = $tp['nip'];
            $bulan = $tp['bulan'];
            $tahun = $tp['tahun'];
            $pagu_tpp = $tp['pagu_tpp'];
            $beban_kerja = round($pagu_tpp*0.7,0);  
            $produktivitas_kerja = $pagu_tpp - $beban_kerja; 
            $pagu_presensi = round($produktivitas_kerja * 0.4,0);
            $pagu_kinerja = $produktivitas_kerja - $pagu_presensi; 

            $pagu_tpp = $tp['pagu_tpp'];
            $beban_kerja = round($pagu_tpp*0.7,0);  
            $produktivitas_kerja = $pagu_tpp - $beban_kerja; 

            $tp['beban_kerja'] = $beban_kerja;  
            $tp['produktivitas_kerja'] = $produktivitas_kerja; 

            $dtpres = PresHarian::find()
            ->select(['SUM("pot_masuk") as "mas"', 'SUM("pot_pulang") as "pul"'])
            ->where([
                'nip' => $nip,
                'EXTRACT(MONTH FROM tgl::DATE)' => $bulan,
                'EXTRACT(YEAR FROM tgl::DATE)' => $tahun,
            ])->asArray()->one();

            $jml = PresHarian::find()
            ->where([
                'nip' => $nip,
                'EXTRACT(MONTH FROM tgl::DATE)' => $bulan,
                'EXTRACT(YEAR FROM tgl::DATE)' => $tahun,
            ])->count();
            $batas = $jml * 5;

            if($dtpres === null) $hpres = 0;
            else{
                $sumpres = $dtpres['mas'] + $dtpres['pul'];
                if($sumpres == $batas){
                    $hpres = 0;
                    $tp['beban_kerja'] = 0;
                }
                else $hpres = 100 - $sumpres;
            }

            if($hpres < 0){
                $hpres = 0;
                $tp['beban_kerja'] = 0;
            }
            
            $tp['presensi'] = $hpres;
            $tp['presensi_rp'] = round($tp['presensi']/100 * $pagu_presensi,0);

            if($tp['persen_tpp'] != 15){
                $jmlcuti = PresHarian::find()
                ->where([
                    'nip' => $nip,
                    'EXTRACT(MONTH FROM tgl::DATE)' => $bulan,
                    'EXTRACT(YEAR FROM tgl::DATE)' => $tahun,
                ])
                ->andWhere(['ilike', 'kd_pr_masuk', 'C'])->count();

                if($jmlcuti >= 13) $tp['cuti'] = 10;
                elseif($jmlcuti >= 18) $tp['cuti'] = 20;
                elseif($jmlcuti > 21) $tp['cuti'] = 30;

            }else{
                // nakes
                $tp['presensi'] = 100;
                $tp['presensi_rp'] = round($tp['presensi']/100 * $pagu_presensi,0);
                $tp['beban_kerja'] = $beban_kerja;
                // April 2024
                //$jml = 16;
                $jmlcuti = 0;
            }
            
            if($jml == $jmlcuti && $jmlcuti > 0) $tp['beban_kerja'] = 0;

            if($tp['cuti'] > 0){
                $tp['presensi'] = $tp['presensi'] - $tp['cuti'];
                $tp['cuti_rp'] = round($pagu_presensi * $tp['cuti']/100,0);
                $tp['presensi_rp'] = $pagu_presensi - $tp['cuti_rp'];
                
                if($tp['cuti'] >= 30) {$tp['beban_kerja'] = 0;}
            }
            //$tp['beban_kerja'] = 0;

            $tp['sakip'] = 100; //lihat di tabel tpp_sakip_rb
            $tp['sakip_rp'] = round($tp['sakip']/100 * $pagu_kinerja * 0.1,0);

            //kinerja
            //$tp['kinerja'] = 100; //kinerja 100 %

            $nb = $bulan + 1;
            if($nb == 13) $nt = $tahun + 1; else $nt = $tahun;
            $dtkin = KinHarian::find()
            ->select(['SUM("ok_waktu_h") as "totkin"'])
            ->where([
                'nip' => $nip,
                'EXTRACT(MONTH FROM tgl::DATE)' => $bulan,
                'EXTRACT(YEAR FROM tgl::DATE)' => $tahun,
            ])
            ->andWhere(['<', 'tgl_ok', "$nt-$nb-04"])
            ->asArray()->one();

            $tp['kinerja'] = round($dtkin['totkin']/ 6750 * 100,2);

            if($tp['persen_tpp'] == 15) $tp['kinerja'] = 100; //nakes
            if($tp['cuti'] >= 30) {$tp['kinerja'] = 0;} // cuti sebulan kinerja = 0

            if($tp['kinerja'] > 100) $tp['kinerja'] = 100;

            $tp['kinerja_rp'] = round(($pagu_kinerja - $tp['sakip_rp']) * $tp['kinerja']/100,0);

            $tp['tpp_jumlah'] = $tp['beban_kerja'] + 
                $tp['presensi_rp'] + 
                $tp['kinerja_rp'] + 
                $tp['sakip_rp'];

            $tp['hukdis_rp'] = round($tp['hukdis']/100 * $tp['tpp_jumlah'],0);
            $tp['tgr_rp'] = round($tp['tgr']/100 * $tp['tpp_jumlah'],0);

            $tp['pengurangan'] = $tp['hukdis_rp'] + $tp['tgr_rp'];
            $tp['tpp_total'] = $tp['tpp_jumlah'] - $tp['pengurangan'];

            //start pph
            if($tp['gol'] > 40) $tp['pph'] = 15;
            elseif($tp['gol'] > 30) $tp['pph'] = 5;
            else $tp['pph'] = 0;

            $tp['pph_rp'] = round($tp['pph']/100 * $tp['tpp_total'],0);

            //start of cek gaji untuk pot bpjs

            $fgaji = DbgajidoFgaji::find()
            ->where([
                'TGLGAJI' => "$tahun-$bulan-01",
                //'KDJNSTRANS' => 1,
                'NIP' => $nip,
            ])
            ->andWhere(['<', 'KDSTAPEG', 13])
            ->andWhere(['<', 'KDJNSTRANS', 3]);

            if($fgaji->count() != 0){
                $gaji = $fgaji->one();
                $hasil = $gaji['GAPOK'] + $gaji['TJISTRI'] + $gaji['TJANAK'] + $gaji['TJESELON'] + $gaji['TJFUNGSI']+ $gaji['TJKHUSUS'];
                
                $hasilasli=$hasil+$tp['tpp_total'];		

                $bp1 = 0;
                $bp4 = 0;
                    
                if($tp['jtrans'] == 4){
                    $tpid = $tp['tahun'].'-'.$tp['bulan'].'-1-'.$tp['nip'];
                    $cektp = PreskinTppHitung::findOne($tpid);
                    if($cektp !== null){
                        $bp1 = $cektp['bpjs1'];
                        $bp4 = $cektp['bpjs4'];
                    }
                }

                if($hasilasli >= 12000000){

                    $hasilbpjs4 = 480000 - $gaji['TJASKES'];
                    $hasilbpjs1 = 120000 - $gaji['PIWP2'];

                    $tp['bpjs1'] = round(($hasilbpjs1),0) - $bp1;
                    $tp['bpjs4'] = round(($hasilbpjs4),0) - $bp4;
                }else{
                    $tp['bpjs1'] = round(($tp['tpp_total'] * 0.01),0) - $bp1;
                    $tp['bpjs4'] = round(($tp['tpp_total'] * 0.04),0) - $bp4;        
                }
            }else $tp['status'] = 99;

            if($tp['bpjs1'] < 0) $tp['bpjs1'] = 0;
            if($tp['bpjs4'] < 0) $tp['bpjs4'] = 0;

            $tp['tpp_bruto'] = $tp['tpp_total'] + $tp['bpjs4'];

            //end of cek gaji untuk pot bpjs
            $tp['pot_jml'] = $tp['pph_rp'] + $tp['bpjs4'] + $tp['bpjs1'];
            $tp['tpp_net'] = $tp['tpp_bruto'] - $tp['pot_jml'];

            $tp['updated'] = date('Y-m-d H:i:s');

            $tp->save(false);

            if($tp['status'] != 99){
                $preas = PreskinAsn::findOne($nip);
                $preas['tpp'] = $bulan;
                $preas->save(false);
            }

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'TPP telah dihitung ulang',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);

        }
        return $this->redirect(Url::previous());        
    }

    protected function getPaguTpp($pagu)
    {
        $pg = PreskinPaguTpp::findOne($pagu);
        if($pg === null) $ptpp = 0;
        else{
            $npagu = $pg['pagu'];
            $nmkel = $pg['kelas'];
            $ptpp = [
                'pagu' => $npagu,
                'kelas' => $nmkel,
            ];
        }

        return $ptpp;
    }

    protected function updateGol($x)
    {
        // $fip = EpsMastfip::find()->where(['B_02' => $x])
        // ->select(['B_02', 'E_04', 'E_05'])
        // ->one();

        $fip = SiasnDataUtama::find()->where(['nipBaru' => $x])
        ->select(['nipBaru', 'golRuangAkhirId', 'tmtGolAkhir'])
        ->one();
        
        if($fip === null){
            return $gol = 0;
        }else{
            // $tmt = $fip['E_05'];
            // $kdgol = $fip['E_04'];

            $tmt = $fip['golRuangAkhirId'];
            $kdgol = $fip['tmtGolAkhir'];

            $tgol = SiasnRefGol::findOne($kdgol);
            if($tgol !== null){
                $nmgol = $tgol['NAMA_PANGKAT']."-".$tgol['NAMA'];
            }else $nmgol = '';
    
            $gol = [
                'tmtgol' => $tmt,
                'kdgol' => $kdgol,
                'nmgol' => $nmgol,
            ];    
        }
        return $gol;
    }

    protected function updateRefGol($x)
    {
        $tgol = SiasnRefGol::findOne($x);
        if($tgol !== null){
            $nmgol = $tgol['NAMA_PANGKAT']."-".$tgol['NAMA'];
        }else $nmgol = '';

        $gol = [
            'nmgol' => $nmgol,
        ];    
        return $gol;
    }

    protected function getHit($x)
    {
        $tp = $this->findModel($x);
        $nip = $tp['nip'];
        $bulan = $tp['bulan'];
        $tahun = $tp['tahun'];
        $pagu_tpp = $tp['pagu_tpp'];
        $beban_kerja = round($pagu_tpp*0.7,0);  
        $produktivitas_kerja = $pagu_tpp - $beban_kerja; 
        $pagu_presensi = round($produktivitas_kerja * 0.4,0);
        $pagu_kinerja = $produktivitas_kerja - $pagu_presensi; 

        $pagu_tpp = $tp['pagu_tpp'];
        $beban_kerja = round($pagu_tpp*0.7,0);  
        $produktivitas_kerja = $pagu_tpp - $beban_kerja; 

        $tp['beban_kerja'] = $beban_kerja;
        $tp['produktivitas_kerja'] = $produktivitas_kerja;

        $tp['presensi_rp'] = round($tp['presensi']/100 * $pagu_presensi,0);

        $tp['sakip'] = 100; //lihat di tabel tpp_sakip_rb
        $tp['sakip_rp'] = round($tp['sakip']/100 * $pagu_kinerja * 0.1,0);

        $tp['kinerja_rp'] = round(($pagu_kinerja - $tp['sakip_rp']) * $tp['kinerja']/100,0);

        if($tp['kinerja'] == 0) $tp['beban_kerja'] = 0;

        $tp['tpp_jumlah'] = $tp['beban_kerja'] + 
            $tp['presensi_rp'] + 
            $tp['kinerja_rp'] + 
            $tp['sakip_rp'];

        $tp['hukdis_rp'] = round($tp['hukdis']/100 * $tp['tpp_jumlah'],0);
        $tp['tgr_rp'] = round($tp['tgr']/100 * $tp['tpp_jumlah'],0);

        $tp['pengurangan'] = $tp['hukdis_rp'] + $tp['tgr_rp'];
        $tp['tpp_total'] = $tp['tpp_jumlah'] - $tp['pengurangan'];

        //start pph
        if($tp['gol'] > 40) $tp['pph'] = 15;
        elseif($tp['gol'] > 30) $tp['pph'] = 5;
        else $tp['pph'] = 0;

        $tp['pph_rp'] = round($tp['pph']/100 * $tp['tpp_total'],0);

        //start of cek gaji untuk pot bpjs

        $fgaji = DbgajidoFgaji::find()
        ->where([
            'TGLGAJI' => "$tahun-$bulan-01",
            //'KDJNSTRANS' => 1,
            'NIP' => $nip,
        ])
        ->andWhere(['<', 'KDSTAPEG', 13])
        ->andWhere(['<', 'KDJNSTRANS', 3]);

        if($fgaji->count() != 0){
            $gaji = $fgaji->one();
            $hasil = $gaji['GAPOK'] + $gaji['TJISTRI'] + $gaji['TJANAK'] + $gaji['TJESELON'] + $gaji['TJFUNGSI']+ $gaji['TJKHUSUS'];
            
            $hasilasli=$hasil+$tp['tpp_total'];		
            if($hasilasli >= 12000000){

                $hasilbpjs4 = 480000 - $gaji['TJASKES'];
                $hasilbpjs1 = 120000 - $gaji['PIWP2'];

                $tp['bpjs1'] = round(($hasilbpjs1),0);
                $tp['bpjs4'] = round(($hasilbpjs4),0);
            }else{
                $tp['bpjs1'] = round(($tp['tpp_total'] * 0.01),0);
                $tp['bpjs4'] = round(($tp['tpp_total'] * 0.04),0);
                
            }
        }else $tp['status'] = 99;
        $tp['tpp_bruto'] = $tp['tpp_total'] + $tp['bpjs4'];

        //end of cek gaji untuk pot bpjs
        $tp['pot_jml'] = $tp['pph_rp'] + $tp['bpjs4'] + $tp['bpjs1'];
        $tp['tpp_net'] = $tp['tpp_bruto'] - $tp['pot_jml'];

        $tp['updated'] = date('Y-m-d H:i:s');

        $tp->save(false);

        if($tp['status'] != 99){
            $preas = PreskinAsn::findOne($nip);
            $preas['tpp'] = $bulan;
            $preas->save(false);
        }
    }
    
    public function actionJabatan($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $jab = $this->cekTambahJab($model['id']);

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data Jabatan berhasil diubah ...!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update-tpp', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('update-tpp', [
                'model' => $model, 
            ]);
        } 
    }

    public function actionUnor($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $nalok = $this->getTablok($model['tablok']);
            $model['nama_opd'] = $nalok;

            $nalok = $this->getTablok($model['tablokb']);
            $model['nama_unor'] = $nalok;
            
            $model->save();
            
            $dt = $this->cekTambahOpd($model['id']);
            $this->getHit($model['id']);

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data Unor berhasil diubah ...!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update-tpp', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('update-tpp', [
                'model' => $model, 
            ]);
        } 
    }

    public function actionKelas($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->save();
            
            $pagutpp = $this->getPaguTpp($model['kelas_jab_tpp']);
            if($pagutpp != 0){
                $model['pagu'] = $pagutpp['pagu'];
                $model['nama_kelas'] = $pagutpp['kelas'];
                $model->save(false);
            }
            $this->cekTambahOpd($model['id']);
            $this->getHit($model['id']);

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data Kelas berhasil diubah ...!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update-tpp', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('update-tpp', [
                'model' => $model, 
            ]);
        } 
    }

    public function actionGolongan($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $refgol = SiasnRefGol::findOne($model['gol']);
            if($refgol !== null) $model['nama_gol'] = $refgol['NAMA_PANGKAT'].'-'.$refgol['NAMA'];
            $model->save();
            $this->getHit($model['id']);

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data Golongan berhasil diubah ...!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update-tpp', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('update-tpp', [
                'model' => $model, 
            ]);
        } 
    }

    public function actionCuti($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // $cuti = $model['jml_cuti'];
            // $hr_kerja = $model['jml_hrkerja'];

            // if($cuti <= 12) $pot_cuti = 0;
            // elseif($cuti <= 17 ) $pot_cuti = 10;
            // elseif($cuti <= 21 ) $pot_cuti = 30;
            // else $pot_cuti = 30;

            // $model['cuti'] = $pot_cuti;
            // $model['cuti_rp'] = $pot_cuti/100 * $model[''];

            $model->save();
            $this->getHit($model['id']);

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data Cuti berhasil diubah ...!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update-tpp', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('update-tpp', [
                'model' => $model, 
            ]);
        } 
    }

    public function actionHukdis($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->save();
            $this->getHit($model['id']);
            
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data Hukuman Disiplin berhasil diubah ...!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update-tpp', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('update-tpp', [
                'model' => $model, 
            ]);
        } 
    }

    public function actionPersenTpp($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->save();
            $this->cekTambahOpd($model['id']);
            $this->getHit($model['id']);

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data Presentasi TPP berhasil diubah ...!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update-tpp', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('update-tpp', [
                'model' => $model, 
            ]);
        } 
    }
    protected function getTablok($kd)
    {
        $tab = EpsTablokb::find()->where(['KOLOK' => $kd])->one();
        if($tab === null) return 0;
        else return $tab['NALOK'];
    }

    protected function getGroupCetak($kd)
    {
        $tab = EpsTablokb::find()->where(['KOLOK' => $kd])->one();
        if($tab === null) return 0;
        else return $tab['GROUP_CETAK'];
    }

    protected function cekTambahOpd($id)
    {
        //$opd_tambah = PreskinTambahTppOpd::find()->where(['kolok' => $kolok])->one();
        $tp = PreskinTppHitung::findOne($id);
        //$tab = $this->getTablok($tp['tablokb']);
        //$tp['nama_unor'] = $tab['']
        $opd_tambah = PreskinTambahTppOpd::find()->where(['kolok' => $tp['tablokb']])->one();
        if($opd_tambah !== null){
            $tp['beban_opd'] = $opd_tambah['beban'];
            $tp['kondisi_opd'] = $opd_tambah['kondisi'];
            $tp['pol_opd'] = $opd_tambah['pol'];
            $tp['prestasi_opd'] = $opd_tambah['prestasi'];
            $tp['tempat_opd'] = $opd_tambah['tempat'];

            $tp['beban_opd_rp'] = round($tp['beban_opd']/100*$tp['pagu'],0);
            $tp['kondisi_opd_rp'] = round($tp['kondisi_opd']/100*$tp['pagu'],0);
            $tp['pol_opd_rp'] = round($tp['pol_opd']/100*$tp['pagu'],0);
            $tp['prestasi_opd_rp'] = round($tp['prestasi_opd']/100*$tp['pagu'],0);
            $tp['tempat_opd_rp'] = round($tp['tempat_opd']/100*$tp['pagu'],0);
        }else{
            $tp['beban_opd'] = 0;
            $tp['kondisi_opd'] = 0;
            $tp['pol_opd'] = 0;
            $tp['prestasi_opd'] = 0;
            $tp['tempat_opd'] = 0;

            $tp['beban_opd_rp'] = 0;
            $tp['kondisi_opd_rp'] = 0;
            $tp['pol_opd_rp'] = 0;
            $tp['prestasi_opd_rp'] = 0;
            $tp['tempat_opd_rp'] = 0;
        }

        $tambah_total = $tp['beban_jab_rp']+
            $tp['kondisi_jab_rp']+
            $tp['pol_jab_rp']+
            $tp['prestasi_jab_rp']+
            $tp['tempat_jab_rp']+
            $tp['profesi_jab_rp']+
            $tp['beban_opd_rp']+
            $tp['kondisi_opd_rp']+
            $tp['pol_opd_rp']+
            $tp['prestasi_opd_rp']+
            $tp['tempat_opd_rp'];

        $tp['pagu_total'] = $tp['pagu'] + $tambah_total;
        $tp['pagu_tpp'] = round($tp['pagu_total'] * $tp['persen_tpp']/100,0);

        $tp->save(false);
        return $tp;
    }

    //protected function cekTambahJab($kojab, $jjab)
    protected function cekTambahJab($id)
    {
        //$jab_tambah = PreskinTambahTppJab::find()->where(['kode_jab' => $kojab, 'jenis_jab' => $jjab])->one();
        $tp = PreskinTppHitung::findOne($id);
        $jab_tambah = PreskinTambahTppJab::find()
            ->where(['kode_jab' => $tp['kode_jab'], 'jenis_jab' => $tp['jenis_jab']])->one();

        if($jab_tambah !== null){
            $tp['beban_jab'] = $jab_tambah['beban'];
            $tp['kondisi_jab'] = $jab_tambah['kondisi'];
            $tp['pol_jab'] = $jab_tambah['pol'];
            $tp['prestasi_jab'] = $jab_tambah['prestasi'];
            $tp['tempat_jab'] = $jab_tambah['tempat'];
            $tp['profesi_jab'] = $jab_tambah['langka']; //kelangkaan

            $tp['beban_jab_rp'] = round($tp['beban_jab']/100*$tp['pagu'],0);
            $tp['kondisi_jab_rp'] = round($tp['kondisi_jab']/100*$tp['pagu'],0);
            $tp['pol_jab_rp'] = round($tp['pol_jab']/100*$tp['pagu'],0);
            $tp['prestasi_jab_rp'] = round($tp['prestasi_jab']/100*$tp['pagu'],0);
            $tp['tempat_jab_rp'] = round($tp['tempat_jab']/100*$tp['pagu'],0);
            $tp['profesi_jab_rp'] = round($tp['profesi_jab']/100*$tp['pagu'],0);
        }else{
            $tp['beban_jab'] = 0;
            $tp['kondisi_jab'] = 0;
            $tp['pol_jab'] = 0;
            $tp['prestasi_jab'] = 0;
            $tp['tempat_jab'] = 0;
            $tp['profesi_jab'] = 0; 

            $tp['beban_jab_rp'] = 0;
            $tp['kondisi_jab_rp'] = 0;
            $tp['pol_jab_rp'] = 0;
            $tp['prestasi_jab_rp'] = 0;
            $tp['tempat_jab_rp'] = 0;
            $tp['profesi_jab_rp'] = 0;
        }
        $tambah_total = $tp['beban_jab_rp']+
            $tp['kondisi_jab_rp']+
            $tp['pol_jab_rp']+
            $tp['prestasi_jab_rp']+
            $tp['tempat_jab_rp']+
            $tp['profesi_jab_rp']+
            $tp['beban_opd_rp']+
            $tp['kondisi_opd_rp']+
            $tp['pol_opd_rp']+
            $tp['prestasi_opd_rp']+
            $tp['tempat_opd_rp'];

        $tp['pagu_total'] = $tp['pagu'] + $tambah_total;
        $tp['pagu_tpp'] = round($tp['pagu_total'] * $tp['persen_tpp']/100,0);
        $tp->save(false);
        return $tp;
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
