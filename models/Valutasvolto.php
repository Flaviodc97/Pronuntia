<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "valutasvolto".
 *
 * @property int $ID
 * @property int $IdLogoP
 * @property int $IdSvolto
 * @property int $valutazione
 *
 * @property User $idLogoP
 * @property Svolgeesercizio $idSvolto
 */
class Valutasvolto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'valutasvolto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdSvolto', 'valutazione'], 'required'],
            [['IdLogoP', 'IdSvolto', 'valutazione'], 'integer'],
            [['IdLogoP'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdLogoP' => 'IdUser']],
            [['IdSvolto'], 'exist', 'skipOnError' => true, 'targetClass' => Svolgeesercizio::className(), 'targetAttribute' => ['IdSvolto' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'IdLogoP' => 'Id Logo P',
            'IdSvolto' => 'Id Svolto',
            'valutazione' => 'Valutazione',
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'IdLogoP',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * Gets query for [[IdLogoP]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdLogoP()
    {
        return $this->hasOne(User::className(), ['IdUser' => 'IdLogoP']);
    }

    /**
     * Gets query for [[IdSvolto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdSvolto()
    {
        return $this->hasOne(Svolgeesercizio::className(), ['ID' => 'IdSvolto']);
    }
}
