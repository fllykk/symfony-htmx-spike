<?php

namespace App\Service;

class PaginatorLinks
{

    /**
     * Helper function to generate a pagination link.
     *
     * @param int $page
     * @param string $label
     * @param string $route
     * @param array $queryParams
     * @param string $activeClass
     * @return array
     */

    private static function generateLink(int $page, string $label, string $route, array $queryParams, string $activeClass = ''): array
    {
        $queryParams['page'] = $page;
        $url = $route . '?' . http_build_query($queryParams);
        return ["href" => $url, "label" => $label, "class" => $activeClass];
    }


    public static function links(int $totalPages, int $currentPage, string $route, array $queryParams, int $nbOfNextLinks = 2, $list_class = 'list' ) : array
    {

        $last       = $totalPages;

        $start      = ( ( $currentPage - $nbOfNextLinks ) > 0 ) ? $currentPage - $nbOfNextLinks : 1;
        $end        = ( ( $currentPage + $nbOfNextLinks ) < $last ) ? $currentPage + $nbOfNextLinks : $last;//dd($nbOfNextLinks, $start, ($nbOfNextLinks - $start + 1));

        // increment the end pagination if currentPage - lastPage is less than nbOfNextLinks
        $end        = ( $currentPage <= $nbOfNextLinks ) ? $currentPage == $nbOfNextLinks ? $end + ($nbOfNextLinks - $start) : $end + ($nbOfNextLinks - $start + 1) : $end;
        $end = min($end, $totalPages);

        // decrement the start pagination if currentPage - lastPage is less than nbOfNextLinks
        $start        = ( $currentPage + $nbOfNextLinks ) >= $last ?  $start - ($nbOfNextLinks - ($last - $currentPage)) : $start;
        $start = min($start, $totalPages);

        $links = [];

        // if there is only one page, no need to show pagination links
        if($totalPages == 1){
            return $links;
        }

        if ($currentPage > 1 ){ // if currentPage > 0 then first link is << Previous
            $links[] = self::generateLink($currentPage-1, "&laquo;", $route, $queryParams);
        }

        if ( $start <= 1 ) {
            $start = 2;
            $activeClass = ($currentPage == 1) ? 'active' : '';
            $links[] = self::generateLink(1, "1", $route, $queryParams, $activeClass);
        }

        if(($currentPage -$nbOfNextLinks) >= 2){
            $links[] = ["href" => "", "label" => "<span class='disabled'>...</span>", "class" => "disabled"];
        }

        for ( $i = $start ; $i <= $end; $i++ ) {
            $activeClass = ($currentPage == $i) ? 'active' : '';;
            $links[] = self::generateLink($i, (string) $i, $route, $queryParams, $activeClass);
        }

        if ( $end < $last ) {
            $activeClass = ($currentPage == $last) ? "active" : "";
            $links[] = ["href" => "", "label" => "<span class='disabled'>...</span>", "class" => "disabled"];
            $links[] = self::generateLink($last, (string) $last, $route, $queryParams, $activeClass);
        }

        if ($currentPage <> $last) {
            $links[] = self::generateLink($currentPage == $last ? $last : $currentPage+1, (string) "&raquo;", $route, $queryParams);
        }

        return $links;
    }

}