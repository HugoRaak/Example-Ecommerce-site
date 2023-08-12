<?php declare(strict_types=1);
namespace App\Auth;

use App\Admin\AdminWidgetInterface;
use App\Auth\Database\Table\UserTable;
use Framework\Renderer\RendererInterface;

final readonly class UserWidget implements AdminWidgetInterface
{
    public function __construct(private RendererInterface $renderer, private UserTable $userTable)
    {
    }

    /**
     * display widget on the admin dashboard
     */
    public function render(): string
    {
        $countUser = $this->userTable->count();
        return $this->renderer->render('@auth/admin/widget', ['countUser' => $countUser]);
    }

    /**
     * display the link in the admin navbar
     */
    public function renderMenu(): string
    {
        return $this->renderer->render('@auth/admin/menu');
    }
}
