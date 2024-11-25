<?php

namespace app\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Json;
use app\models\LoginForm;

use dominus77\sweetalert2\Alert;

use app\modules\simpeg\models\EpsMastfip;
use app\modules\integrasi\models\TblDataUpdate;
use app\modules\integrasi\models\SiasnDataUtama;
use app\modules\gaji\models\SimasGajiMstpegawai;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                
                'class' => AccessControl::className(),
                'only' => ['index', 'error', 'login', 'sign-out'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['error', 'index', 'login', 'sign-out'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'sign-out' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {         
    
        //if(Yii::$app->session['role'] != 5) return $this->redirect('http://simasganteng.brebeskab.go.id');
        if (Yii::$app->user->isGuest) return $this->redirect('http://simasganteng.brebeskab.go.id');

        $sess = Yii::$app->session;
        // return $sess['module'];

        // if($sess['module'] == 3) {
        //     //Url::remember();
        //     return $this->redirect(['/integrasi/data-asn']);
        // }else if($sess['module'] == 7) {
        //     Url::remember();
        //     return $this->render('index7');
        // }
            
        if($sess['module'] == 3) //return $this->redirect('/asn');      
            $path = 'index3';
        else if($sess['module'] == 7) $path = 'index7';
        // else if($sess['module'] == 11) $path = 'index11';
        else $path = 'index';
        
        $siasn = TblDataUpdate::find()
            ->select(['nipBaru', 'dataUtama', 'flag'])
            ->where(['<', 'flag', 99])
            ->count();

        $gaji = SimasGajiMstpegawai::find()
            ->select(['NIP', 'KDSTAPEG'])
            ->where(['<', 'KDSTAPEG', 13])
            ->count();
        
        $jml = EpsMastfip::find()
            ->select(['B_02', 'B_08'])
            ->where(['<', 'B_08', 3])
            ->count();

        $p3k = $this->getData('B_10', 71);
        $jabstru = $this->getData('G_05A', 1);
        $jabum = $this->getData('G_05A', 0);
        $lk = $this->getData('B_06', 1);

        $p3klk = $this->getData2('B_10', 71, 'B_06', 1);
        $pnslk = $this->getDataNot2('B_10', 71, 'B_06', 1);

        $data['asn-gaji'] = $gaji;
        $data['asn-siasn'] = $siasn;

        $data['jml'] = $jml;
        $data['p3k'] = $p3k;
        $data['pns'] = $jml - $p3k;

        $p3ksi = $this->getDataSiasn('kedudukanPnsId', 71);

        $data['p3k-si'] = $p3ksi;
        $data['pns-si'] = $siasn - $p3ksi;

        $p3kgj = SimasGajiMstpegawai::find()
            ->select(['NIP', 'KDSTAPEG'])
            ->where(['KDSTAPEG' => 12])
            ->count();

        $data['p3k-gj'] = $p3kgj;
        $data['pns-gj'] = $gaji - $p3kgj;            

        $data['jabstru'] = $jabstru;
        $data['jabum'] = $jabum;
        $data['jabfung'] = $jml - $jabstru - $jabum;
        $data['lk'] = $lk;
        $data['pr'] = $jml - $lk;
        $data['p3klk'] = $p3klk;
        $data['p3kpr'] = $p3k - $p3klk;
        $data['pnslk'] = $pnslk;
        $data['pnspr'] = $data['pns'] - $pnslk;

        Url::remember();
        return $this->render($path,['data' => $data]);
    }

    public function actionPegawai()
    {         
        if (Yii::$app->user->isGuest) return $this->redirect('http://simasganteng.brebeskab.go.id');

        $session = Yii::$app->session;
        $session['data'] = null;
        
        $search = new EpsMastfipSearch();
        $data = $search->search(Yii::$app->request->queryParams);
        $data->query->orderBy([
            'E_04' => SORT_DESC,
            'A_01' => SORT_ASC,
            'A_03' => SORT_ASC,
            'A_04' => SORT_ASC,
        ]);

        Url::remember();
        return $this->render('pegawai',['model' => $search, 'data' => $data]);        
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $get = Yii::$app->request->get();
        if(isset($get['role']) || isset($get['token']) || isset($get['id']) || isset($get['key']) || isset($get['name'])){
            $token = $get['token'];
            $module = $get['name'];
            $role = base64_decode(str_replace($token, '', $get['role']));
            
            $username = $this->dekripsi(base64_decode($get['id']), $token);
            $password = $this->dekripsi(base64_decode($get['key']), $token);

            if($username != '' && $password != '' && $token != ''){
                $model = new LoginForm([
                    'username' => $username,
                    'password' => $password,
                    'token' => $token,
                ]);
       
                if ($model->login()) {
                    
                    $session = Yii::$app->session;
                    $session['password'] = $model['password'];
                    $session['username'] = $model['username'];
                    $session['nip'] = Yii::$app->user->identity->nipBaru; 
                    $session['pengguna'] = Yii::$app->user->identity->pengguna;
                    $session['namapengguna'] = Yii::$app->user->identity->namapengguna;
                    $session['iduser'] = Yii::$app->user->identity->id;
                    $session['role'] = $role;
                    $session['module'] = $module;
                    $session['roleadmin'] = \app\models\RoleUser::getRoleAdmin($session['iduser']);
                    $session['token_id'] = Yii::$app->user->identity->token_id;
                    $session['token_expired'] = Yii::$app->user->identity->token_expired;
                    
                    return $this->goBack();
                }
            }
        }

        return $this->redirect('http://simasganteng.brebeskab.go.id');
    }

    public function actionSignOut()
    {  
        Yii::$app->response->redirect('http://simasganteng.brebeskab.go.id', 301);
        Yii::$app->end();
    }
    
    protected function enkripsi($name, $key){
        
        $method = "AES-256-CBC";
        $options = 0;
        $iv = 'Qkcnaiag24r9cnxZ';

        return openssl_encrypt($name, $method, $key, $options, $iv);
    }

    protected function dekripsi($name, $key){
        
        $method = "AES-256-CBC";
        $options = 0;
        $iv = 'Qkcnaiag24r9cnxZ';

        return openssl_decrypt($name, $method, $key, $options, $iv);
    }

    protected function getData($fil, $val){
        $query = EpsMastfip::find()
        ->select(['B_02', 'B_08', $fil])
        ->where(['<', 'B_08', 3])
        ->andWhere([$fil => $val])
        ->count()
        ;

        return $query;
    }

    protected function getData2($fil, $val, $fil2, $val2){
        $query = EpsMastfip::find()
        ->select(['B_02', 'B_08', $fil, $fil2])
        ->where(['<', 'B_08', 3])
        ->andWhere([$fil => $val, $fil2 => $val2])
        ->count()
        ;

        return $query;
    }

    protected function getDataNot2($fil, $val, $fil2, $val2){
        $query = EpsMastfip::find()
        ->select(['B_02', 'B_08', $fil, $fil2])
        ->where(['<', 'B_08', 3])
        ->andWhere(['<>', $fil, $val])
        ->andWhere([$fil2 => $val2])
        ->count()
        ;

        return $query;
    }

    protected function getDataSiasn($fil, $val){
        $query = SiasnDataUtama::find()
        ->select(['nipBaru', 'flag', $fil])
        ->joinWith('asnKedudukan a')
        ->where(['a.aktif' => 1])
        ->andWhere([$fil => $val])
        ->count()
        ;

        return $query;
    }
}
