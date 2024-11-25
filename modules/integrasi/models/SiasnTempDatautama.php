<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_datautama".
 *
 * @property string|null $agama_id
 * @property string|null $alamat
 * @property string|null $email
 * @property string|null $email_gov
 * @property string|null $kabupaten_id
 * @property string|null $karis_karsu
 * @property string|null $kelas_jabatan
 * @property string|null $kpkn_id
 * @property string|null $lokasi_kerja_id
 * @property string|null $nomor_bpjs
 * @property string|null $nomor_hp
 * @property string|null $nomor_telpon
 * @property string|null $npwp_nomor
 * @property string|null $npwp_tanggal
 * @property string|null $pns_orang_id
 * @property string|null $tanggal_taspen
 * @property string|null $tapera_nomor
 * @property string|null $taspen_nomor
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempDatautama extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_datautama';
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
            [['npwp_tanggal', 'tanggal_taspen'], 'safe'],
            [['agama_id', 'kabupaten_id', 'kpkn_id', 'lokasi_kerja_id', 'pns_orang_id'], 'string', 'max' => 128],
            [['alamat', 'email', 'email_gov', 'karis_karsu', 'nomor_bpjs', 'tapera_nomor', 'taspen_nomor'], 'string', 'max' => 150],
            [['kelas_jabatan'], 'string', 'max' => 10],
            [['nomor_hp', 'nomor_telpon'], 'string', 'max' => 15],
            [['npwp_nomor'], 'string', 'max' => 17],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'agama_id' => 'Agama ID',
            'alamat' => 'Alamat',
            'email' => 'Email',
            'email_gov' => 'Email Gov',
            'kabupaten_id' => 'Kabupaten ID',
            'karis_karsu' => 'Karis Karsu',
            'kelas_jabatan' => 'Kelas Jabatan',
            'kpkn_id' => 'Kpkn ID',
            'lokasi_kerja_id' => 'Lokasi Kerja ID',
            'nomor_bpjs' => 'Nomor Bpjs',
            'nomor_hp' => 'Nomor Hp',
            'nomor_telpon' => 'Nomor Telpon',
            'npwp_nomor' => 'Npwp Nomor',
            'npwp_tanggal' => 'Npwp Tanggal',
            'pns_orang_id' => 'Pns Orang ID',
            'tanggal_taspen' => 'Tanggal Taspen',
            'tapera_nomor' => 'Tapera Nomor',
            'taspen_nomor' => 'Taspen Nomor',
        ];
    }

    // public static function primaryKey()
    // {
    //     return ["pns_orang_id"];
    // }
}
