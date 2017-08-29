<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yuncms\user\models\User;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $email;

    /** @var int */
    public $created_at;

    /** @var string */
    public $registration_ip;


    /** @inheritdoc */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'email', 'registration_ip','created_at'], 'safe'],
            ['created_at', 'default', 'value' => null],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'username' => Yii::t('user', 'Username'),
            'email' => Yii::t('user', 'Email'),
            'created_at' => Yii::t('user', 'Registration time'),
            'registration_ip' => Yii::t('user', 'Registration ip'),
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()->orderBy(['id'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['registration_ip' => $this->registration_ip]);

        return $dataProvider;
    }
}
