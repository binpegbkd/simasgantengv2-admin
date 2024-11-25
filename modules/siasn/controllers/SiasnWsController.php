<?php

namespace app\modules\siasn\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use app\models\Fungsi;
use app\modules\integrasi\models\Search;
use app\modules\integrasi\models\SiasnIntegrasiConfig;
use app\modules\integrasi\models\SiasnWs;
use app\modules\integrasi\models\SiasnWsSearch;
/**
 * SiasnWsController implements the CRUD actions for SiasnWs model.
 */
class SiasnWsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
            ]
        );
    }

    /**
     * Lists all SiasnWs models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Search();
        $result = [];
        
        $ws = SiasnWs::find()
        ->select(["*", "CONCAT(method,' ',name,' : ' , path) AS tampil"])
        ->orderBy(['name' => SORT_ASC])
        ->asArray()
        ->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $nip = $model['nip'];
            $mode = $model['mode'];
            $path = $model['path'];
            $result = $this->getDataSiasn($nip, $mode, $path);

            $data = Json::decode($result, $asArray = true);

            if($path == '/pns/rw-golongan/'){
                // $dt = [];
                // foreach($data['data'] as $key => $val){
                //     [$dt[$val['golonganId']]] = [$val];
                // }
                // $dat = [];
                // foreach($dat as $key => $val)
                $data = $data['data'];
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return $data;
        }

        return $this->render('index', [
            'model' => $model,
            'ws' => $ws,
        ]);
    }

    /**
     * Displays a single SiasnWs model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SiasnWs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SiasnWs();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SiasnWs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SiasnWs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SiasnWs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return SiasnWs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiasnWs::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getDataSiasn($nip, $mode, $path)
    {
        $uname = '198306292010011024';
        $auth = \app\models\User::findOne(8188)['auth_key'];

        $tblconfig = SiasnIntegrasiConfig::findOne($mode); 

        $sso_exp = strtotime($tblconfig['token_sso_exp']);
        $key_exp = strtotime($tblconfig['token_key_exp']);
        $skrg = strtotime(date('Y-m-d H:i:s'));

        if($skrg >= $key_exp){
            $res = SiasnIntegrasiConfig::getTokenWSO2($mode);
            //return $res;
        }
        if($skrg >= $sso_exp){
            $resu = SiasnIntegrasiConfig::getTokenSSO($uname, $auth, $mode);
            //return $resu;
        }

        $result = SiasnIntegrasiConfig::apiResult($path, $nip, $mode);

        return $result;            
    }
}
