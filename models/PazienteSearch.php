<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Paziente;
use app\models\User;

/**
 * PazienteSearch represents the model behind the search form of `app\models\Paziente`.
 */
class PazienteSearch extends Paziente
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdPaziente', 'livello', 'IdLogoP', 'IdCaregiver'], 'integer'],
            [['nome'], 'safe'],
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
    {
        $user = Yii::$app->user->identity;
        $id = $user->getId();
        $query = Paziente::find()->where(['=','IdLogoP', $id ]);

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
            'IdPaziente' => $this->IdPaziente,
            'livello' => $this->livello,
            'IdLogoP' => $this->IdLogoP,
            'IdCaregiver' => $this->IdCaregiver,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome]);

        return $dataProvider;
    }
}
