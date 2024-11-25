<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "tb_alamat2".
 *
 * @property int $kodealamat
 * @property string|null $alamat
 * @property string|null $latitude
 * @property string|null $longitude
 */
class FpTbAlamat2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_alamat2';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db7');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kodealamat'], 'required'],
            [['kodealamat'], 'default', 'value' => 0],
            [['kodealamat'], 'integer'],
            [['alamat'], 'string', 'max' => 255],
            [['latitude', 'longitude'], 'string', 'max' => 50],
            [['tablokb'], 'string', 'max' => 10],
            [['kodealamat'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kodealamat' => 'Kode Lokasi',
            'alamat' => 'Lokasi',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'tablokb' => 'Unor',
        ];
    }

    public function getFpTablok()
    {
        return $this->hasOne(\app\modules\simpeg\models\EpsTablokb::className(), ['KOLOK' => 'tablokb']);
    } 
}
