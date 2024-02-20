<?php


namespace backend\modules\admin\models;

use common\models\entity\Promocode;
use common\models\entity\Transaction;
use Yii;
use yii\base\Model;

class PromoForm extends Model
{
    public $promo;

    public function rules()
    {
        return [
            [['promo'], 'required'],
            [['promo'], 'string'],
            [['promo'], 'trim'],
        ];
    }

    public function delete()
    {
        if (!$this->validate())
            return false;

        $promocodes = Promocode::findAll(['promo' => $this->promo, 'status' => Promocode::STATUS_ACTIVE]);
        foreach ($promocodes as $promocode) {
            $transaction = Transaction::findOne(['status' => Transaction::STATUS_PAID, 'promocode_id' => $promocode->id]);
            if (!$transaction)
                $promocode->delete();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'promo' => Yii::t('app', 'PROMO'),
        ];
    }
}