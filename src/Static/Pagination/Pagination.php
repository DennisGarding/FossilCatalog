<?php

namespace App\Static\Pagination;

use App\Defaults;

class Pagination
{
    public static function calculate(int $tableColumnCount, ?int $page = 1): PaginationResult
    {
        if ($page < 1) {
            $page = 1;
        }

        $offset = 0;
        if ($page > 1) {
            $offset = ($page - 1) * Defaults::QUERY_LIMIT;
        }

        $pages = ceil($tableColumnCount / Defaults::QUERY_LIMIT);
        if ($pages < 1) {
            $pages = 1;
        }

        $startPage = $page - 5;
        if ($startPage < 1) {
            $startPage = 1;
        }

        $endPage = $page + 5;
        if ($endPage > $pages) {
            $endPage = $pages;
        }

        return new PaginationResult($offset, $page, $pages, $startPage, $endPage);
    }
}
