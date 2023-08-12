<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Database\Table\UserTable;
use Framework\Validator;

final class UserValidator extends Validator
{
    /**
     * add rules about user to the validator
     *
     * @param mixed[] $dataForm
     */
    public function __construct(readonly array $dataForm, readonly UserTable $userTable)
    {
        parent::__construct($dataForm);
        $this
            ->rule('required', ['username', 'email', 'password', 'confirm_password'])
            ->rule(
                fn($field, $value) => !$userTable->exists($field, $value),
                'username',
                'Cette valeur est déjà utilisé'
            )
            ->rule('lengthBetween', 'username', 2, 30)
            ->rule('ascii', 'username')
            ->rule('email', 'email')
            ->rule('lengthMin', 'password', 8)
            ->rule('equals', 'confirm_password', 'password')
            ->setPrependLabels(false);
    }
}
