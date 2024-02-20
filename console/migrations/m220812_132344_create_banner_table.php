<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%banner}}`.
 */
class m220812_132344_create_banner_table extends Migration
{
    public $tableName = '{{%banner}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'path' => $this->string()->notNull(),
            'size' => $this->integer()->notNull(),
            'position' => $this->integer(),
            'zone' => $this->smallInteger(),
            'url' => $this->string(),
            'status' => $this->tinyInteger()->defaultValue(0)->notNull(),
            'published_at' => $this->dateTime(),
            'created_user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->addForeignKey('fk-created_user-banner', $this->tableName, 'created_user_id', '{{%user}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Баннеры');
        $this->addCommentOnColumn($this->tableName, 'title', 'Заголовок');
        $this->addCommentOnColumn($this->tableName, 'path', 'Путь');
        $this->addCommentOnColumn($this->tableName, 'size', 'Размер');
        $this->addCommentOnColumn($this->tableName, 'position', 'Позиция');
        $this->addCommentOnColumn($this->tableName, 'zone', 'Зона');
        $this->addCommentOnColumn($this->tableName, 'url', 'Ссылка');
        $this->addCommentOnColumn($this->tableName, 'status', 'Статус');
        $this->addCommentOnColumn($this->tableName, 'published_at', 'Дата публикации');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Дата создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Дата изменения');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
