<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%achievement}}`.
 */
class m220927_114422_create_achievement_table extends Migration
{
    public $tableName = '{{%achievement}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%achievement_user}}');
        $this->dropTable('{{%achievement_level}}');
        $this->dropTable($this->tableName);

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'letter' => $this->string(1)->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createTable('{{%achievement_user}}', [
            'id' => $this->primaryKey(),
            'achievement_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'for' => $this->string(),
            'type' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-achievement_user-user_id', '{{%achievement_user}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-achievement_user-achievement_id', '{{%achievement_user}}', 'achievement_id', '{{%achievement}}', 'id');

        $date = date('Y-m-d H:i:s');
        $this->batchInsert($this->tableName, ['id', 'letter', 'title', 'description', 'created_at', 'updated_at'],
            [
                [1, 'A', 'Первая награда', 'Получи их все', $date, $date],
                [2, 'B', 'Вторая награда', 'Получи их все', $date, $date],
                [3, 'C', 'Третья награда', 'Получи их все', $date, $date],
                [4, 'D', 'Четвертая награда', 'Получи их все', $date, $date],
                [5, 'E', 'Пятая награда', 'Получи их все', $date, $date],
                [6, 'F', 'Шестая награда', 'Получи их все', $date, $date],
                [7, 'G', 'Седьмая награда', 'Получи их все', $date, $date],
                [8, 'H', 'Восьмая награда', 'Получи их все', $date, $date],
                [9, 'I', 'Девятая награда', 'Получи их все', $date, $date],
                [10, 'G', 'Десятая награда', 'Получи их все', $date, $date],
                [11, 'K', 'Одиннадцатая награда', 'Получи их все', $date, $date],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%achievement_user}}');
        $this->dropTable($this->tableName);

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
}
