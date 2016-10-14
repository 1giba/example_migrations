<?php

use Phpmig\Migration\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `users` (
                    `id`            INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `name`          VARCHAR(80),
                    `password`      VARCHAR(80),
                    `created_at`    TIMESTAMP NOT NULL,
                    `updated_at`    TIMESTAMP NOT NULL
                )';
        $container = $this->getContainer(); 
        $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `users`';
        $container = $this->getContainer(); 
        $container['db']->query($sql);
    }
}
