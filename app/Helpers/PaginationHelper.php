<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper
{

    public static function getData(LengthAwarePaginator $paginatedData)
    {
        $array = $paginatedData->toArray();
        return $array['data'];
    }


    public static function getPaginationInfo(LengthAwarePaginator $result)
    {
        $paginate['total'] = $result->total();
        $paginate['perPage'] = $result->perPage();
        $paginate['currentPage'] = $result->currentPage();
        $paginate['lastPage'] = $result->lastPage();
        $paginate['nextPageUrl'] = $result->nextPageUrl();
        $paginate['previousPageUrl'] = $result->previousPageUrl();

        return $paginate;
    }
}
