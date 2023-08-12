<?php declare(strict_types=1);
namespace App\Contact;

use Framework\Validator;

final class ContactValidator extends Validator
{
    /**
     * add rules about contact form to the validator
     *
     * @param string[] $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this
            ->rule('required', ['name', 'email', 'object', 'message'])
            ->rule('lengthBetween', ['name', 'object'], 2, 60)
            ->rule('email', 'email')
            ->setPrependLabels(false);
    }
}
