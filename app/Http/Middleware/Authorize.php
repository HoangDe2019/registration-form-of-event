<?php

namespace App\Http\Middleware;

use App\Role;
use App\RolePermission;
use App\Supports\Message;
use App\User;
use Closure;
use Dingo\Api\Routing\Router;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\OFFICE;

class Authorize
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var
     */
    protected $userInfo;

    /**
     * @var
     */
    protected $permissions;


    /**
     * Authorize constructor.
     */
    public function __construct(Router $router)
    {
        if (!OFFICE::allowRemote()) {
            throw new AccessDeniedHttpException(Message::get('remote_denied'));
        }

        $this->router = $router;
        $userId = OFFICE::getCurrentUserId();
        $user = User::find($userId);

        if (empty($user)) {
            throw new \Exception(Message::get("unauthorized"));
        }

        $this->userInfo = $user;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isSuperAdmin = array_get($this->userInfo, 'is_super', false);

        // User is Admin, then bypass checking permission
        if ($isSuperAdmin) {
            return $next($request);
        }
        $permissions = [];
        if (!empty($this->userInfo->role_id)) {
            $currentPermissions = RolePermission::with(['permission'])
                ->where('role_id', $this->userInfo->role_id)->get()->toArray();
            $permissions = array_pluck($currentPermissions, null, 'permission.code');
        }
        $action = array_get($this->router->getCurrentRoute()->getAction(), 'action', null);

        if (!$action) {
            return $next($request);
            //throw new AccessDeniedHttpException('Permission denied!');
        }

        if (empty($permissions[$action])) {
            throw new AccessDeniedHttpException(Message::get("no_permission"));
        }

        return $next($request);
    }

}
