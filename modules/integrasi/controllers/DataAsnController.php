<?php

namespace app\modules\integrasi\controllers;

use Yii;
use app\modules\sitampan\models\PreskinAsn;
use app\modules\sitampan\models\PreskinAsnSearch;
use app\modules\simpeg\models\EpsTablokb;
use app\modules\gaji\models\SimasGajiStapegTbl;
use app\modules\integrasi\models\SiasnDataUtama;
use app\modules\simpeg\models\EpsMastfip;
use app\modules\integrasi\models\TblDataUpdate;
use app\modules\integrasi\models\SiasnIntegrasiConfig;
use app\modules\integrasi\models\TblHist;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;

/**
 * SiasnDataUtamaController implements the CRUD actions for SiasnDataUtama model.
 */
class DataAsnController extends Controller
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
                    'view' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $session = Yii::$app->session;  
        $session->remove('data');
        
        $jml = PreskinAsn::find()
            ->select(['nip', 'status'])
            ->where(['<', 'status', 13])
            ->count();

        $p3k = $this->getData('status', 12);
        $lk = $this->getData('SUBSTR("nip",15,1)', 1);


        $p3klk = $this->getData2('status', 12, 'SUBSTR("nip",15,1)', 1);
        $pnslk = $lk - $p3klk;

        $data['jml'] = $jml;
        $data['p3k'] = $p3k;
        $data['pns'] = $jml - $p3k;
        $data['lk'] = $lk;
        $data['pr'] = $jml - $lk;
        $data['p3klk'] = $p3klk;
        $data['p3kpr'] = $p3k - $p3klk;
        $data['pnslk'] = $pnslk;
        $data['pnspr'] = $data['pns'] - $pnslk;

        Url::remember();
        return $this->render('index', [
            'data' => $data,
        ]);
    }

    protected function getData($fil, $val){
        $query = PreskinAsn::find()
        ->select(['nip', 'status', $fil])
        ->where(['<', 'status', 13])
        ->andWhere([$fil => $val])       
        ->count()
        ;

        return $query;
    }

    protected function getData2($fil, $val, $fil2, $val2){
        $query = PreskinAsn::find()
        ->select(['nip', 'status', $fil, $fil2])
        ->where(['<', 'status', 13])
        ->andWhere([$fil => $val, $fil2 => $val2])
        ->count()
        ;

        return $query;
    }

    protected function getDataNot2($fil, $val, $fil2, $val2){
        $query = PreskinAsn::find()
        ->select(['nip', 'status', $fil1, $fil2])
        ->where(['<', 'status', 13])
        ->andWhere(['<>', $fil, $val])
        ->andWhere([$fil2 => $val2])
        ->count()
        ;

        return $query;
    }

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

    public function actionData()
    {
        $session = Yii::$app->session;  
        $session->remove('data');
        
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
            'a.A_01' => SORT_ASC,
            'a.A_03' => SORT_ASC,
            'a.A_01' => SORT_ASC,
            'a.E_04' => SORT_DESC, 
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

        Url::remember();
        return $this->render('data', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd,
            'sta' => $sta,
        ]);
    }

    public function actionView()
    {
        $session = Yii::$app->session;  
        if (!Yii::$app->request->post() && $session['data'] === null) return $this->redirect(['/site/index']);
        else{         
            $session['data'] = ISSET($session['data']) ? $session['data'] : Yii::$app->request->post()['PreskinAsn']['nip'];
            
            $this->layout = '//main-data';
            $nip = $session['data'];

            $session['simpeg'] = EpsMastfip::mastfipData($nip);
            $session['siasn'] = SiasnDataUtama::siasnData($nip);
            
            //Url::remember();
            return $this->render('view');
        }
    }

    public function actionProfil()
    {
        $session = Yii::$app->session;  
        if (!Yii::$app->request->post() && $session['data'] === null) return $this->redirect(['/site/index']);
        else{         
            $session['data'] = ISSET($session['data']) ? $session['data'] : Yii::$app->request->post()['PreskinAsn']['nip'];
            
            $this->layout = '//main-data';
            $nip = $session['data'];

            $session['simpeg'] = EpsMastfip::mastfipData($nip);
            $session['siasn'] = SiasnDataUtama::siasnData($nip);
            
            Url::remember();
            return $this->render('view');
        }
    }

    public function actionCpnsPns()
    {    
        $session = Yii::$app->session;  
        if (!Yii::$app->request->post() && $session['data'] === null) return $this->redirect(['/site/index']);
        else{         
            $session['data'] = ISSET($session['data']) ? $session['data'] : Yii::$app->request->post()['EpsMastfip']['B_02'];
            
            $this->layout = '//main-data';
            $nip = $session['data'];

            $session['simpeg'] = EpsMastfip::mastfipData($nip);
            $session['siasn'] = SiasnDataUtama::siasnData($nip);
            Url::remember();
            return $this->render('cpns-pns');
        }
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

        return $this->redirect(['profil']);
    }

}
