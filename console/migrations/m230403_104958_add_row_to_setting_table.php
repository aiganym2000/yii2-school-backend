<?php

use common\models\entity\Setting;
use yii\db\Migration;

/**
 * Class m230403_104958_add_row_to_setting_table
 */
class m230403_104958_add_row_to_setting_table extends Migration
{
    public $tableName = '{{%setting}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert($this->tableName, [
            'title' => 'Время в минутах, когда активен платеж',
            'key' => 'pay_limit',
            'value' => '20',
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $setting = Setting::findOne(['key' => 'pay_limit']);
        if ($setting)
            $setting->delete();
    }
}
