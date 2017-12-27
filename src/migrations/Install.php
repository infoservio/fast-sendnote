<?php
namespace infoservio\mailmanager\migrations;

use Yii;
use Craft;
use craft\db\Migration;

class Install extends Migration
{
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->createTables();
        // $this->createIndexes();
//        $this->addForeignKeys();
        // Refresh the db schema caches
        Craft::$app->db->schema->refresh();

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
//        $this->removeForeignKeys();
//        $this->removeTables();

        return true;
    }

    // Private Methods
    // =========================================================================

    private function createTables()
    {
        if (!$this->tableExists('mailmanager_mail')) {
            $this->createTable('mailmanager_mail', [
                'id' => $this->primaryKey(),
                'userId' => $this->integer(),
                'templateId' => $this->integer(),
                'mailTypeId' => $this->string(),
                'email' => $this->string(255),
                'method' => $this->integer(),
                'customId' => $this->string(255),
                'isDelivered' => $this->smallInteger()->defaultValue(0),
                'isOpened' => $this->smallInteger()->defaultValue(0),
                'isDropped' => $this->smallInteger()->defaultValue(0),
                'dateCreated' => $this->date(),
                'dateUpdated' => $this->date(),
                'uid' => $this->text()
            ]);
        }

        if (!$this->tableExists('mailmanager_template')) {
            $this->createTable('mailmanager_template', [
                'id' => $this->primaryKey(),
                'userId' => $this->integer(),
                'slug' => $this->string(100)->unique(),
                'name' => $this->string(255),
                'subject' => $this->string(255),
                'template' => $this->text(),
                'isRemoved' => $this->smallInteger()->defaultValue(0),
                'dateCreated' => $this->date(),
                'dateUpdated' => $this->date(),
                'uid' => $this->text()
            ]);
        }

        if (!$this->tableExists('mailmanager_changes')) {
            $this->createTable('mailmanager_changes', [
                'id' => $this->primaryKey(),
                'templateId' => $this->integer(),
                'userId' => $this->integer(),
                'oldVersion' => $this->text(),
                'newVersion' => $this->text(),
                'dateCreated' => $this->date(),
                'dateUpdated' => $this->date(),
                'uid' => $this->text()
            ]);
        }

        if (!$this->tableExists('mailmanager_logs')) {
            $this->createTable('mailmanager_logs', [
                'id' => $this->primaryKey(),
                'pid' => $this->integer(),
                'culprit' => $this->integer(),
                'category' => $this->text(),
                'method' => $this->text(),
                'message' => $this->text(),
                'errors' => $this->text(),
                'dateCreated' => $this->date(),
                'dateUpdated' => $this->date(),
                'uid' => $this->text()
            ]);
        }
    }

    private function addForeignKeys()
    {
        $this->addForeignKey(
            'fk-template-users',
            'mailmanager_template',
            'userId',
            'users',
            'id'
        );

        $this->addForeignKey(
            'fk-mailmanager-mail-template',
            'mailmanager_mail',
            'templateId',
            'mailmanager_template',
            'id'
        );

        $this->addForeignKey(
            'fk-mailmanager-changes-template',
            'mailmanager_changes',
            'templateId',
            'mailmanager_template',
            'id'
        );
    }

    private function removeForeignKeys()
    {
        $this->dropForeignKey(
            'fk-template-users',
            'mailmanager_template'
        );

        $this->dropForeignKey(
            'fk-mailmanager-mail-template',
            'mailmanager_mail'
        );

        $this->dropForeignKey(
            'fk-mailmanager-changes-template',
            'mailmanager_changes'
        );
    }

    private function removeTables()
    {
        if ($this->tableExists('mailmanager_mail')) {
            $this->dropTable('mailmanager_mail');
        }

        if ($this->tableExists('mailmanager_changes')) {
            $this->dropTable('mailmanager_changes');
        }

        if ($this->tableExists('mailmanager_template')) {
            $this->dropTable('mailmanager_template');
        }

        if ($this->tableExists('mailmanager_logs')) {
            $this->dropTable('mailmanager_logs');
        }
    }

    private function tableExists($table)
    {
        return (Yii::$app->db->schema->getTableSchema($table) !== null);
    }
}

