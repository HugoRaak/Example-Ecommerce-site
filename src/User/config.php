<?php

use function DI\string;

return [
    'user.prefix' => '/compte',
    'user.edit.prefix' => string('{user.prefix}/modifier'),
    'user.delete.prefix' => string('{user.prefix}/supprimer'),
];
