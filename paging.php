<?php

function page($pdo, $resource) {
    $items_per_page = 6;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page);

    $offset = ($page - 1) * $items_per_page;

    $total_items_query = $pdo->query("SELECT COUNT(*) FROM $resource");
    $total_items = $total_items_query->fetchColumn();
    $total_pages = ceil($total_items / $items_per_page);

    return [
        "offset" => $offset,
        "page" => $page,
        "items_per_page" => $items_per_page,
        "total_pages" => $total_pages
    ];
}

function renderView($page, $totalPages)
{
    return '
        <div class="page-container">
            ' . ($page > 1 ? '<a href="?page=' . ($page - 1) . '" class="pagination-button">Prethodna stranica</a>' : '') . '
            ' . ($page < $totalPages ? '<a href="?page=' . ($page + 1) . '" class="pagination-button">Sljedeća stranica</a>' : '') . '
        </div>
    ';
}
