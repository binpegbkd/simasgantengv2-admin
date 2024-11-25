<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "log_sinkron".
 *
 * @property string $adms
 * @property int $id
 * @property string $updated
 */
class FpLogSinkron extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_sinkron';
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
            [['adms'], 'required'],
            [['id'], 'default', 'value' => null],
            [['id'], 'integer'],
            [['updated'], 'safe'],
            [['adms'], 'string', 'max' => 1],
            [['adms'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adms' => 'Adms',
            'id' => 'ID',
            'updated' => 'Updated',
        ];
    }
}
