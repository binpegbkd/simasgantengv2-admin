<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_cpns".
 *
 * @property string|null $id
 * @property string|null $kartu_pegawai
 * @property string|null $nama_jabatan_angkat_cpns
 * @property string|null $nomor_dokter_pns
 * @property string|null $nomor_sk_cpns
 * @property string|null $nomor_sk_pns
 * @property string|null $nomor_spmt
 * @property string|null $nomor_sttpl
 * @property string|null $pertek_cpns_pns_l2th_nomor
 * @property string|null $pertek_cpns_pns_l2th_tanggal
 * @property string|null $pns_orang_id
 * @property string|null $status_cpns_pns
 * @property string|null $tanggal_dokter_pns
 * @property string|null $tgl_sk_cpns
 * @property string|null $tgl_sk_pns
 * @property string|null $tgl_sttpl
 * @property string|null $tmt_pns
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempCpns extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_cpns';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pertek_cpns_pns_l2th_tanggal', 'tanggal_dokter_pns', 'tgl_sk_cpns', 'tgl_sk_pns', 'tgl_sttpl', 'tmt_pns'], 'safe'],
            // [['flag'], 'default', 'value' => null],
            // [['flag'], 'integer'],
            [['id', 'pns_orang_id'], 'string', 'max' => 128],
            [['kartu_pegawai', 'nama_jabatan_angkat_cpns', 'nomor_dokter_pns', 'nomor_sk_cpns', 'nomor_sk_pns', 'nomor_spmt', 'nomor_sttpl', 'pertek_cpns_pns_l2th_nomor'], 'string', 'max' => 100],
            [['status_cpns_pns'], 'string', 'max' => 50],
            // [['by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kartu_pegawai' => 'Kartu Pegawai',
            'nama_jabatan_angkat_cpns' => 'Nama Jabatan Angkat Cpns',
            'nomor_dokter_pns' => 'Nomor Dokter Pns',
            'nomor_sk_cpns' => 'Nomor Sk Cpns',
            'nomor_sk_pns' => 'Nomor Sk Pns',
            'nomor_spmt' => 'Nomor Spmt',
            'nomor_sttpl' => 'Nomor Sttpl',
            'pertek_cpns_pns_l2th_nomor' => 'Pertek Cpns Pns L2th Nomor',
            'pertek_cpns_pns_l2th_tanggal' => 'Pertek Cpns Pns L2th Tanggal',
            'pns_orang_id' => 'Pns Orang ID',
            'status_cpns_pns' => 'Status Cpns Pns',
            'tanggal_dokter_pns' => 'Tanggal Dokter Pns',
            'tgl_sk_cpns' => 'Tgl Sk Cpns',
            'tgl_sk_pns' => 'Tgl Sk Pns',
            'tgl_sttpl' => 'Tgl Sttpl',
            'tmt_pns' => 'Tmt Pns',
            // 'flag' => 'Flag',
            // 'updated' => 'Updated',
            // 'by' => 'By',
        ];
    }

    // public static function primaryKey()
    // {
    //     return ["id"];
    // }
}
