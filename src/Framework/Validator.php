<?php declare(strict_types=1);
namespace Framework;

use Valitron\Validator as ValitronValidator;

abstract class Validator extends ValitronValidator
{
    /**
     * @param mixed[] $data
     * @param string[] $fields
     */
    public function __construct(
        readonly array $data = [],
        readonly array $fields = [],
        readonly ?string $lang = null,
        readonly ?string $langDir = null
    ) {
        self::lang('fr');
        parent::__construct($data, $fields, $lang, $langDir);
        static::$_ruleMessages['email'] = "adresse mail invalide";
        static::$_ruleMessages['equals'] = "doit être identique au mot de passe";
        static::$_ruleMessages['regex'] = "doit contenir uniquement des lettres de a-z
                                           et/ou des chiffres 0-9 et/ou '-' ";
    }
}
