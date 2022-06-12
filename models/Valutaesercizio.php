<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "valutaesercizio".
 *
 * @property int $IdCaregiver
 * @property int $IdEsercizio
 * @property int $valutazione
 *
 * @property User $idCaregiver
 * @property User $idCaregiver0
 * @property Esercizio $idEsercizio
 * @property Esercizio $idEsercizio0
 */
class Valutaesercizio extends \yii\db\ActiveRecord 
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'valutaesercizio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdEsercizio', 'valutazione'], 'required'],
            [['IdCaregiver', 'IdEsercizio', 'valutazione'], 'integer'],
            [['IdCaregiver', 'IdEsercizio'], 'unique', 'targetAttribute' => ['IdCaregiver', 'IdEsercizio']],
            [['IdCaregiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdCaregiver' => 'IdUser']],
            [['IdEsercizio'], 'exist', 'skipOnError' => true, 'targetClass' => Esercizio::className(), 'targetAttribute' => ['IdEsercizio' => 'IdEsercizio']],
            [['IdCaregiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdCaregiver' => 'IdUser']],
            [['IdEsercizio'], 'exist', 'skipOnError' => true, 'targetClass' => Esercizio::className(), 'targetAttribute' => ['IdEsercizio' => 'IdEsercizio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdCaregiver' => 'Id Caregiver',
            'IdEsercizio' => 'Esercizio da valutare',
            'valutazione' => 'Mia Valutazione',
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'IdCaregiver',
                'updatedByAttribute' => false,
            ],
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
     * Gets query for [[IdCaregiver0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCaregiver0()
    {
        return $this->hasOne(User::className(), ['IdUser' => 'IdCaregiver']);
    }

    /**
     * Gets query for [[IdEsercizio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEsercizio()
    {
        return $this->hasOne(Esercizio::className(), ['IdEsercizio' => 'IdEsercizio']);
    }

    /**
     * Gets query for [[IdEsercizio0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEsercizio0()
    {
        return $this->hasOne(Esercizio::className(), ['IdEsercizio' => 'IdEsercizio']);
    }
}
