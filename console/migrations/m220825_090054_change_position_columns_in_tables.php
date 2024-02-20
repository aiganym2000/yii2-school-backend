<?php

use common\models\entity\Banner;
use common\models\entity\Lesson;
use common\models\entity\Question;
use yii\db\Migration;

/**
 * Class m220825_090054_change_position_columns_in_tables
 */
class m220825_090054_change_position_columns_in_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%banner}}', 'position');
        $this->dropColumn('{{%lesson}}', 'position');
        $this->dropColumn('{{%question}}', 'position');

        $this->addColumn('{{%banner}}', 'position', $this->integer()->null());
        $this->addColumn('{{%lesson}}', 'position', $this->integer()->null());
        $this->addColumn('{{%question}}', 'position', $this->integer()->null());

        $banners = Banner::find()->all();
        $i = 1;
        foreach ($banners as $banner) {
            $banner->position = $i;
            $banner->save();
            $i++;
        }

        $lessons = Lesson::find()->all();
        $i = 1;
        foreach ($lessons as $lesson) {
            $lesson->position = $i;
            $lesson->save();
            $i++;
        }

        $questions = Question::find()->all();
        $i = 1;
        foreach ($questions as $question) {
            $question->position = $i;
            $question->save();
            $i++;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%banner}}', 'position');
        $this->dropColumn('{{%lesson}}', 'position');
        $this->dropColumn('{{%question}}', 'position');

        $this->addColumn('{{%banner}}', 'position', $this->integer());
        $this->addColumn('{{%lesson}}', 'position', $this->integer()->notNull());
        $this->addColumn('{{%question}}', 'position', $this->integer()->notNull());
    }
}
