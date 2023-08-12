<?php declare(strict_types=1);
namespace App\Contact;

final class MailInfo
{
    public string $name;

    public string $email;

    public string $object;

    public string $message;

    /**
     * hydrate mailInfo
     * @param mixed[] $params
     *
     */
    public function hydrate(array $params): void
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
