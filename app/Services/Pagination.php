<?php namespace App\Services;

//use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class Pagination {

    public static function make($items, $total, $perPage, $currentPage)
    {
		$offSet = ($currentPage * $perPage) - $perPage;
		$itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

		return new Paginator(
			$itemsForCurrentPage,
            $total,
            $perPage,
			Paginator::resolveCurrentPage(),
			['path' => Paginator::resolveCurrentPath()]
		);
    }

}
