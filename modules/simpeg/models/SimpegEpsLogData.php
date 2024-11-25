<?php

namespace app\modules\simpeg\models;

use Yii;

/**
 * This is the model class for table "eps_log_data".
 *
 * @property int $id
 * @property string $tgl
 * @property string $tabel
 * @property string $nip
 * @property string|null $aksi
 * @property int $flag
 */
class SimpegEpsLogData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eps_log_data';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db3');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl', 'tabel', 'nip'], 'required'],
            [['tgl'], 'safe'],
            [['flag'], 'integer'],
            [['tabel'], 'string', 'max' => 100],
            [['nip'], 'string', 'max' => 30],
            [['aksi'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tgl' => 'Tgl',
            'tabel' => 'Tabel',
            'nip' => 'Nip',
            'aksi' => 'Aksi',
            'flag' => 'Flag',
        ];
    }
}
