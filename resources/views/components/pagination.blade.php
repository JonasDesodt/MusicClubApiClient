<ul class="pagination">
    @if($page > 1)
        <li><a href="{{ '?page=' . (int)$page - 1 }}">Previous</a></li>
    @endif

    @if($page < ((int) $totalCount / (int) $pageSize) )
        <li><a href="{{ '?page=' . (int)$page + 1 }}">Next<a></li>
    @endif
</ul>