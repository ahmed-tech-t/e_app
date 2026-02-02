<?php
namespace App\Domain;

use Illuminate\Pagination\LengthAwarePaginator;

class PagenatorMeta
{
    private $total;
    private $current_page;
    private $last_page;

    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->total = $paginator->total();
        $this->current_page = $paginator->currentPage();
        $this->last_page = $paginator->lastPage();
    }


    public function toArray()
    {
        return [
            'total' => $this->total,
            'current_page' => $this->current_page,
            'last_page' => $this->last_page,
        ];
    }

}