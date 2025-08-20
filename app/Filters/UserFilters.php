<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilters
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder<\App\Models\User> $query
     * @param array<string, string> $input
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\User>
     */
    public static function apply(Builder $query, array $input): Builder
    {
        $name = $input['name'] ?? null;
        $email = $input['email'] ?? null;

        $query->when($name, fn ($query, $name) => $query->whereLike('name', "%{$name}%"));
        $query->when($email, fn ($query, $email) => $query->whereLike('email', "%{$email}%"));

        return $query;
    }
}
