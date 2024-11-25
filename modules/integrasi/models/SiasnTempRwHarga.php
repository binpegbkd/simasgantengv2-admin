<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_rw_harga".
 *
 * @property string|null $hargaId
 * @property string|null $id
 * @property string|null $pnsOrangId
 * @property string|null $skDate
 * @property string|null $skNomor
 * @property int|null $tahun
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempRwHarga extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_rw_harga';
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
            [['skDate'], 'safe'],
            [['tahun'], 'default', 'value' => 0],
            [['tahun'], 'integer'],
            [['hargaId', 'id', 'pnsOrangId'], 'string', 'max' => 128],
            [['skNomor'], 'string', 'max' => 100],
            // [['by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hargaId' => 'ID Penghargaan',
            'id' => 'ID',
            'pnsOrangId' => 'Pns Orang ID',
            'skDate' => 'Tgl Sk',
            'skNomor' => 'Nomor Sk',
            'tahun' => 'Tahun',
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
