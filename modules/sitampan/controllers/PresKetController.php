<?php

namespace app\modules\sitampan\controllers;

use Yii;
use app\modules\sitampan\models\PresKet;
use app\modules\sitampan\models\PresKetSearch;
use app\modules\sitampan\models\PresKetJenis;
use app\modules\simpeg\models\EpsTablokb;
use app\modules\simpeg\models\EpsMastfip;

use app\modules\sitampan\models\VSppd;

use app\models\Fungsi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;

/**
 * PresKetController implements the CRUD actions for PresKet model.
 */
class PresKetController extends Controller
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
     * Lists all PresKet models.
     * @return mixed
     */
    public function actionIndex()
    {        
        $jenis = PresKetJenis::find()->all();
        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->orderBy(['KOLOK' => SORT_ASC])
        ->all();

        $bln = date('n');
        $thn = date('Y');

        $req = Yii::$app->request;
        if($req->get()){
            if($req->get('periode') !== null){
                $per = explode('-',$req->get('periode'));
                $bln = intval($per[0]);
                $thn = $per[1];
            }
        }
        
        $searchModel = new PresKetSearch();

        $searchModel['tahun'] = $thn; 
        $searchModel['bulan'] = $bln;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if($searchModel['opd'] == '')  $searchModel['opd'] = '31000000';

        $tablok = EpsTablokb::find()
        ->select(['KOLOK'])
        ->where(['GROUP_USER' => $searchModel['opd']]);

        if($tablok->count() > 0){
            $lok = [];
            foreach($tablok->all() as $tlok){
                $lok[] = $tlok['KOLOK'];
            }
        } else $lok = $searchModel['opd'];
        
        $dataProvider->query->andFilterWhere(['OR', ['opd' => $lok]]);

        $opdname = Epstablokb::findOne($searchModel['opd'])['NALOK'];

    //     if($searchModel['opd'] == '31000000')
    //      return $dataProvider->query->createCommand()->getRawSql();
    //      return $searchModel['opd'];

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenis' => $jenis,
            'opd' => $opd,
            'opdname' =>$opdname,
            'periode' => strtoupper(Fungsi::NmBulan($bln))." $thn",
        ]);
    }

    /**
     * Displays a single PresKet model.
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
     * Creates a new PresKet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idu)
    {
        $sess = Yii::$app->session;
        $jenis = PresKetJenis::find()->all();
        $asn = EpsMastfip::find()
            ->select(['B_02', 'B_03', 'A_01', 'A_02', 'A_03', 'A_04', 'B_08', 
                'CONCAT("B_02",\' \',"B_03") AS nipnama'])
            ->leftJoin('eps_tablokb', 'eps_tablokb."KOLOK" = CONCAT("A_01","A_02","A_03","A_04")')
            ->where(['<', 'B_08', 3])
            ->andWhere(['OR', ['eps_tablokb.GROUP_USER' => $idu],['eps_tablokb.KOLOK' => $idu]])
            ->orderBy([
                'CONCAT("A_01","A_02","A_03","A_04")' => SORT_ASC,
                'B_03' => SORT_ASC,
                ])
            ->asArray()
            ;  
        
        $model = new PresKet([
            'id' => $idu.'-'.$sess['nip'].'-'.time(),
            'opd' => $idu,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if($model['nip'] != ''){
                $nip = '';
                foreach ($model['nip'] as $data) {
                    $nip = $nip.$data.';';
                }
                $model['nip'] = $nip;
            }
            $model['updated'] = date('Y-m-d H:i:s');
            if($model->save()){
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Berhasil',
                    'text' => 'Data berhasil disimpan',
                    'showConfirmButton' => false,
                    'timer' => 2000
                ]); 
            }
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model, 'jenis' => $jenis, 'asn' => $asn,
            ]);
        }else{
            return $this->render('create', [
                'model' => $model, 'jenis' => $jenis, 'asn' => $asn,
            ]);
        } 
    }

    /**
     * Updates an existing PresKet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {        
        $sess = Yii::$app->session;
        $model = $this->findModel($id);
        $jenis = PresKetJenis::find()->all();
        $asn = EpsMastfip::find()
            ->select(['B_02', 'B_03', 'A_01', 'A_02', 'A_03', 'A_04', 'B_08', 
                'CONCAT("B_02",\' \',"B_03") AS nipnama'])
            ->leftJoin('eps_tablokb', 'eps_tablokb."KOLOK" = CONCAT("A_01","A_02","A_03","A_04")')
            ->where(['<', 'B_08', 3])
            ->andWhere(['OR', ['eps_tablokb.GROUP_USER' => $model['opd']],['eps_tablokb.KOLOK' => $model['opd']]])
            ->orderBy([
                'CONCAT("A_01","A_02","A_03","A_04")' => SORT_ASC,
                'B_03' => SORT_ASC,
                ])
            ->asArray()
        ;

        $pegawai = explode(";",$model['nip']);
        $nip = [];
        foreach($pegawai as $peg){
            if($peg != ''){
                $nip[]= $peg;
            }
        }
        $model['nip'] = $nip;

        if ($model->load(Yii::$app->request->post())) {
            if($model['nip'] != ''){
                $nip = '';
                foreach ($model['nip'] as $data) {
                    $nip = $nip.$data.';';
                }
                $model['nip'] = $nip;
            }
            $model['updated'] = date('Y-m-d H:i:s');
            if($model->save()){
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                    'title' => 'Berhasil',
                    'text' => 'Data berhasil diubah',
                    'showConfirmButton' => false,
                    'timer' => 2000
                ]); 
            }
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model, 'jenis' => $jenis, 'asn' => $asn,
            ]);
        }else{
            return $this->render('update', [
                'model' => $model, 'jenis' => $jenis, 'asn' => $asn,
            ]);
        } 
    }

    /**
     * Deletes an existing PresKet model.
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
            'title' => 'Berhasil',
            'text' => 'Data berhasil dihapus',
            'showConfirmButton' => false,
            'timer' => 2000
        ]); 
        return $this->redirect(Url::previous());
    }

    /**
     * Finds the PresKet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PresKet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PresKet::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSppdSync($bulan, $tahun)
    {
        $opd = '31';
        
        $sppd = VSppd::find()->where([
            'MONTH(tgl_berangkat)' => $bulan,
            'YEAR(tgl_berangkat)' => $tahun,
        ])->groupBy(['no_surat', 'tgl_surat']);

        if($sppd->count() != 0){
            $data = [];
            $x = time();
            $no = 0;

            foreach($sppd->all() as $sp){
                $x = $x+1; 
                $no = $no + 1;

                $jenis_ket = 1;
                $no_surat = $sp['no_surat'];
                $tgl_surat = $sp['tgl_surat'];
                $tgl_awal = $sp['tgl_berangkat'];
                $tgl_akhir = $sp['tgl_kembali'];
                $detail = $sp['perihal'];
                
                if($tgl_surat != '0000-00-00'){

                    $asn_opd = EpsMastfip::find()->select(['B_02'])->where([
                        'A_01' => $opd,
                        'B_02' => $sp['nip9'], 
                    ]);
                    
                    if($asn_opd->count() > 0) $nip = $sp['nip9'].';';
                    else $nip = '';

                    $sppd2 = VSppd::find()
                    ->select(['nip'])
                    ->where([
                        'no_surat' => $no_surat,
                        'tgl_surat' => $tgl_surat,
                    ])->orderBy(['nip' => SORT_ASC])->all();

                    foreach($sppd2 as $sp2){
                        $asn_opd2 = EpsMastfip::find()->select(['B_02'])->where([
                            'A_01' => $opd,
                            'B_02' => $sp2['nip'], 
                        ]);
    
                        if($asn_opd2->count() > 0)  $nip = $nip.$sp2['nip'].';';                        
                    }
                    
                    if($nip != ''){
                        $mod = Presket::find()->where([
                            'opd' => $opd.'000000',
                            'jenis_ket' => $jenis_ket,
                            'no_surat' => $no_surat,
                            'tgl_surat' => $tgl_surat,
                        ]);

                        if($mod->count() == 0){
                            $mod = new PresKet();
                            $mod['id'] = $opd.'000000-'.$sp['nip9'].'-'.$x;
                        }else{
                            $mod = $mod->one();
                        }

                        $mod['opd'] = $opd.'000000';
                        $mod['jenis_ket'] = $jenis_ket;
                        $mod['no_surat'] = $no_surat;
                        $mod['tgl_surat'] = $tgl_surat;
                        $mod['tgl_awal'] = $tgl_awal;
                        $mod['tgl_akhir'] = $tgl_akhir;
                        $mod['detail'] = $detail;
                        $mod['nip'] = $nip;
                        $mod->save();
                        
                        Yii::$app->session->setFlash('position', [
                            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                            'title' => 'Berhasil',
                            'text' => 'Data SPPD berhasil disinkronkan',
                            'showConfirmButton' => false,
                            'timer' => 2000
                        ]); 

                    }
                }
            }
        }else{
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, //SUCCESS, INFO, ERROR, WARNING, QUESTION
                'title' => 'Gagal',
                'text' => 'Data SPPD tidak ditemukan !!',
                'showConfirmButton' => false,
                'timer' => 2000
            ]); 
        }

        return $this->redirect(Url::previous());
    }
}
