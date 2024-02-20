<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%achievement}}`.
 */
class m220815_083556_create_achievement_table extends Migration
{
    public $tableName = '{{%achievement}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnTable($this->tableName, 'Достижения');
        $this->addCommentOnColumn($this->tableName, 'title', 'Название');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Дата создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Дата обновления');

        $this->createTable('{{%achievement_level}}', [
            'id' => $this->primaryKey(),
            'achievement_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'score' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk-achievement_level-achievement_id', '{{%achievement_level}}', 'achievement_id', $this->tableName, 'id');

        $this->addCommentOnTable('{{%achievement_level}}', 'Уровень достижения');
        $this->addCommentOnColumn('{{%achievement_level}}', 'title', 'Название');
        $this->addCommentOnColumn('{{%achievement_level}}', 'achievement_id', 'Достижение');
        $this->addCommentOnColumn('{{%achievement_level}}', 'score', 'Баллы');
        $this->addCommentOnColumn('{{%achievement_level}}', 'created_at', 'Дата создания');
        $this->addCommentOnColumn('{{%achievement_level}}', 'updated_at', 'Дата обновления');

        $this->createTable('{{%achievement_user}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'achievement_level_id' => $this->integer()->notNull(),
            'user_score' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk-achievement_user-user_id', '{{%achievement_user}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-achievement_user-achievement_level_id', '{{%achievement_user}}', 'achievement_level_id', '{{%achievement_level}}', 'id');

        $this->addCommentOnTable('{{%achievement_user}}', 'Достижения пользователя');
        $this->addCommentOnColumn('{{%achievement_user}}', 'user_id', 'Пользователь');
        $this->addCommentOnColumn('{{%achievement_user}}', 'achievement_level_id', 'Уровень достижения');
        $this->addCommentOnColumn('{{%achievement_user}}', 'user_score', 'Баллы пользователя');
        $this->addCommentOnColumn('{{%achievement_user}}', 'created_at', 'Дата создания');
        $this->addCommentOnColumn('{{%achievement_user}}', 'updated_at', 'Дата обновления');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%achievement_user}}');
        $this->dropTable('{{%achievement_level}}');
        $this->dropTable($this->tableName);
    }
}
