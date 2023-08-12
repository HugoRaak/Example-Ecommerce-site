<?php declare(strict_types=1);
namespace App\Auth\Database\Table;

use App\Auth\Database\Entity\Role;
use Framework\Database\Table\Table;

/**
 * @extends Table<static, Role>
 */
final class RoleTable extends Table
{
    protected ?string $table = 'role';

    protected ?string $entity = Role::class;
}
