<?php

namespace app\modules\integrasi\controllers;

use Yii;
use app\modules\integrasi\models\SiasnRefUnor;
use app\modules\integrasi\models\SiasnRefUnorSearch;
use app\modules\integrasi\models\SiasnIntegrasiConfig;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use kartik\mpdf\Pdf;

/**
 * SiasnRefUnorController implements the CRUD actions for SiasnRefUnor model.
 */
class SiasnRefUnorController extends Controller
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
            ]
        );
    }

    /**
     * Lists all SiasnRefUnor models.
     *
     * @return string
     */
    public function actionIndex()
    {
        //$this->layout = '//main-ref';
        $searchModel = new SiasnRefUnorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if($searchModel['namaUnor'] !== null){
            $r = SiasnRefUnor::find()->where(['ilike', 'namaUnor', trim($searchModel['namaUnor'],' ')])->andWhere(['aktif' => 'A'])->one();

            if($r === null) {
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR,
                    'title' => 'Gagal',
                    'text' => 'Data tidak ditemukan ..!!',
                    'showConfirmButton' => false,
                    'timer' => 1000
                ]);  

                return $this->redirect(Url::previous());
            }
            
            if($r['diatasanId'] !== null && $r['diatasanId'] != '') $unor_atas = $this->getUnor($r['diatasanId']);
            else $unor_atas = '';
            
            $dt[] = [
                'NO' => 1,
                'ID' => $r['id'],
                'HIERARKI' => $r['namaUnor'],
                'NAMA' => $r['namaUnor'],
                'ESELON-ID' => $r['eselonId'],
                'NAMA-JABATAN' => $r['namaJabatan'],
                'UNOR-ATASAN-ID' => $r['diatasanId'],
                'UNOR-ATASAN' => $unor_atas,
                'UNOR-SIMPEG' => $r['simpeg'],
            ];
        }else{
            $r = SiasnRefUnor::find()->where(['ilike', 'namaUnor', 'pemerintah kabupaten'])->andWhere(['aktif' => 'A'])->one();
            if($r === null) $dt = [];
            else{
                $dt[] = [
                    'NO' => 1,
                    'ID' => $r['id'],
                    'HIERARKI' => $r['namaUnor'],
                    'NAMA' => $r['namaUnor'],
                    'ESELON-ID' => $r['eselonId'],
                    'NAMA-JABATAN' => $r['namaJabatan'],
                    'UNOR-ATASAN-ID' => '',
                    'UNOR-ATASAN' => '',
                    'UNOR-SIMPEG' => $r['simpeg'],
                ];
            }
        }

        $r10 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
        ->andWhere(['aktif' => 'A', 'diatasanId' => $r['id']])
        ->orderBy(['eselonId' => SORT_ASC, 'simpeg' => SORT_ASC])
        ->all()
        ;

        $no = 1;
        foreach($r10 as $r1){
            $no = $no + 1;
            $dt[] = [
                'NO' => $no,
                'ID' => $r1['id'],
                'HIERARKI' => '-- '.$r1['namaUnor'],
                'NAMA' => $r1['namaUnor'],
                'ESELON-ID' => $r1['eselonId'],
                'NAMA-JABATAN' => $r1['namaJabatan'],
                'UNOR-ATASAN-ID' => $r1['diatasanId'],
                'UNOR-ATASAN' => $this->getUnor($r1['diatasanId']),
                'UNOR-SIMPEG' => $r1['simpeg'],
            ];

            $r20 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
            ->andWhere(['aktif' => 'A', 'diatasanId' => $r1['id']])
            ->orderBy(['eselonId' => SORT_ASC, 'simpeg' => SORT_ASC])
            ->all()
            ;

            foreach($r20 as $r2){
                $no = $no + 1;
                $dt[] = [
                    'NO' => $no,
                    'ID' => $r2['id'],
                    'HIERARKI' => '---- '.$r2['namaUnor'],
                    'NAMA' => $r2['namaUnor'],
                    'ESELON-ID' => $r2['eselonId'],
                    'NAMA-JABATAN' => $r2['namaJabatan'],
                    'UNOR-ATASAN-ID' => $r2['diatasanId'],
                    'UNOR-ATASAN' => $this->getUnor($r2['diatasanId']),
                    'UNOR-SIMPEG' => $r2['simpeg'],
                ];

                $r30 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                ->andWhere(['aktif' => 'A', 'diatasanId' => $r2['id']])
                ->orderBy(['eselonId' => SORT_ASC, 'simpeg' => SORT_ASC])
                ->all()
                ;

                foreach($r30 as $r3){
                    $no = $no + 1;
                    $dt[] = [
                        'NO' => $no,
                        'ID' => $r3['id'],
                        'HIERARKI' => '------ '.$r3['namaUnor'],
                        'NAMA' => $r3['namaUnor'],
                        'ESELON-ID' => $r3['eselonId'],
                        'NAMA-JABATAN' => $r3['namaJabatan'],
                        'UNOR-ATASAN-ID' => $r3['diatasanId'],
                        'UNOR-ATASAN' => $this->getUnor($r3['diatasanId']),
                        'UNOR-SIMPEG' => $r3['simpeg'],
                    ];

                    $r40 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                    ->andWhere(['aktif' => 'A', 'diatasanId' => $r3['id']])
                    ->orderBy(['eselonId' => SORT_ASC, 'simpeg' => SORT_ASC])
                    ->all()
                    ;

                    foreach($r40 as $r4){
                        $no = $no + 1;
                        $dt[] = [
                            'NO' => $no,
                            'ID' => $r4['id'],
                            'HIERARKI' => '-------- '.$r4['namaUnor'],
                            'NAMA' => $r4['namaUnor'],
                            'ESELON-ID' => $r4['eselonId'],
                            'NAMA-JABATAN' => $r4['namaJabatan'],
                            'UNOR-ATASAN-ID' => $r4['diatasanId'],
                            'UNOR-ATASAN' => $this->getUnor($r4['diatasanId']),
                            'UNOR-SIMPEG' => $r4['simpeg'],
                        ];

                        $r50 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                        ->andWhere(['aktif' => 'A', 'diatasanId' => $r4['id']])
                        ->orderBy(['eselonId' => SORT_ASC, 'simpeg' => SORT_ASC])
                        ->all()
                        ;

                        foreach($r50 as $r5){
                            $no = $no + 1;
                            $dt[] = [
                                'NO' => $no,
                                'ID' => $r5['id'],
                                'HIERARKI' => '---------- '.$r5['namaUnor'],
                                'NAMA' => $r5['namaUnor'],
                                'ESELON-ID' => $r5['eselonId'],
                                'NAMA-JABATAN' => $r5['namaJabatan'],
                                'UNOR-ATASAN-ID' => $r5['diatasanId'],
                                'UNOR-ATASAN' => $this->getUnor($r5['diatasanId']),
                                'UNOR-SIMPEG' => $r5['simpeg'],
                            ];

                            $r60 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                            ->andWhere(['aktif' => 'A', 'diatasanId' => $r5['id']])
                            ->orderBy(['eselonId' => SORT_ASC, 'simpeg' => SORT_ASC])
                            ->all()
                            ;

                            foreach($r60 as $r6){
                                $no = $no + 1;
                                $dt[] = [
                                    'NO' => $no,
                                    'ID' => $r6['id'],
                                    'HIERARKI' => '------------ '.$r6['namaUnor'],
                                    'NAMA' => $r6['namaUnor'],
                                    'ESELON-ID' => $r6['eselonId'],
                                    'NAMA-JABATAN' => $r6['namaJabatan'],
                                    'UNOR-ATASAN-ID' => $r6['diatasanId'],
                                    'UNOR-ATASAN' => $this->getUnor($r6['diatasanId']),
                                    'UNOR-SIMPEG' => $r6['simpeg'],
                                ];
                            }
                        }
                    }
                }
            }
        }
        Url::remember();
        return $this->render('index', [
            'dta' => $dt,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAllUnor()
    {
        // $this->layout = '//main-ref';
        $searchModel = new SiasnRefUnorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if($searchModel['namaUnor'] !== null){
            $r = SiasnRefUnor::find()
                ->where(['ilike', 'namaUnor', trim($searchModel['namaUnor'],' ')])
                ->orderBy(['aktif' => SORT_ASC])
                ->one();

            if($r === null) {
                return $this->render('all', [
                    'dta' => [],
                    'searchModel' => $searchModel,
                ]);
            }
            
            if($r['diatasanId'] !== null && $r['diatasanId'] != '') $unor_atas = $this->getUnor($r['diatasanId']);
            else $unor_atas = '';
            
            $dt[] = [
                'NO' => 1,
                'ID' => $r['id'],
                'HIERARKI' => $r['namaUnor'],
                'NAMA' => $r['namaUnor'],
                'ESELON-ID' => $r['eselonId'],
                'NAMA-JABATAN' => $r['namaJabatan'],
                'UNOR-ATASAN-ID' => $r['diatasanId'],
                'UNOR-ATASAN' => $unor_atas,
                'UNOR-SIMPEG' => $r['simpeg'],
                'AKTIF' => $r['aktif'],
            ];
        }else{
            $r = SiasnRefUnor::find()->where(['ilike', 'namaUnor', 'pemerintah kabupaten'])->one();
            if($r === null) $dt = [];
            else{
                $dt[] = [
                    'NO' => 1,
                    'ID' => $r['id'],
                    'HIERARKI' => $r['namaUnor'],
                    'NAMA' => $r['namaUnor'],
                    'ESELON-ID' => $r['eselonId'],
                    'NAMA-JABATAN' => $r['namaJabatan'],
                    'UNOR-ATASAN-ID' => '',
                    'UNOR-ATASAN' => '',
                    'UNOR-SIMPEG' => $r['simpeg'],
                    'AKTIF' => $r['aktif'],
                ];
            }
        }

        $r10 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
        ->andWhere(['diatasanId' => $r['id']])
        ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
        ->all()
        ;

        $no = 1;
        foreach($r10 as $r1){
            $no = $no + 1;
            $dt[] = [
                'NO' => $no,
                'ID' => $r1['id'],
                'HIERARKI' => '-- '.$r1['namaUnor'],
                'NAMA' => $r1['namaUnor'],
                'ESELON-ID' => $r1['eselonId'],
                'NAMA-JABATAN' => $r1['namaJabatan'],
                'UNOR-ATASAN-ID' => $r1['diatasanId'],
                'UNOR-ATASAN' => $this->getUnor($r1['diatasanId']),
                'UNOR-SIMPEG' => $r1['simpeg'],
                'AKTIF' => $r1['aktif'],
            ];

            $r20 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
            ->andWhere(['diatasanId' => $r1['id']])
            ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
            ->all()
            ;

            foreach($r20 as $r2){
                $no = $no + 1;
                $dt[] = [
                    'NO' => $no,
                    'ID' => $r2['id'],
                    'HIERARKI' => '---- '.$r2['namaUnor'],
                    'NAMA' => $r2['namaUnor'],
                    'ESELON-ID' => $r2['eselonId'],
                    'NAMA-JABATAN' => $r2['namaJabatan'],
                    'UNOR-ATASAN-ID' => $r2['diatasanId'],
                    'UNOR-ATASAN' => $this->getUnor($r2['diatasanId']),
                    'UNOR-SIMPEG' => $r2['simpeg'],
                    'AKTIF' => $r2['aktif'],
                ];

                $r30 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                ->andWhere(['diatasanId' => $r2['id']])
                ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                ->all()
                ;

                foreach($r30 as $r3){
                    $no = $no + 1;
                    $dt[] = [
                        'NO' => $no,
                        'ID' => $r3['id'],
                        'HIERARKI' => '------ '.$r3['namaUnor'],
                        'NAMA' => $r3['namaUnor'],
                        'ESELON-ID' => $r3['eselonId'],
                        'NAMA-JABATAN' => $r3['namaJabatan'],
                        'UNOR-ATASAN-ID' => $r3['diatasanId'],
                        'UNOR-ATASAN' => $this->getUnor($r3['diatasanId']),
                        'UNOR-SIMPEG' => $r3['simpeg'],
                        'AKTIF' => $r3['aktif'],
                    ];

                    $r40 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                    ->andWhere(['aktif' => 'A', 'diatasanId' => $r3['id']])
                    ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                    ->all()
                    ;

                    foreach($r40 as $r4){
                        $no = $no + 1;
                        $dt[] = [
                            'NO' => $no,
                            'ID' => $r4['id'],
                            'HIERARKI' => '-------- '.$r4['namaUnor'],
                            'NAMA' => $r4['namaUnor'],
                            'ESELON-ID' => $r4['eselonId'],
                            'NAMA-JABATAN' => $r4['namaJabatan'],
                            'UNOR-ATASAN-ID' => $r4['diatasanId'],
                            'UNOR-ATASAN' => $this->getUnor($r4['diatasanId']),
                            'UNOR-SIMPEG' => $r4['simpeg'],
                            'AKTIF' => $r4['aktif'],
                        ];

                        $r50 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                        ->andWhere(['aktif' => 'A', 'diatasanId' => $r4['id']])
                        ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                        ->all()
                        ;

                        foreach($r50 as $r5){
                            $no = $no + 1;
                            $dt[] = [
                                'NO' => $no,
                                'ID' => $r5['id'],
                                'HIERARKI' => '---------- '.$r5['namaUnor'],
                                'NAMA' => $r5['namaUnor'],
                                'ESELON-ID' => $r5['eselonId'],
                                'NAMA-JABATAN' => $r5['namaJabatan'],
                                'UNOR-ATASAN-ID' => $r5['diatasanId'],
                                'UNOR-ATASAN' => $this->getUnor($r5['diatasanId']),
                                'UNOR-SIMPEG' => $r5['simpeg'],
                                'AKTIF' => $r5['aktif'],
                            ];

                            $r60 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                            ->andWhere(['diatasanId' => $r5['id']])
                            ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                            ->all()
                            ;

                            foreach($r60 as $r6){
                                $no = $no + 1;
                                $dt[] = [
                                    'NO' => $no,
                                    'ID' => $r6['id'],
                                    'HIERARKI' => '------------ '.$r6['namaUnor'],
                                    'NAMA' => $r6['namaUnor'],
                                    'ESELON-ID' => $r6['eselonId'],
                                    'NAMA-JABATAN' => $r6['namaJabatan'],
                                    'UNOR-ATASAN-ID' => $r6['diatasanId'],
                                    'UNOR-ATASAN' => $this->getUnor($r6['diatasanId']),
                                    'UNOR-SIMPEG' => $r6['simpeg'],
                                    'AKTIF' => $r6['aktif'],
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $this->render('all', [
            'dta' => $dt,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAllUnorCetak()
    {    
        $r = SiasnRefUnor::find()->where(['ilike', 'namaUnor', 'pemerintah kabupaten'])->one();
        if($r === null) $dt = [];
        else{
            $dt[] = [
                'NO' => 1,
                'ID' => $r['id'],
                'HIERARKI' => $r['namaUnor'],
                'NAMA' => $r['namaUnor'],
                'ESELON-ID' => $r['eselonId'],
                'NAMA-JABATAN' => $r['namaJabatan'],
                'UNOR-ATASAN-ID' => '',
                'UNOR-ATASAN' => '',
                'UNOR-SIMPEG' => $r['simpeg'],
                'AKTIF' => $r['aktif'],
            ];
        }

        $r10 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
        ->andWhere(['diatasanId' => $r['id']])
        ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
        ->all()
        ;

        $no = 1;
        foreach($r10 as $r1){
            $no = $no + 1;
            $dt[] = [
                'NO' => $no,
                'ID' => $r1['id'],
                'HIERARKI' => '-- '.$r1['namaUnor'],
                'NAMA' => $r1['namaUnor'],
                'ESELON-ID' => $r1['eselonId'],
                'NAMA-JABATAN' => $r1['namaJabatan'],
                'UNOR-ATASAN-ID' => $r1['diatasanId'],
                'UNOR-ATASAN' => $this->getUnor($r1['diatasanId']),
                'UNOR-SIMPEG' => $r1['simpeg'],
                'AKTIF' => $r1['aktif'],
            ];

            $r20 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
            ->andWhere(['diatasanId' => $r1['id']])
            ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
            ->all()
            ;

            foreach($r20 as $r2){
                $no = $no + 1;
                $dt[] = [
                    'NO' => $no,
                    'ID' => $r2['id'],
                    'HIERARKI' => '---- '.$r2['namaUnor'],
                    'NAMA' => $r2['namaUnor'],
                    'ESELON-ID' => $r2['eselonId'],
                    'NAMA-JABATAN' => $r2['namaJabatan'],
                    'UNOR-ATASAN-ID' => $r2['diatasanId'],
                    'UNOR-ATASAN' => $this->getUnor($r2['diatasanId']),
                    'UNOR-SIMPEG' => $r2['simpeg'],
                    'AKTIF' => $r2['aktif'],
                ];

                $r30 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                ->andWhere(['diatasanId' => $r2['id']])
                ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                ->all()
                ;

                foreach($r30 as $r3){
                    $no = $no + 1;
                    $dt[] = [
                        'NO' => $no,
                        'ID' => $r3['id'],
                        'HIERARKI' => '------ '.$r3['namaUnor'],
                        'NAMA' => $r3['namaUnor'],
                        'ESELON-ID' => $r3['eselonId'],
                        'NAMA-JABATAN' => $r3['namaJabatan'],
                        'UNOR-ATASAN-ID' => $r3['diatasanId'],
                        'UNOR-ATASAN' => $this->getUnor($r3['diatasanId']),
                        'UNOR-SIMPEG' => $r3['simpeg'],
                        'AKTIF' => $r3['aktif'],
                    ];

                    $r40 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                    ->andWhere(['aktif' => 'A', 'diatasanId' => $r3['id']])
                    ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                    ->all()
                    ;

                    foreach($r40 as $r4){
                        $no = $no + 1;
                        $dt[] = [
                            'NO' => $no,
                            'ID' => $r4['id'],
                            'HIERARKI' => '-------- '.$r4['namaUnor'],
                            'NAMA' => $r4['namaUnor'],
                            'ESELON-ID' => $r4['eselonId'],
                            'NAMA-JABATAN' => $r4['namaJabatan'],
                            'UNOR-ATASAN-ID' => $r4['diatasanId'],
                            'UNOR-ATASAN' => $this->getUnor($r4['diatasanId']),
                            'UNOR-SIMPEG' => $r4['simpeg'],
                            'AKTIF' => $r4['aktif'],
                        ];

                        $r50 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                        ->andWhere(['aktif' => 'A', 'diatasanId' => $r4['id']])
                        ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                        ->all()
                        ;

                        foreach($r50 as $r5){
                            $no = $no + 1;
                            $dt[] = [
                                'NO' => $no,
                                'ID' => $r5['id'],
                                'HIERARKI' => '---------- '.$r5['namaUnor'],
                                'NAMA' => $r5['namaUnor'],
                                'ESELON-ID' => $r5['eselonId'],
                                'NAMA-JABATAN' => $r5['namaJabatan'],
                                'UNOR-ATASAN-ID' => $r5['diatasanId'],
                                'UNOR-ATASAN' => $this->getUnor($r5['diatasanId']),
                                'UNOR-SIMPEG' => $r5['simpeg'],
                                'AKTIF' => $r5['aktif'],
                            ];

                            $r60 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                            ->andWhere(['diatasanId' => $r5['id']])
                            ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                            ->all()
                            ;

                            foreach($r60 as $r6){
                                $no = $no + 1;
                                $dt[] = [
                                    'NO' => $no,
                                    'ID' => $r6['id'],
                                    'HIERARKI' => '------------ '.$r6['namaUnor'],
                                    'NAMA' => $r6['namaUnor'],
                                    'ESELON-ID' => $r6['eselonId'],
                                    'NAMA-JABATAN' => $r6['namaJabatan'],
                                    'UNOR-ATASAN-ID' => $r6['diatasanId'],
                                    'UNOR-ATASAN' => $this->getUnor($r6['diatasanId']),
                                    'UNOR-SIMPEG' => $r6['simpeg'],
                                    'AKTIF' => $r6['aktif'],
                                ];
                            }
                        }
                    }
                }
            }
        }
        $content =  $this->renderPartial('cetak-unor', [
            'dta' => $dt,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, 
            'format' => Pdf::FORMAT_FOLIO, 
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            'destination' => Pdf::DEST_BROWSER, 
            'content' => $content,  
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:12px}', 
            'options' => ['title' => 'HIERARKI UNIT ORGANISASI PEMERINTAH KABUPATEN BREBES'],
            'methods' => [ 
                'SetHeader'=>['HIERARKI UNIT ORGANISASI PEMERINTAH KABUPATEN BREBES'], 
                'SetFooter'=>['
                <div align="right">Hal. {PAGENO}</div>
                '],
            ]
        ]);

        return $pdf->render(); 
    }

    public function actionAllPrint()
    {
        $this->layout = '//main-ref';
        $searchModel = new SiasnRefUnorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if($searchModel['namaUnor'] !== null){
            $r = SiasnRefUnor::find()->where(['ilike', 'namaUnor', trim($searchModel['namaUnor'],' ')])->one();

            if($r === null) {
                return $this->render('all', [
                    'dta' => [],
                    'searchModel' => $searchModel,
                ]);
            }
            
            if($r['diatasanId'] !== null && $r['diatasanId'] != '') $unor_atas = $this->getUnor($r['diatasanId']);
            else $unor_atas = '';
            
            $dt[] = [
                'NO' => 1,
                'ID' => $r['id'],
                'HIERARKI' => $r['namaUnor'],
                'NAMA' => $r['namaUnor'],
                'ESELON-ID' => $r['eselonId'],
                'NAMA-JABATAN' => $r['namaJabatan'],
                'UNOR-ATASAN-ID' => $r['diatasanId'],
                'UNOR-ATASAN' => $unor_atas,
                'UNOR-SIMPEG' => $r['simpeg'],
                'AKTIF' => $r['aktif'],
            ];
        }else{
            $r = SiasnRefUnor::find()->where(['ilike', 'namaUnor', 'pemerintah kabupaten'])->one();
            if($r === null) $dt = [];
            else{
                $dt[] = [
                    'NO' => 1,
                    'ID' => $r['id'],
                    'HIERARKI' => $r['namaUnor'],
                    'NAMA' => $r['namaUnor'],
                    'ESELON-ID' => $r['eselonId'],
                    'NAMA-JABATAN' => $r['namaJabatan'],
                    'UNOR-ATASAN-ID' => '',
                    'UNOR-ATASAN' => '',
                    'UNOR-SIMPEG' => $r['simpeg'],
                    'AKTIF' => $r['aktif'],
                ];
            }
        }

        $r10 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
        ->andWhere(['diatasanId' => $r['id']])
        ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
        ->all()
        ;

        $no = 1;
        foreach($r10 as $r1){
            $no = $no + 1;
            $dt[] = [
                'NO' => $no,
                'ID' => $r1['id'],
                'HIERARKI' => '-- '.$r1['namaUnor'],
                'NAMA' => $r1['namaUnor'],
                'ESELON-ID' => $r1['eselonId'],
                'NAMA-JABATAN' => $r1['namaJabatan'],
                'UNOR-ATASAN-ID' => $r1['diatasanId'],
                'UNOR-ATASAN' => $this->getUnor($r1['diatasanId']),
                'UNOR-SIMPEG' => $r1['simpeg'],
                'AKTIF' => $r1['aktif'],
            ];

            $r20 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
            ->andWhere(['diatasanId' => $r1['id']])
            ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
            ->all()
            ;

            foreach($r20 as $r2){
                $no = $no + 1;
                $dt[] = [
                    'NO' => $no,
                    'ID' => $r2['id'],
                    'HIERARKI' => '---- '.$r2['namaUnor'],
                    'NAMA' => $r2['namaUnor'],
                    'ESELON-ID' => $r2['eselonId'],
                    'NAMA-JABATAN' => $r2['namaJabatan'],
                    'UNOR-ATASAN-ID' => $r2['diatasanId'],
                    'UNOR-ATASAN' => $this->getUnor($r2['diatasanId']),
                    'UNOR-SIMPEG' => $r2['simpeg'],
                    'AKTIF' => $r2['aktif'],
                ];

                $r30 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                ->andWhere(['diatasanId' => $r2['id']])
                ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                ->all()
                ;

                foreach($r30 as $r3){
                    $no = $no + 1;
                    $dt[] = [
                        'NO' => $no,
                        'ID' => $r3['id'],
                        'HIERARKI' => '------ '.$r3['namaUnor'],
                        'NAMA' => $r3['namaUnor'],
                        'ESELON-ID' => $r3['eselonId'],
                        'NAMA-JABATAN' => $r3['namaJabatan'],
                        'UNOR-ATASAN-ID' => $r3['diatasanId'],
                        'UNOR-ATASAN' => $this->getUnor($r3['diatasanId']),
                        'UNOR-SIMPEG' => $r3['simpeg'],
                        'AKTIF' => $r3['aktif'],
                    ];

                    $r40 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                    ->andWhere(['aktif' => 'A', 'diatasanId' => $r3['id']])
                    ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                    ->all()
                    ;

                    foreach($r40 as $r4){
                        $no = $no + 1;
                        $dt[] = [
                            'NO' => $no,
                            'ID' => $r4['id'],
                            'HIERARKI' => '-------- '.$r4['namaUnor'],
                            'NAMA' => $r4['namaUnor'],
                            'ESELON-ID' => $r4['eselonId'],
                            'NAMA-JABATAN' => $r4['namaJabatan'],
                            'UNOR-ATASAN-ID' => $r4['diatasanId'],
                            'UNOR-ATASAN' => $this->getUnor($r4['diatasanId']),
                            'UNOR-SIMPEG' => $r4['simpeg'],
                            'AKTIF' => $r4['aktif'],
                        ];

                        $r50 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                        ->andWhere(['aktif' => 'A', 'diatasanId' => $r4['id']])
                        ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                        ->all()
                        ;

                        foreach($r50 as $r5){
                            $no = $no + 1;
                            $dt[] = [
                                'NO' => $no,
                                'ID' => $r5['id'],
                                'HIERARKI' => '---------- '.$r5['namaUnor'],
                                'NAMA' => $r5['namaUnor'],
                                'ESELON-ID' => $r5['eselonId'],
                                'NAMA-JABATAN' => $r5['namaJabatan'],
                                'UNOR-ATASAN-ID' => $r5['diatasanId'],
                                'UNOR-ATASAN' => $this->getUnor($r5['diatasanId']),
                                'UNOR-SIMPEG' => $r5['simpeg'],
                                'AKTIF' => $r5['aktif'],
                            ];

                            $r60 = SiasnRefUnor::find()->where(['<>', 'eselonId', '0'])
                            ->andWhere(['diatasanId' => $r5['id']])
                            ->orderBy(['simpeg' => SORT_ASC, 'aktif' => SORT_ASC, 'eselonId' => SORT_ASC])
                            ->all()
                            ;

                            foreach($r60 as $r6){
                                $no = $no + 1;
                                $dt[] = [
                                    'NO' => $no,
                                    'ID' => $r6['id'],
                                    'HIERARKI' => '------------ '.$r6['namaUnor'],
                                    'NAMA' => $r6['namaUnor'],
                                    'ESELON-ID' => $r6['eselonId'],
                                    'NAMA-JABATAN' => $r6['namaJabatan'],
                                    'UNOR-ATASAN-ID' => $r6['diatasanId'],
                                    'UNOR-ATASAN' => $this->getUnor($r6['diatasanId']),
                                    'UNOR-SIMPEG' => $r6['simpeg'],
                                    'AKTIF' => $r6['aktif'],
                                ];
                            }
                        }
                    }
                }
            }
        }
        //Url::remember();
        return $this->renderPartial('all-print', [
            'dta' => $dt,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionGetRef()
    {
        $page = '/referensi/ref-unor';
        
        $mode = 'prod'; 
        $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0'.$page;

        SiasnIntegrasiConfig::refreshTokenSiasn($mode);

        $token_file = SiasnIntegrasiConfig::findOne($mode);
        $tokenSSO = $token_file['token_sso'];
        $tokenKey = $token_file['token_key'];
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey);
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        //return $result;    
        
        $unor = Json::decode($result, true);

        if($unor['code'] == 1){
            $dt = [];
            $no = 0; 
            $nb = 0;
            foreach($unor['data'] as $data){
                $ref = SiasnRefUnor::findOne($data['Id']);
                if($ref === null){
                    $no = $no + 1;
                    $ref = new SiasnRefUnor();
                    $ref['id'] = $data['Id'];
                    $ref['simpeg'] = null;
                }else $nb = $nb + 1;

                $ref['instansiId'] = $data['InstansiId'];
                $ref['diatasanId'] = $data['DiatasanId'];
                $ref['eselonId'] = $data['EselonId'];
                $ref['namaUnor'] = $data['NamaUnor'];
                $ref['namaJabatan'] = $data['NamaJabatan'];
                $ref['cepatKode'] = $data['CepatKode'];
                $ref['updated'] = date('Y-m-d H:i:s');

                $ref->save();
                    
            }
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS,
                'title' => 'Gagal',
                'text' => 'Data baru : '.$no.' & Data update : '.$nb,
                'showConfirmButton' => false,
                'timer' => 3000
            ]);   
        }else{
            $dt = [];

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR,
                'title' => 'Gagal',
                'text' => 'Tidak dapat mendownload',
                'showConfirmButton' => false,
                'timer' => 1000
            ]); 
        }

        return $this->redirect(['index']);
    }

    public function actionGetRefNew($page = '/referensi/ref-unor')
    {
        $mode = 'prod'; 
        $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0'.$page;

        SiasnIntegrasiConfig::refreshTokenSiasn($mode);

        $token_file = SiasnIntegrasiConfig::findOne($mode);
        $tokenSSO = $token_file['token_sso'];
        $tokenKey = $token_file['token_key'];
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey);
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        //return $result;    
        
        $unor = Json::decode($result, true);

        if($unor['code'] == 1){
            $dt = [];
            $no = 0; 
            $nb = 0;
            foreach($unor['data'] as $data){
                $ref = SiasnRefUnor::findOne($data['Id']);
                if($ref === null){
                    $no = $no + 1;
                    $ref = new SiasnRefUnor();
                    $ref['id'] = $data['Id'];
                    $ref['simpeg'] = null;

                    $ref['instansiId'] = $data['InstansiId'];
                    $ref['diatasanId'] = $data['DiatasanId'];
                    $ref['eselonId'] = $data['EselonId'];
                    $ref['namaUnor'] = $data['NamaUnor'];
                    $ref['namaJabatan'] = $data['NamaJabatan'];
                    $ref['cepatKode'] = $data['CepatKode'];
                    $ref['updated'] = date('Y-m-d H:i:s');

                    $ref->save();
                }
            }
            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS,
                'title' => 'Data baru tersimpan : '.$no,
                'showConfirmButton' => false,
                'timer' => 2000
            ]);   
        }else{
            $dt = [];

            Yii::$app->session->setFlash('position', [
                'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR,
                'title' => 'Tidak dapat mendownload',
                'showConfirmButton' => false,
                'timer' => 2000
            ]); 
        }

        return $this->redirect(['all-unor']);
    }

    /**
     * Displays a single SiasnRefUnor model.
     * @param string $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewX($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SiasnRefUnor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreateX()
    {
        $model = new SiasnRefUnor();

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
     * Updates an existing SiasnRefUnor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        if (Yii::$app->request->post()){
            $model['simpeg'] = Yii::$app->request->post()['simpeg'];
            $model['aktif'] = Yii::$app->request->post()['aktif'];

            if($model->save(false)){
                /*
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS,
                    'title' => 'Data berhasil disimpan',
                    'showConfirmButton' => false,
                    'timer' => 2000
                ]);
                */ 
            }else{
                Yii::$app->session->setFlash('position', [
                    'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR,
                    'title' => 'Data gagal disimpan',
                    'showConfirmButton' => false,
                    'timer' => 2000
                ]); 
            }            
        }

        return $this->redirect(Url::previous());
        
    }

    /**
     * Deletes an existing SiasnRefUnor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteX($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SiasnRefUnor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return SiasnRefUnor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiasnRefUnor::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public static function getUnor($x){

        $unor = SiasnRefUnor::find()->where(['id' => $x])->one();
        if($unor !== null) $nama = $unor['namaUnor'];
        else $nama = null;

        return $nama;
    }
}
