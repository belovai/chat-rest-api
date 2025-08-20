<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilters
{
    public static function apply(Builder $query, array $input): Builder
    {
        $name = $input['name'] ?? null;
        $email = $input['email'] ?? null;

        $query->when($name, fn ($query, $name) => $query->whereLike('name', "%{$name}%"));
        $query->when($email, fn ($query, $email) => $query->whereLike('email', "%{$email}%"));

        return $query;
    }
}
