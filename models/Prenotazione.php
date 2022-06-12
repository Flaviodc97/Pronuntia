<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "prenotazione".
 *
 * @property int $ID
 * @property int $IdLogopedista
 * @property int $IdCaregiver
 * @property string|null $data
 *
 * @property User $idCaregiver
 * @property User $idLogopedista
 */
class Prenotazione extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prenotazione';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdLogopedista', 'IdCaregiver','data'], 'required'],
            [['IdLogopedista', 'IdCaregiver'], 'integer'],
            ['data', 'string'],
            [['IdLogopedista'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdLogopedista' => 'IdUser']],
            [['IdCaregiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdCaregiver' => 'IdUser']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'IdLogopedista' => 'Id Logopedista',
            'IdCaregiver' => 'Id Caregiver',
            'data' => 'Data',
        ];
    }

    /**
     * Gets query for [[IdCaregiver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCaregiver()
    {
        return $this->hasOne(User::className(), ['IdUser' => 'IdCaregiver']);
    }

    /**
     * Gets query for [[IdLogopedista]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdLogopedista()
    {
        return $this->hasOne(User::className(), ['IdUser' => 'IdLogopedista']);
    }
}
