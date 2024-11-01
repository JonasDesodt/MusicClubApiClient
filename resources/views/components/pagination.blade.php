@php 
    $queryString = http_build_query(array_filter(request()->query(), function($key) {
        return $key !== 'page';
    }, ARRAY_FILTER_USE_KEY)); 
@endphp

<ul class="pagination">
    @if($page > 1)
        <li><a href="{{ '?page=' . ($page - 1) . ($queryString ? '&' . $queryString : '') }}">Previous</a></li>
    @endif

    @if($page < ceil($totalCount / $pageSize))
        <li><a href="{{ '?page=' . ($page + 1) . ($queryString ? '&' . $queryString : '') }}">Next</a></li>
    @endif
</ul>