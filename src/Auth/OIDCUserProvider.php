<?php

/** @noinspection ContractViolationInspection */

namespace Autisid\OIDCClient\Auth;

use AssertionError;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Autisid\OIDCClient\Models\User;
use Autisid\OpenIDConnect\UserInfo;

class OIDCUserProvider implements UserProvider
{
    final public function retrieveByInfo(UserInfo $user_info): User
    {
        $attrs = $user_info->attrs();
        $uuid = $attrs->pull('sub');

        $user = config('auth.providers.users.model')::where(config('oidc.sub_column'), $uuid)->firstOrNew();
        try {
            assert($user instanceof User);
        } catch (AssertionError) {
            throw new AssertionError('User model must extend ' . User::class);
        }

        /** @noinspection UnusedFunctionResultInspection */
        $attrs->each(fn (mixed $value, string $attr) => $user->$attr = $value);

        return $user;
    }

    final public function retrieveById(mixed $identifier): ?User
    {
        return null;
    }

    final public function retrieveByToken(mixed $identifier, mixed $token): ?User
    {
        return null;
    }

    final public function updateRememberToken(Authenticatable|User $user, mixed $token): void
    {
    }

    final public function retrieveByCredentials(array $credentials): ?User
    {
        return null;
    }

    final public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return true;
    }
}
