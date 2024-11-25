<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "vpresensi_adms".
 *
 * @property int $id
 * @property int $userid
 * @property string $badgenumber
 * @property string|null $name
 * @property string|null $SN
 * @property string $checktime
 * @property string|null $sensorid
 */
class VpresensiAdms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vpresensiadms';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db6');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userid'], 'integer'],
            [['userid', 'badgenumber', 'checktime'], 'required'],
            [['checktime'], 'safe'],
            [['badgenumber', 'SN'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 40],
            [['sensorid'], 'string', 'max' => 5],
            [['alias'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'badgenumber' => 'Badgenumber',
            'name' => 'Name',
            'SN' => 'Sn',
            'checktime' => 'Checktime',
            'sensorid' => 'Sensorid',
            'alias' => 'Alias',
        ];
    }
}
