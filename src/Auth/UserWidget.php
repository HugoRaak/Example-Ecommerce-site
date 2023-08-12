<?php declare(strict_types=1);
namespace App\Auth;

use App\Admin\AdminWidgetInterface;
use App\Auth\Database\Table\UserTable;
use Framework\Renderer\RendererInterface;

final class UserWidget implements AdminWidgetInterface
{
    public function __construct(readonly private RendererInterface $renderer, readonly private UserTable $userTable)
    {
    }

    /**
     * display widget on the admin dashboard
     * @return string
     */
    public function render(): string
    {
        $countUser = $this->userTable->count();
        return $this->renderer->render('@auth/admin/widget', compact('countUser'));
    }

    /**
     * display the link in the admin navbar
     * @return string
     */
    public function renderMenu(): string
    {
        return $this->renderer->render('@auth/admin/menu');
    }
}
