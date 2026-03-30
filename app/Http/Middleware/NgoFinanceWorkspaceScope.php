<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users with only the ngo_finance role may access treasury routes; others pass through.
 * Prevents finance-only accounts from using programme/field/HR admin tools.
 */
class NgoFinanceWorkspaceScope
{
    private const ALLOWED_ROUTE_NAMES = [
        'ngo.dashboard',
        'ngo.profile',
        'ngo.donations',
        'ngo.banking',
        'ngo.ledger',
        'ngo.ledger.store',
        'ngo.finance.hub',
        'ngo.finance.activity',
        'ngo.finance.payments',
        'ngo.finance.payments.store',
        'ngo.finance.payments.paid',
        'ngo.finance.preferences',
        'ngo.finance.claims',
        'ngo.finance.claims.store',
        'ngo.finance.claims.decide',
        'ngo.bank-accounts.store',
        'ngo.bank-accounts.update',
        'ngo.bank-accounts.destroy',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->hasRole('ngo_finance')) {
            return $next($request);
        }

        if ($user->hasRole('ngo_admin') || $user->hasRole('ngo_staff')) {
            return $next($request);
        }

        $name = $request->route()?->getName();
        if ($name === null || in_array($name, self::ALLOWED_ROUTE_NAMES, true)) {
            return $next($request);
        }

        return redirect()->route('ngo.finance.hub');
    }
}
