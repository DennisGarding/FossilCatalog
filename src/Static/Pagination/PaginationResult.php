<?php

namespace App\Static\Pagination;

class PaginationResult
{
    public function __construct(
        private readonly int $offset,
        private readonly int $page,
        private readonly int $pages,
        private readonly int $startPage,
        private readonly int $endPage,
    ) {}

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getStartPage(): int
    {
        return $this->startPage;
    }

    public function getEndPage(): int
    {
        return $this->endPage;
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'offset' => $this->offset,
            'page' => $this->page,
            'pages' => $this->pages,
            'startPage' => $this->startPage,
            'endPage' => $this->endPage,
        ];
    }
}
