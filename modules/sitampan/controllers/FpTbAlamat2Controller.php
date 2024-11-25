<?php

namespace app\modules\sitampan\controllers;

use Yii;
use app\modules\sitampan\models\FpTbAlamat2;
use app\modules\sitampan\models\FpTbAlamat2Search;
use app\modules\simpeg\models\EpsTablokb;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * FpTbAlamat2Controller implements the CRUD actions for FpTbAlamat2 model.
 */
class FpTbAlamat2Controller extends Controller
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
     * Lists all FpTbAlamat2 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FpTbAlamat2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['kodealamat' => SORT_ASC]);

        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->all();

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'opd' => $opd,
        ]);
    }

    /**
     * Displays a single FpTbAlamat2 model.
     * @param integer $id
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
     * Creates a new FpTbAlamat2 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FpTbAlamat2();
        
        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
        ->all();

        $mod = FpTbAlamat2::find()->select(['kodealamat'])->orderBy(['kodealamat' => SORT_DESC])->one();
        if($mod === null) $model['kodealamat'] = 1;
        else $model['kodealamat'] = $mod['kodealamat'] + 1; 

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
            return $this->redirect(Url::previous());
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model, 'opd' => $opd,
            ]);
        }else{
            return $this->render('create', [
                'model' => $model, 'opd' => $opd,
            ]);
        } 
    }

    /**
     * Updates an existing FpTbAlamat2 model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $opd = EpsTablokb::find()
        ->select(['KOLOK', 'UNIT', "NALOK", "CONCAT(\"KOLOK\",' ',\"NALOK\") AS UNOR"])
        ->asArray()
        ->where(new yii\db\Expression('"KOLOK" = "UNIT"'))
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
                'model' => $model, 'opd' => $opd,
            ]);
        }else{
            return $this->render('update', [
                'model' => $model, 'opd' => $opd, 
            ]);
        } 
    }

    /**
     * Deletes an existing FpTbAlamat2 model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Data berhasil dihapus.');
        return $this->redirect(Url::previous());
    }

    /**
     * Finds the FpTbAlamat2 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FpTbAlamat2 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FpTbAlamat2::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
