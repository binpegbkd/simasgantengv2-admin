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

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;

use app\modules\integrasi\controllers\SiasnGetData;

/**
 * SiasnDataUtamaController implements the CRUD actions for SiasnDataUtama model.
 */
class SupervisiController extends Controller
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
                            if(Yii::$app->session['module'] != 11)  return $this->redirect(['/']);
                            else return Yii::$app->session['module'] == 11;
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
     * Lists all SiasnDataUtama models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SiasnDataUtamaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy([
            'flag' => SORT_ASC,
            'kedudukanPnsId' => SORT_ASC,
            'golRuangAkhirId' => SORT_DESC,
        ]);

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexOpd()
    {
        $searchModel = new SiasnDataUtamaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy([
            'flag' => SORT_ASC,
            'kedudukanPnsId' => SORT_ASC,
            'golRuangAkhirId' => SORT_DESC,
        ]);

        Url::remember();
        return $this->render('index-opd', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCari()
    {
        $searchModel = new SiasnDataUtamaSearch();  

        return $this->render('_search', [
            'model' => $searchModel,
        ]);
    }

    public function actionCariOpd()
    {
        $pd = EpsTablokb::find()
            ->select(['KOLOK', 'NALOK', "CONCAT(\"KOLOK\",' ',\"NALOK\") AS pd"])
            ->where(["RIGHT(\"KOLOK\", 4)" => '0000'])
            ->asArray()
            ->orderBy(['NALOK' => SORT_ASC])
            ->all();
    
        $searchModel = new SiasnDataUtamaSearch();  
        
        return $this->render('_search-opd', [
            'model' => $searchModel,
            'pd' => $pd,
        ]);
    }

    /**
     * Displays a single SiasnDataUtama model.
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

    public function actionViewDok($id)
    { 
        // return $id;
        if(Yii::$app->request->isAjax) 
            return $this->renderAjax('view-dok', ['model' => $id]);   
        else return $this->render('view-dok', ['model' => $id]);      
    }

    public function actionProfil($id)
    {
        $model = $this->findModelByNip($id);

        Url::remember();
        return $this->render('profil', [
            'model' => $model,
        ]);
    }

    public function actionDataLain($id)
    {
        $model = $this->findModelByNip($id);

        Url::remember();
        return $this->render('data-lain', [
            'model' => $model,
        ]);
    }

    public function actionCpnsPnsP3k($id)
    {
        $model = $this->findModelByNip($id);

        $path = 'efi/'.$model['id'].'/';
        
        $dok_cpns = $path.'SK_CPNS_'.$id.'.pdf';
        if(!file_exists($dok_cpns)) $dok_cpns = '';
        if(filesize($dok_cpns) == 0) $dok_cpns = '';

        $dok_pns = $path.'SK_PNS_'.$id.'.pdf';
        if(!file_exists($dok_pns)) $dok_pns = '';
        if(filesize($dok_pns) == 0) $dok_pns = '';

        $dok_spmt = $path.'SPMT_'.$id.'.pdf';
        if(!file_exists($dok_spmt)) $dok_spmt = '';
        if(filesize($dok_spmt) == 0) $dok_spmt = '';
        
        Url::remember();
        return $this->render('cpns-pns-p3k', [
            'model' => $model, 
            'dok_cpns' => $dok_cpns,
            'dok_pns' => $dok_pns,
            'dok_spmt' => $dok_spmt,
        ]);
    }

    public function actionPangkatGol($id)
    {
        $model = $this->findModelByNip($id);
        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwGolongan::find()->where(['nipBaru' => $id])->orderBy(['golongan' => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('pangkat-gol', [
            'model' => $model, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewGol($id)
    {
        $model = SiasnRwGolongan::findOne($id);

        if(Yii::$app->request->isAjax) 
            return $this->renderAjax('view-gol', ['model' => $model]);   
        else return $this->render('view-gol', ['model' => $model]);        
    }


    public function actionJabatan($id)
    {
        $model = $this->findModelByNip($id);
        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwJabatan::find()->where(['nipBaru' => $id])->orderBy(['TO_DATE("tmtJabatan",\'DD-MM-YYYY\')' => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('jabatan', [
            'model' => $model, 'dataProvider' => $dataProvider,
        ]);
    }


    public function actionViewJab($id)
    {
        $model = SiasnRwJabatan::findOne($id);

        if(Yii::$app->request->isAjax) 
            return $this->renderAjax('view-jab', ['model' => $model]);   
        else return $this->render('view-jab', ['model' => $model]);        
    }

    public function actionPendidikan($id)
    {
        $model = $this->findModelByNip($id);
        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwPendidikan::find()->where(['nipBaru' => $id])->orderBy(['tahunLulus' => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('pendidikan', [
            'model' => $model, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDiklat($id)
    {
        $model = $this->findModelByNip($id);
        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwDiklat::find()->where(['nipBaru' => $id])->orderBy(['tahun' => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('diklat', [
            'model' => $model, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKursus($id)
    {
        $model = $this->findModelByNip($id);
        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwKursus::find()->where(['nipBaru' => $id])->orderBy(['TO_DATE("tanggalKursus",\'DD-MM-YYYY\')' => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('kursus', [
            'model' => $model, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAk($id)
    {
        $model = $this->findModelByNip($id);
        $idpns = $model['id'];
        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwAngkakredit::find()->where(['pns' => $idpns])->orderBy(["tanggalSk" => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('ak', [
            'model' => $model, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKinerja($id)
    {
        $model = $this->findModelByNip($id);
        $idpns = $model['id'];
        
        Url::remember();
        return $this->render('kinerja', [
            'model' => $model,
        ]);
    }

    public function actionSkp($id)
    {
        $model = $this->findModelByNip($id);
        $idpns = $model['id'];

        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwSkp::find()->where(['pns' => $idpns])->orderBy(['tahun' => SORT_ASC])
        ]);

        $dataProvider2 = new ActiveDataProvider([
            'query' => SiasnRwSkp22::find()->where(['pnsDinilaiId' => $idpns])->orderBy(['tahun' => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('skp', [
            'model' => $model, 
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
        ]);
    }

    public function actionPenghargaan($id)
    {
        $model = $this->findModelByNip($id);
        $idpns = $model['id'];

        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwHarga::find()->where(['pnsOrangId' => $idpns])->orderBy(['TO_DATE("skDate",\'DD-MM-YYYY\')' => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('penghargaan', [
            'model' => $model, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMasaKerja($id)
    {
        $model = $this->findModelByNip($id);
        $idpns = $model['id'];
        
        Url::remember();
        return $this->render('masa-kerja', [
            'model' => $model,
        ]);
    }

    public function actionCltn($id)
    {
        $model = $this->findModelByNip($id);
        return $this->render('cltn', [
            'model' => $model,
        ]);
    }

    public function actionPasangan($id)
    {
        $model = $this->findModelByNip($id);
        
        Url::remember();
        return $this->render('pasangan', [
            'model' => $model,
        ]);
    }

    public function actionAnak($id)
    {
        $model = $this->findModelByNip($id);
        
        Url::remember();
        return $this->render('anak', [
            'model' => $model,
        ]);
    }

    public function actionHukdis($id)
    {
        $model = $this->findModelByNip($id);
        $idpns = $model['id'];

        $dataProvider = new ActiveDataProvider([
            'query' => SiasnRwHukdis::find()->where(['pnsOrang' => $idpns])->orderBy(['TO_DATE("skTanggal",\'DD-MM-YYYY\')' => SORT_ASC])
        ]);
        
        Url::remember();
        return $this->render('hukdis', [
            'model' => $model, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPemberhentian($id)
    {
        $model = $this->findModelByNip($id);
        
        Url::remember();
        return $this->render('pemberhentian', [
            'model' => $model,
        ]);
    }
    /**
     * Creates a new SiasnDataUtama model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SiasnDataUtama();

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
     * Updates an existing SiasnDataUtama model.
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
     * Finds the SiasnDataUtama model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SiasnDataUtama the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiasnDataUtama::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelByNip($id)
    {
        if (($model = SiasnDataUtama::find()->where(['nipBaru' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getData($fil, $val){
        $query = SiasnDataUtama::find()
        ->select(['nipBaru', 'flag', $fil])
        ->where(['<', 'flag', 99])
        ->andWhere([$fil => $val])
        ->count()
        ;

        return $query;
    }

    public function actionSinkron($zid)
    {
        $simas = SiasnDataUtama::find()->count();

        $tgl = date('Y-m-d H:i:s', $zid/1000);

        return $simas.' '.$tgl;
    }

    public function actionGetDataSiasn($id, $nip)
    {       
        $sess = Yii::$app->session;
        $mode = 'prod';
        $ws = SiasnConfig::getTokenWSO2($mode);
        $sso = SiasnConfig::getTokenSSO($sess['nip'], $sess['password'], $mode);

        $model = $this->findModelByNip($nip);

        $siasn = ['code' => 0, 'data'=> '', 'message' => null];

        if($id == '/pns/photo/') $nip = $model['id'];

        $siasn = Json::decode(SiasnConfig::apiResult($mode, $ws['token_key'], $sso['token_sso'], $id, $nip));

        if(! array_key_exists('code', $siasn)) {
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
            //function save get data
            //$model = \app\modules\integrasi\controller\getdata::get(data);
            $data = $siasn['data'];
            $data['updated']  = date('Y-m-d H:i:s');  

            $model = SiasnDataUtama::findOne($data['id']);

            if($model === null) $model = new SiasnDataUtama();

            foreach($data as $attr => $value){
                if($data["$attr"] == 'null') $data["$attr"] = NULL;
                if($attr != 'path'){
                    if(isset($model["$attr"])){
                        $model["$attr"] = $data["$attr"];
                    }
                    if($attr == 'tanggal_taspen') $model["$attr"] = date('Y-m-d H:i:s', strtotime($data["$attr"]));
                }
            }
            
            if($model->save(false)){
                
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

        return $this->redirect(Url::previous());
    }

    public function actionGetSiasn($id, $nip)
    { 
        $sess = Yii::$app->session;
        $mode = 'prod';
        $username = $sess['nip'];
        $password = $sess['password'];
        
        $model = $this->findModelByNip($nip);

    }
}
