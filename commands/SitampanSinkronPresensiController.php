<?php
// dijalankan per 30 menit
namespace app\commands;

use Yii;

use app\models\Fungsi;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\console\Controller;
use yii\console\ExitCode;
use  yii\web\Session;

use app\modules\sitampan\models\PresHarian;
use app\modules\sitampan\models\PreskinAsn;
use app\modules\sitampan\models\PreskinHariKerja;
use app\modules\sitampan\models\PreskinJamKerja;
use app\modules\sitampan\models\PreskinLibur;
use app\modules\sitampan\models\FpPresensiCheckinoutHp;
use app\modules\sitampan\models\PresKet;
use app\modules\sitampan\models\PreskinPresJenis;

class SitampanSinkronPresensisController extends Controller
{    
    public function actionIndex()
    {          
        $awal = date('d-m-Y H:i:s');

        $bulan = date('n')-1;
        $tahun = date('Y');
        $bulan = 4;

        if($bulan == 0){
            $bulan = 12;
            $tahun = $tahun-1; 
        }

        $presensi = PresHarian::find()
        ->where([
            'EXTRACT(month FROM DATE(tgl))' => $bulan, 
            'EXTRACT(year FROM DATE (tgl))' => $tahun,
        ])
        ->andWhere(['<','flag', 3])
        ->orderBy(['flag' => SORT_ASC, 'tablokb' => SORT_ASC, 'nip' => SORT_ASC, 'tgl' => SORT_ASC])
        //->limit(5000)
        ;

        if($presensi->count() != 0){
            $no = 0;
            foreach($presensi->all() as $pre){
                $no = $no + 1;
                $id = $pre['idpns'];
                $nip = $pre['nip'];
                $opd = $pre['tablokb'];
                $nama = $pre['nama'];
                $kode = $pre['kode'];
                $tgl = $pre['tgl'];
                $jd_masuk = $pre['jd_masuk'];
                $jd_pulang = $pre['jd_pulang'];
                $tjd_masuk = strtotime($jd_masuk);
                $tjd_pulang = strtotime($jd_pulang);
                $separo = floor(($tjd_pulang - $tjd_masuk)/120);

                echo "\n$no. Membaca : $opd $nip $nama \n";

                $cekFake = FpPresensiCheckinoutHp::find()
                    ->where([
                        'nip' => $nip,                    ])
                    ->andWhere(['between', 'checktime', "$tgl 00:00:00", "$tgl 23:59:59"])
                    ->andWhere(['ilike', 'light', 'fake'])
                ;  
                if($cekFake->count() == 0){
                    
                    $w_mas = date('Y-m-d H:i:s', strtotime('-1 hours', $tjd_masuk));
                    $w_pul = date('Y-m-d H:i:s', strtotime('+2 hours', $tjd_pulang));
                    
                    $masuk = FpPresensiCheckinoutHp::find()
                        ->select(['checktime', 'nip'])
                        ->where(['nip' => $nip])
                        ->andWhere(['between', 'checktime', $w_mas, $w_pul])
                        ->orderBy(['checktime' => SORT_ASC])
                        ->one();
                    ;  
                    
                    $pulang = FpPresensiCheckinoutHp::find()
                        ->select(['checktime', 'nip'])
                        ->where(['nip' => $nip])
                        ->andWhere(['between', 'checktime', $w_mas, $w_pul])
                        ->orderBy(['checktime' => SORT_DESC])
                        ->one();
                    ;   
    
                    if($masuk !== null){

                        $pr_masuk = $masuk['checktime'];
                        $pr_pulang = $pulang['checktime'];                   

                        $pre['pr_masuk'] = $pr_masuk;
                        $pre['pr_pulang'] = $pr_pulang;

                        $tpr_masuk = strtotime($pr_masuk);
                        $tpr_pulang = strtotime($pr_pulang);

                        $mnt_masuk = $tpr_masuk - $tjd_masuk;
                        $mm = floor($mnt_masuk/60);

                        if($mm >= 1){

                            $kd_pr_masuk = PreskinPresJenis::find()
                                ->where(['>=', 'selisih_waktu', $mm])
                                ->andWhere(['like', 'kd_presensi', 'TL'])
                                ->orderBy(['selisih_waktu' => SORT_ASC]);
                            
                            if($kd_pr_masuk->count() == 0){
                                $kd_pr_masuk = PreskinPresJenis::find()
                                ->where(['like', 'kd_presensi', 'TL'])
                                ->orderBy(['selisih_waktu' => SORT_DESC])
                                ->one();
                            }else $kd_pr_masuk = $kd_pr_masuk->one();
                                
                            $pre['mnt_masuk'] = $mnt_masuk;
                            $pre['kd_pr_masuk'] = $kd_pr_masuk['kd_presensi'];
                            $pre['pot_masuk'] = $kd_pr_masuk['persen_pot'];

                            if($mnt_masuk >= $separo) $pre['mnt_masuk'] = $separo;

                        }else{

                            $pre['mnt_masuk'] = 0;
                            $pre['kd_pr_masuk'] = 'H';
                            $pre['pot_masuk'] = 0;
                        }

                        $mnt_pulang = $tjd_pulang - $tpr_pulang;
                        $mp = floor($mnt_pulang/60);

                        if($mp >= 1){                        

                            $kd_pr_pulang = PreskinPresJenis::find()
                                ->where(['>=', 'selisih_waktu', $mp])
                                ->andWhere(['like', 'kd_presensi', 'PSW'])
                                ->orderBy(['selisih_waktu' => SORT_ASC]);

                            if($kd_pr_pulang->count() == 0){
                                $kd_pr_pulang = PreskinPresJenis::find()
                                ->where(['like', 'kd_presensi', 'PSW'])
                                ->orderBy(['selisih_waktu' => SORT_DESC])
                                ->one();
                            }else $kd_pr_pulang = $kd_pr_pulang->one();                            

                            $pre['mnt_pulang'] = $mnt_pulang;
                            $pre['kd_pr_pulang'] = $kd_pr_pulang['kd_presensi'];
                            $pre['pot_pulang'] = $kd_pr_pulang['persen_pot'];

                            if($mnt_pulang >= $separo) $pre['mnt_pulang'] = $separo;

                        }else{

                            $pre['mnt_pulang'] = 0;
                            $pre['kd_pr_pulang'] = 'H';
                            $pre['pot_pulang'] = 0;
                        }

                    }else{
                        $pre['mnt_masuk'] = $pre['mnt_pulang'] = $separo;
                        $pre['kd_pr_masuk'] = 'TMK'; 
                        $pre['kd_pr_pulang'] = 'TMK'; 
                        $pre['pot_masuk'] = 2.5;
                        $pre['pot_pulang'] = 2.5;
                    }

                    $pre['flag'] = 3;  
                    if($pre['mnt_masuk'] > 0 || $pre['mnt_pulang'] > 0) $pre['flag'] = 1;
                    $pre['updated'] = date('Y-m-d H:i:s');
                    $pre->save(false);    

                    echo "--- Berangkat : ".$pre['jd_masuk']." - ".$pre['pr_masuk']." : ".$pre['kd_pr_masuk']."\n";
                    echo "--- Pulang    : ".$pre['jd_pulang']." - ".$pre['pr_pulang']." : ".$pre['kd_pr_pulang']."\n";

                    $ket = PresKet::find()->where(['like', 'nip', $nip.';'])
                        ->andWhere(['and', ['<=', 'tgl_awal', $tgl],['>=', 'tgl_akhir', $tgl]])
                        ->orderBy(['jenis_ket' => SORT_DESC]);

                    if($ket->count() != 0){                    
                        $pre['mnt_masuk'] = 0;
                        $pre['mnt_pulang'] = 0;
                        $pre['kd_pr_masuk'] = $ket->one()['jenisSuket']['kode'];
                        $pre['kd_pr_pulang'] = $ket->one()['jenisSuket']['kode']; 
                        $pre['pot_masuk'] = 0;
                        $pre['pot_pulang'] = 0;
                        $pre['flag'] = 3; 
                        $pre['updated'] = date('Y-m-d H:i:s');
                        $pre->save(false);  

                    echo "--- Keterangan Absen : ".$pre['kd_pr_masuk']."\n";

                    }
                }else{
                    $pre['mnt_masuk'] = $pre['mnt_pulang'] = $separo;
                    $pre['kd_pr_masuk'] = 'TMK'; 
                    $pre['kd_pr_pulang'] = 'TMK'; 
                    $pre['pot_masuk'] = 2.5;
                    $pre['pot_pulang'] = 2.5;
                    $pre['flag'] = 3; 
                    $pre['updated'] = date('Y-m-d H:i:s');
                    $pre->save(false); 

                    echo "--- Terdeteksi Fake GPS "."\n";
                } 
            }
        }else{
            echo "\n Data tidak ditemukan \n";
        } 
        
        $akhir = date('d-m-Y H:i:s');
        echo "\n awal : ".$awal." === akhir : ".$akhir." \n";
    }
}