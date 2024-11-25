<?php

namespace app\modules\integrasi\controllers;

use Yii;
use app\modules\integrasi\models\SiasnAnomali;
use app\modules\integrasi\models\SiasnAnomaliSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * SiasnAnomaliController implements the CRUD actions for SiasnAnomali model.
 */
class SiasnAnomaliController extends Controller
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
     * Lists all SiasnAnomali models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SiasnAnomaliSearch();        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(empty(Yii::$app->request->get()))
        $dataProvider->query->andFilterWhere(['flag' => '0']);
        $dataProvider->query->orderBy(['skJabatan' => SORT_DESC]);

        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SiasnAnomali model.
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
     * Creates a new SiasnAnomali model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SiasnAnomali();

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
     * Updates an existing SiasnAnomali model.
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
     * Deletes an existing SiasnAnomali model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
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
     * Finds the SiasnAnomali model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SiasnAnomali the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiasnAnomali::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset(){
        return $this->redirect(['index']);
    }

    public function actionUploadJab($id)
    {
        $sess = Yii::$app->session;
        $model = $this->findModel($id);
        $file = $model['skJabatan'];

        if (Yii::$app->request->post()) {
            $nf = UploadedFile::getInstance($model,'skJabatan');

            if($nf){
                $path = 'unggahan';
                $filename = $model['nipBaru'].'_SK_JABATAN.pdf';
                if (file_exists($path)) FileHelper::createDirectory($path);

                $file_update = $path.'/'.$filename;
                if($nf->saveAs($file_update)) {
                    $model['flag'] = 0;
                    $model['skJabatan'] = $filename;
                    $model['updated'] = date('Y-m-d H:i:s');
                    $model['updateBy'] = $sess['nip'].'-'.$sess['nama'];

                    if($model->save(false)){
                        Yii::$app->session->setFlash('position', [
                            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                            'title' => 'Berhasil',
                            'text' => 'Data Berhasil disimpan !!!',
                            'showConfirmButton' => false,
                            'timer' => 2000
                        ]);
                    }else{
                        Yii::$app->session->setFlash('position', [
                            'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                            'title' => 'Data gagal disimpan',
                            'showConfirmButton' => false,
                            'timer' => 2000
                        ]);
                    }
                }else{
                    Yii::$app->session->setFlash('position', [
                        'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                        'title' => 'Data gagal disimpan',
                        'showConfirmButton' => false,
                        'timer' => 2000
                    ]);
                }

                return $this->redirect(Url::previous());
            }            
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('upload-jab', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('upload-jab', [
                'model' => $model, 
            ]);
        } 
    } 

    public function actionUploadKp($id)
    {
        $sess = Yii::$app->session;
        $model = $this->findModel($id);
        $file = $model['skKP'];

        if (Yii::$app->request->post()) {
            $nf = UploadedFile::getInstance($model,'skKP');

            if($nf){
                $path = 'unggahan';
                $filename = $model['nipBaru'].'_SK_KP.pdf';
                if (file_exists($path)) FileHelper::createDirectory($path);

                $file_update = $path.'/'.$filename;
                if($nf->saveAs($file_update)) {
                    $model['flag'] = 0;
                    $model['skJabatan'] = $filename;
                    $model['updated'] = date('Y-m-d H:i:s');
                    $model['updateBy'] = $sess['nip'].'-'.$sess['nama'];

                    if($model->save(false)){
                        Yii::$app->session->setFlash('position', [
                            'icon' => \dominus77\sweetalert2\Alert::TYPE_SUCCESS, 
                            'title' => 'Berhasil',
                            'text' => 'Data Berhasil disimpan !!!',
                            'showConfirmButton' => false,
                            'timer' => 2000
                        ]);
                    }else{
                        Yii::$app->session->setFlash('position', [
                            'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                            'title' => 'Data gagal disimpan',
                            'showConfirmButton' => false,
                            'timer' => 2000
                        ]);
                    }
                }else{
                    Yii::$app->session->setFlash('position', [
                        'icon' => \dominus77\sweetalert2\Alert::TYPE_ERROR, 
                        'title' => 'Data gagal disimpan',
                        'showConfirmButton' => false,
                        'timer' => 2000
                    ]);
                }

                return $this->redirect(Url::previous());
            }            
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('upload-kp', [
                'model' => $model, 
            ]);
        }else{
            return $this->render('upload-kp', [
                'model' => $model, 
            ]);
        }         
    } 

    public function actionPreviewJab($id) {

        Yii::$app->response->redirect('http://simasganteng-app.brebeskab.go.id//integrasi/siasn-anomali/preview-jab-from?file='.$id, 301);

        //$model = $this->findModel($id);
        //$filename = $model['skJabatan'];

        // $fp = fopen('http://simasganteng-app.bkd.brebeskab.go.id/unggahan/'.$filename, 'r');
        // $fpl = fopen('unggahan/'.$filename, 'w');
        // while(!feof($fp)){
        //     fwrite($fpl, fread($fp, 1024));
        // }
        // fclose($fp);
        // fclose($fpl);

        // $content = file_get_contents('http://simasganteng-app.bkd.brebeskab.go.id/unggahan/'.$filename);
        // $lok = 'unggahan/'.$filename;
        // $fp = file_put_contents($lok, $content);
        // // fwrite($fp, $content);
        // $fpo = fopen($lok, "w");
        // fclose($fpo);

        // $url = 'http://simasganteng-app.bkd.brebeskab.go.id/unggahan/'.$filename;
        // $saveto = 'unggahan/'.$filename;
        // $ch = curl_init ($url);
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        // $raw=curl_exec($ch);
        // curl_close ($ch);
        // if(file_exists($saveto)){
        //     unlink($saveto);
        // }
        // $fp = fopen($saveto,"wb");
        // fwrite($fp, $raw);
        // fclose($fp);

        // $url = 'http://simasganteng-app.bkd.brebeskab.go.id/unggahan/'.$filename;
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // $fp = fopen('unggahan/'.$filename, 'wb');
        // curl_setopt($ch, CURLOPT_FILE, $fp);
        // curl_exec($ch);
        // curl_close($ch);
        // fclose($fp);

        // $path = 'unggahan/'.$filename;
        // $pdf = file_get_contents($path);
        // header('Content-Type: application/pdf');
        // header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
        // header('Pragma: public');
        // header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        // header('Content-Length: '.strlen($pdf));
        // header('Content-Disposition: inline; filename="'.basename($path).'";');
        // ob_clean(); 
        // flush(); 
        // echo $pdf;
        
        
        // $url =  'http://simasganteng-app.bkd.brebeskab.go.id/unggahan/'.$filename; 
        // $file_loc = 'http://simasganteng-bkpsdmd.brebeskab.go.id/unggahan/'.$filename;
        // // $ch = curl_init($url); 
        // // $dir = './'; 
        // // $file_name = basename($url); 
        // // $save_file_loc = $file_loc; 
        // // $fp = fopen($save_file_loc, 'w'); 
        // // curl_setopt($ch, CURLOPT_FILE, $fp); 
        // // curl_setopt($ch, CURLOPT_HEADER, 0); 
        // // curl_exec($ch); 
        // // curl_close($ch); 
        // // fclose($fp); 

        // $content = file_get_contents($url);
        // //simpan di server hosting baru.
        // $fp = fopen($file_loc, "w");
        // fwrite($fp, $content);
        // fclose($fp);
        
        // $model = $this->findModel($id);
        // $filename = $model['skJabatan'];
        // $url = 'http://simasganteng-app.bkd.brebeskab.go.id';
        // $path = $url.'/unggahan/'.$filename;


        // $pdf = file_get_contents($path);
        // $fp = fopen($pdf, "wb");
        // fwrite($fp, $pdf);

        // header('Content-Type: application/pdf');
        // header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
        // header('Pragma: public');
        // header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        // header('Content-Length: '.strlen($pdf));
        // header('Content-Disposition: inline; filename="'.basename($path).'";');
        // ob_clean(); 
        // flush(); 
        // echo $pdf;
        
    //     header('Content-Type: application/pdf');
    //     header('Content-Disposition: inline; filename='.$path);
    //     header('Content-Transfer-Encoding: binary');
    //     header('Accept-Ranges: bytes');
        
    //     return readfile($path);
    // //     if(Yii::$app->request->isAjax) {
    // //         return $this->renderAjax('preview-jab', [
    // //             'path' => $path, 
    // //         ]);
    // //     }else{
    // //         return $this->render('preview-jab', [
    // //             'path' => $path, 
    // //         ]);
    // //     } 
    }

    public function actionPreviewKp($id) {

        $model = $this->findModel($id);
        $filename = $model['skJabatan'];
        $path = 'unggahan/'.$filename;
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename='.$path);
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        
        return readfile($path);
    }
}
