<?php declare(strict_types=1);
namespace App\Auth\Middleware;

use App\Auth\Database\Table\RoleTable;
use Framework\Router\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Check if the user is an admin
 */
final readonly class AdminMiddleware implements MiddlewareInterface
{
    public function __construct(private RoleTable $roleTable)
    {
    }

    /**
     * @throws NotFoundException if the user is not an admin
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');
        if ($this->roleTable->findFromTable('user', $user->__get('id'))->__get('name') !== 'admin') {
            throw new NotFoundException();
        }
        return $handler->handle($request);
    }
}
