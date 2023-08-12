<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Migration\AbstractMigration;

class CreateOrderTable extends AbstractMigration
{
    public function change()
    {
        $this->table('order')
            ->addColumn('user_id', 'integer')
            ->addColumn('seller_id', 'integer')
            ->addColumn('article_id', 'integer', ['null' => true])
            ->addColumn('payment_intent_id', 'string')
            ->addColumn('status', 'string', ['default' => 'pending'])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addForeignKey('user_id', 'user', 'id', [
                'delete' => 'CASCADE'
            ])
            ->addForeignKey('seller_id', 'user', 'id', [
                'delete' => 'CASCADE'
            ])
            ->addForeignKey('article_id', 'article', 'id', [
                'delete' => 'SET NULL'
            ])
            ->create();
    }
}
