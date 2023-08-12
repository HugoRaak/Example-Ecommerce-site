<?php

declare(strict_types=1);

namespace App\Auth\Database\Table;

use App\Auth\Database\Entity\User;
use Framework\Database\Table\Table;

/**
 * @extends Table<static, User>
 */
final class UserTable extends Table
{
    protected ?string $table = 'user';

    protected ?string $entity = User::class;
}
