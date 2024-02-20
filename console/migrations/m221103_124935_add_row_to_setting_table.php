<?php

use common\models\entity\Setting;
use yii\db\Migration;

/**
 * Class m221103_124935_add_row_to_setting_table
 */
class m221103_124935_add_row_to_setting_table extends Migration
{
    public $tableName = '{{%setting}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert($this->tableName, [
            'title' => 'Скидка для полного доступа',
            'key' => 'full_access_percent',
            'value' => '10',
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $setting = Setting::findOne(['key' => 'full_access_percent']);
        if ($setting)
            $setting->delete();
    }
}
