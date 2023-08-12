<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateArticleTable extends AbstractMigration
{
    public function change()
    {
        $this->table('article')
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('images', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('price', 'float')
            ->addColumn('description', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addIndex(['name', 'slug'], ['unique' => true])
            ->create();
    }
}
