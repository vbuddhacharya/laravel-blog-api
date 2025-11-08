<?php

namespace App\Actions;

use Illuminate\Http\Request;

class GetPerPageAction
{
    public static function execute(Request $request, ?int $max = 100): int
    {
        $perPage = $request->query('per_page', config('settings.pagination.perPage'));

        if ($perPage === 'all') {
            return $max; // limiting to max in case table is huge to avoid performance issues
        }

        return (int) $perPage;
    }
}
