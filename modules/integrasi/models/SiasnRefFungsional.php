<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_ref_fungsional".
 *
 * @property string $id
 * @property string|null $nama
 * @property int|null $bup
 * @property string|null $jenjang
 * @property string|null $jenjang_nama
 * @property string|null $simpeg
 */
class SiasnRefFungsional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_ref_fungsional';
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
            [['bup'], 'default', 'value' => null],
            [['bup'], 'integer'],
            [['id'], 'string', 'max' => 128],
            [['nama'], 'string', 'max' => 200],
            [['jenjang'], 'string', 'max' => 2],
            [['jenjang_nama'], 'string', 'max' => 10],
            [['simpeg'], 'string', 'max' => 15],
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
            'nama' => 'Nama',
            'bup' => 'Bup',
            'jenjang' => 'Jenjang',
            'jenjang_nama' => 'Jenjang Nama',
            'simpeg' => 'Simpeg',
        ];
    }
}
