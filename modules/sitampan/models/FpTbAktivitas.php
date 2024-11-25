<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "tb_aktivitas".
 *
 * @property int $kode
 * @property string|null $aktivitas
 */
class FpTbAktivitas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_aktivitas';
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
            [['kode'], 'required'],
            [['kode'], 'default', 'value' => null],
            [['kode'], 'integer'],
            [['aktivitas'], 'string', 'max' => 255],
            [['kode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'aktivitas' => 'Aktivitas',
        ];
    }
}
