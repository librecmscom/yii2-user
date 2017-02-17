<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user_authentications".
 *
 * @property integer $user_id
 * @property string $real_name
 * @property string $id_card
 * @property string $id_card_image
 * @property int $status
 * @property string $failed_reason
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Authentication extends ActiveRecord
{
    /**
     * @var \yii\web\UploadedFile 头像上传字段
     */
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_authentications}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior'
            ],

            'blameable' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'create' => ['real_name', 'id_card', 'imageFile'],
            'update' => ['real_name', 'id_card', 'imageFile'],
            'verify' => ['status'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['real_name', 'id_card', 'imageFile'], 'required', 'on' => ['create', 'update']],
            [['real_name', 'id_card'], 'filter', 'filter' => 'trim'],
            [['imageFile'], 'file', 'extensions' => 'gif,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('user', 'File has to be smaller than 2MB')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'real_name' => Yii::t('user', 'Real Name'),
            'id_card' => Yii::t('user', 'Id Card'),
            'imageFile' => Yii::t('user', 'Id Card Image'),
            'id_card_image' => Yii::t('user', 'Id Card Image'),
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }
}