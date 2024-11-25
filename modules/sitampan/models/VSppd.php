<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "v_sppd".
 *
 * @property string|null $no_surat
 * @property string|null $tgl_surat
 * @property string|null $tgl_berangkat
 * @property string|null $tgl_kembali
 * @property string|null $perihal
 * @property string|null $lokasi
 * @property string|null $nip9
 * @property string|null $nip
 */
class VSppd extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_sppd';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db4');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl_surat', 'tgl_berangkat', 'tgl_kembali'], 'safe'],
            [['no_surat', 'lokasi', 'nip9', 'nip'], 'string', 'max' => 255],
            [['perihal'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_surat' => 'No Surat',
            'tgl_surat' => 'Tgl Surat',
            'tgl_berangkat' => 'Tgl Berangkat',
            'tgl_kembali' => 'Tgl Kembali',
            'perihal' => 'Perihal',
            'lokasi' => 'Lokasi',
            'nip9' => 'Nip9',
            'nip' => 'Nip',
        ];
    }
}
