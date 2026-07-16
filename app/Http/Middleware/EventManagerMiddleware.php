<?php

namespace App\Http\Middleware;

use App\Models\User;

class EventManagerMiddleware extends BasePortalMiddleware
{
    protected function getRoleCheck(User $user): bool
    {
        return $user->isEventManager();
    }

    protected function getSuspendedMessage(): string
    {
        return 'Your account has been suspended. Please contact support.';
    }

    protected function getBannedMessage(): string
    {
        return 'Your account has been permanently disabled.';
    }
}
