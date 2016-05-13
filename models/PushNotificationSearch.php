<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PushNotification;

/**
 * PushNotificationSearch represents the model behind the search form about `app\models\PushNotification`.
 */
class PushNotificationSearch extends PushNotification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'resource_id', 'status', 'creator', 'jpush_msg_id'], 'integer'],
            [['device_type', 'android_content', 'ios_content', 'android_title', 'create_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = PushNotification::find();

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
            'id' => $this->id,
            'resource_id' => $this->resource_id,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'creator' => $this->creator,
            'jpush_msg_id' => $this->jpush_msg_id,
        ]);

        $query->andFilterWhere(['like', 'device_type', $this->device_type])
            ->andFilterWhere(['like', 'android_content', $this->android_content])
            ->andFilterWhere(['like', 'ios_content', $this->ios_content])
            ->andFilterWhere(['like', 'android_title', $this->android_title]);

        return $dataProvider;
    }
}
