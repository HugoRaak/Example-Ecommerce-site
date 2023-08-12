<?php declare(strict_types=1);
namespace App\Auth;

use App\Auth\Actions\AdminUserAction;
use App\Auth\Actions\CreateAction;
use App\Auth\Actions\LoginAction;
use App\Auth\Actions\LogoutAction;
use App\Auth\Actions\PaymentAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

final class AuthModule extends Module
{
    protected const DEFINITIONS = __DIR__ . '/config.php';

    protected const MIGRATIONS = __DIR__ . '/db/migrations';

    protected const SEEDS = __DIR__ . '/db/seeds';

    public function __construct(readonly ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addPath('auth', dirname(__DIR__) . '/Auth/views');
        $router = $container->get(\Framework\Router::class);
        $prefix = $container->get('auth.login');
        $router
            ->get($prefix, LoginAction::class, 'auth.login')
            ->post($prefix, LoginAction::class)
            ->get('/create', CreateAction::class, 'auth.create')
            ->post('/create', CreateAction::class)
            ->post('/logout', LogoutAction::class, 'auth.logout')
            ->post($container->get('pay.prefix') . '-[i:id]', PaymentAction::class, 'pay')
            ->post($container->get('pay.prefix') . '/validation', PaymentAction::class, 'pay.capture')
            ->post($container->get('pay.prefix') . '/autorisation', PaymentAction::class, 'pay.authorization');

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router
                ->get($prefix . '/utilisateur', AdminUserAction::class, 'admin.user.index')
                ->delete($prefix . '/utilisateur/supprimer-[i:id]', AdminUserAction::class, 'admin.user.delete')
                ->post($prefix . '/utilisateur/ajouter-role-[i:id]', AdminUserAction::class, 'admin.user.addRole');
        }
    }
}
