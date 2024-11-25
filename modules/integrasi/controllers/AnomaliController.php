<?php

namespace app\modules\integrasi\controllers;

use Yii;
use app\modules\integrasi\models\SiasnDataUtama;
use app\modules\integrasi\models\SiasnDataUtamaSearch;
use app\modules\integrasi\models\SiasnRefUnor;
use app\modules\simpeg\models\EpsMastfip;
use app\modules\simpeg\models\EpsTablokb;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;

/**
 * SiasnDataUtamaController implements the CRUD actions for SiasnDataUtama model.
 */
class AnomaliController extends Controller
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

    /**
     * Lists all SiasnDataUtama models.
     * @return mixed
     */
    // public function actionIndex()
    // {
    //     $searchModel = new SiasnDataUtamaSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    //     $dataProvider->query->orderBy([
    //         'flag' => SORT_ASC,
    //         'kedudukanPnsId' => SORT_ASC,
    //         'golRuangAkhirId' => SORT_DESC,
    //     ]);

    //     Url::remember();
    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    public function actionUnorNonAktif()
    {
        $si = SiasnDataUtama::find()
            ->joinWith('asnUnor a')
            ->joinWith('asnKedudukan b')
            ->where(['OR', ['a.aktif' => 'X'],['a.simpeg' => null]])
            ->andWhere(['b.aktif' => 1])
            ->orderBy(['unorId' => SORT_ASC]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $si,
        ]);
        
        Url::remember();
        return $this->render('unor-non-aktif', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewSimpeg($id)
    {
        $model = $this->findSimpeg($id);

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('view-simpeg', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('view-simpeg', [
                'model' => $model, 
            ]);
        } 
    }

    protected function findSimpeg($id)
    {
        if (($model = EpsMastfip::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
}
