<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "preskin_tambah_tpp_jab".
 *
 * @property string $id jenis_jab+kode_jab
 * @property int|null $jenis_jab
 * @property string|null $kode_jab kode_jab_fung_umum_struktural
 * @property float $beban
 * @property float $prestasi
 * @property float $kondisi
 * @property float $tempat
 * @property float $pol
 * @property float $langka
 * @property int $flag
 * @property string $update
 */
class PreskinTambahTppJab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preskin_tambah_tpp_jab';
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
            [['id'], 'required'],
            [['jenis_jab', 'flag'], 'default', 'value' => null],
            [['jenis_jab', 'flag'], 'integer'],
            [['beban', 'prestasi', 'kondisi', 'tempat', 'pol', 'langka'], 'number'],
            [['update'], 'safe'],
            [['id', 'kode_jab'], 'string', 'max' => 20],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_jab' => 'Jenis Jab',
            'kode_jab' => 'Kode Jab',
            'beban' => 'Beban',
            'prestasi' => 'Prestasi',
            'kondisi' => 'Kondisi',
            'tempat' => 'Tempat',
            'pol' => 'Pol',
            'langka' => 'Langka',
            'flag' => 'Flag',
            'update' => 'Update',
        ];
    }
}
