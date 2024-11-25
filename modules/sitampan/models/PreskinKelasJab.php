<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "preskin_kelas_jab".
 *
 * @property string $id jenis+kode
 * @property int $jenis_jab simpeg
 * @property string $kode_jab simpeg
 * @property int $eselon
 * @property int $kelas
 * @property int $kelas_tpp
 * @property int $flag
 * @property string $updated
 */
class PreskinKelasJab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preskin_kelas_jab';
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
            [['id', 'jenis_jab', 'kode_jab'], 'required'],
            [['jenis_jab', 'eselon', 'kelas', 'kelas_tpp', 'flag'], 'default', 'value' => null],
            [['jenis_jab', 'eselon', 'kelas', 'kelas_tpp', 'flag'], 'integer'],
            [['updated'], 'safe'],
            [['id'], 'string', 'max' => 25],
            [['kode_jab'], 'string', 'max' => 20],
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
            'eselon' => 'Eselon',
            'kelas' => 'Kelas',
            'kelas_tpp' => 'Kelas Tpp',
            'flag' => 'Flag',
            'updated' => 'Updated',
        ];
    }
}
