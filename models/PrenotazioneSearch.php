<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Prenotazione;

/**
 * PrenotazioneSearch represents the model behind the search form of `app\models\Prenotazione`.
 */
class PrenotazioneSearch extends Prenotazione
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'IdLogopedista', 'IdCaregiver'], 'integer'],
            [['data'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {   $user = Yii::$app->user->identity;
        $id = $user->getId();
        if($user->isCaregiver())
        {   
            $query = Prenotazione::find()->where(['=','IdCaregiver', $id ]);
        }
       else{
           $query =  Prenotazione::find()->where(['=','IdLogopedista', $id ]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'IdLogopedista' => $this->IdLogopedista,
            'IdCaregiver' => $this->IdCaregiver,
            'data' => $this->data,
        ]);

        return $dataProvider;
    }
}
