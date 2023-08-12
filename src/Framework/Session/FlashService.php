<?php declare(strict_types=1);
namespace Framework\Session;

final class FlashService
{
    private string $sessionKey = 'flash';

    /**
     * @var string[]|null
     */
    private ?array $messages = null;

    public function __construct(readonly private SessionInterface $session)
    {
    }

    /**
     * set a success flash message
     * @param string $messages
     *
     * @return void
     */
    public function success(string $messages): void
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $messages;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * set an error flash message
     * @param string $messages
     *
     * @return void
     */
    public function error(string $messages): void
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['error'] = $messages;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * Retrieve the flash message
     * @param string $type
     *
     * @return string|null
     */
    public function get(string $type): ?string
    {
        if ($this->messages === null) {
            $this->messages = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }
        if (array_key_exists($type, $this->messages)) {
            return $this->messages[$type];
        }
        return null;
    }
}
