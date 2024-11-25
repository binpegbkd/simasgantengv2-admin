<?php

namespace app\commands;

use Yii;

use yii\helpers\Json;
use yii\helpers\ArrayHelper;

use yii\console\Controller;
use yii\console\ExitCode;

use app\modules\siasn\models\SapkDataPns;
use app\modules\siasn\models\SiasnRefUnor;
use app\models\SiasnIntegrasiConfig;
use app\models\Tbllog;

/**
 * SapkDataPnsController implements the CRUD actions for SapkDataPns model.
 */
class GetDataUtamaSiasnController extends Controller
{
    public function actionIndex()
    {
        date_default_timezone_set('Asia/Jakarta');
        $mulai =  date('d-m-Y H:i:s');

        $sapk = SapkDataPns::find()
            ->select(['id', 'nipBaru', 'nama', 'flag', 'updated'])
            ->where(['<', 'updated', '2023-09-13 17:00:00'])
            ->orderBy(['nipBaru' => SORT_ASC])
            ->limit(250)
        ;

        $no = 0;
        foreach($sapk->all() as $sap){
            $no = $no + 1;
            $nip = $sap['nipBaru'];
            $nama = $sap['nama'];

            echo "$no. Update Data NIP : $nip - $nama "; 
            $siasn = ['code' => 0, 'data'=> '', 'message' => null];
            $siasn = SiasnIntegrasiConfig::getDataSiasn($nip, 'prod', '/pns/data-utama/');

            /*
            if($siasn['message'] !== null){
               echo "----". $siasn['message'],
               exit;
            }
            */ 

            if($siasn['code'] != 1){
                $siasn['data'] = '';
                $sap['updated']  = date('Y-m-d H:i:s');  
                $sap['flag'] = 9;
                $sap->save();
                echo "---- Data tidak ditemukan \n"; 

            }else{
                $data = $siasn['data'];
                $data['updated']  = date('Y-m-d H:i:s');  

                //$sapkpns = SapkDataPns::findOne($data['id']);
                $sapkpns = SapkDataPns::find()->where(['nipBaru' => $data['nipBaru']])->one();
                if($sapkpns === null) $sapkpns = new SapkDataPns();

                foreach($data as $attr => $value){
                    if($data["$attr"] == 'null') $data["$attr"] = NULL;
                        $sapkpns["$attr"] = $data["$attr"];
                }

                $log = new Tbllog();
                $log['id'] = $sapkpns['id'];
                $log['oleh'] = 'command/ cron';
                $log['data'] = 'data-utama';
                $log['aksi'] = 'all';
                
                if($sapkpns->save(false) && $log->save(false)){
                    echo "---- Data berhasil disimpan \n";
                }else{
                    echo "---- Data GAGAL disimpan  \n";
                }

            }
            echo "\n";
        }
        echo "\n";
        echo $mulai." --- ".date('Y-m-d H:i:s')."\n \n";
    }
}
