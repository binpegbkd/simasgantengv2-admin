<?php

namespace app\modules\sitampan\controllers;

use Yii;
use app\modules\sitampan\models\PreskinAsn;
use app\modules\sitampan\models\PreskinAsnSearch;
use app\modules\sitampan\models\PreskinPaguTpp;
use app\modules\sitampan\models\PreskinHariKerja;
use app\modules\simpeg\models\EpsMastfip;
use app\modules\simpeg\models\EpsMastfipSearch;
use app\modules\simpeg\models\EpsTablokb;
use app\modules\gaji\models\SimasGajiStapegTbl;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;

/**
 * PreskinAsnController implements the CRUD actions for PreskinAsn model.
 */
class PreskinAsnController extends Controller
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
     * Lists all PreskinAsn models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PreskinAsnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if(isset($searchModel['status'])){
            if($searchModel['status'] == '') {
                //$dataProvider->query->andFilterWhere(['<', 'status', 13]);
                $dataProvider->query->andWhere(['OR',['<', 'status', 13],['>', 'tmt_stop', date('Y-m-01')], ['tmt_stop' => null]]);
            }
        }else{
            //$dataProvider->query->andFilterWhere(['<', 'status', 13]);
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
        ->select(['KDSTAPEG', 'NMSTAPEG', 'CONCAT("KDSTAPEG",\' - \', "NMSTAPEG") AS STAPEG'])
        ->asArray()
        ->orderBy(['KDSTAPEG' => SORT_ASC])
        ->all();

        $jad = PreskinHariKerja::find()->orderBy(['id' => SORT_ASC])->all();
        $hk[0] = ['id' => 0, 'jenis' => '0 - Belum dibuat jadwal'];
        foreach($jad as $h){
            $hk[] = ['id' => $h['id'], 'jenis' => $h['id'].' - '.$h['jenis']];
        }

        //return Json::encode($hk, $asArray = true);

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd,
            'sta' => $sta,
            'hk' => $hk,
        ]);
    }

    /**
     * Displays a single PreskinAsn model.
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
     * Creates a new PreskinAsn model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PreskinAsn();

        $jad = PreskinHariKerja::find()->all();
        $kelas = PreskinPaguTpp::find()->all();
        $sta = SimasGajiStapegTbl::find()
        ->select(['KDSTAPEG', 'NMSTAPEG', 'CONCAT("KDSTAPEG",\' - \', "NMSTAPEG") AS STAPEG'])
        ->asArray()
        ->orderBy(['KDSTAPEG' => SORT_ASC])
        ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data berhasil disimpan !!!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model, 'jad' => $jad, 'kelas' => $kelas, 'sta' => $sta,
            ]);
        }else{
            return $this->render('create', [
                'model' => $model, 'jad' => $jad, 'kelas' => $kelas, 'sta' => $sta,
            ]);
        } 
    }

    /**
     * Updates an existing PreskinAsn model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $jad = PreskinHariKerja::find()->all();
        $kelas = PreskinPaguTpp::find()->orderBy(['id' => SORT_ASC])->all();

        $sta = SimasGajiStapegTbl::find()
        ->select(['KDSTAPEG', 'NMSTAPEG', 'CONCAT("KDSTAPEG",\' - \', "NMSTAPEG") AS STAPEG'])
        ->asArray()
        ->orderBy(['KDSTAPEG' => SORT_ASC])
        ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                'title' => 'Berhasil',
                'text' => 'Data berhasil diubah !!!',
                'showConfirmButton' => false,
                'timer' => 1000
            ]);
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model, 'jad' => $jad, 'kelas' => $kelas, 'sta' => $sta,
            ]);
        }else{
            return $this->render('update', [
                'model' => $model, 'jad' => $jad, 'kelas' => $kelas, 'sta' => $sta,
            ]);
        } 
    }

    /**
     * Deletes an existing PreskinAsn model.
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
            'text' => 'Data berhasil dihapus !!!',
            'showConfirmButton' => false,
            'timer' => 2000
        ]);
        return $this->redirect(Url::previous());
    }

    /**
     * Finds the PreskinAsn model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PreskinAsn the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PreskinAsn::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
