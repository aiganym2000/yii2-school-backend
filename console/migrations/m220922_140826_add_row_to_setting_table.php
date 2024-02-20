<?php

use common\models\entity\Setting;
use yii\db\Migration;

/**
 * Class m220922_140826_add_row_to_setting_table
 */
class m220922_140826_add_row_to_setting_table extends Migration
{
    public $tableName = '{{%setting}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert($this->tableName, [
            'title' => 'Проходной процент',
            'key' => 'percent_limit',
            'value' => '50',
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $setting = Setting::findOne(['key' => 'percent_limit']);
        if ($setting)
            $setting->delete();
    }
}
