<?php declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/public',
        __DIR__ . '/config'
    ]);
    
    //I have some problmes with rector and phpstan I didn't success to resolve them otherwise than that
    $rectorConfig->skip([
        __DIR__ . '\src\Framework\Database\PaginatedQuery.php',
        __DIR__ . '\src\Framework\Database\Table\ArticleTable.php',
        __DIR__ . '\src\Framework\Database\Table\Table.php'
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE
    ]);
};