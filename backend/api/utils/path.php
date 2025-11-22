<?php
function compute_shortest_path($graph, $start, $end) {
    if (!isset($graph[$start])) {
        return null;
    }

    $dist = [];
    $prev = [];
    $nodes = array_keys($graph);

    foreach ($nodes as $node) {
        $dist[$node] = INF;
    }
    $dist[$start] = 0;

    while (!empty($nodes)) {
        // Get node with smallest distance
        $minNode = null;
        foreach ($nodes as $node) {
            if ($minNode === null || $dist[$node] < $dist[$minNode]) {
                $minNode = $node;
            }
        }

        if ($minNode === $end) break;

        $nodes = array_diff($nodes, [$minNode]);

        if (!isset($graph[$minNode])) continue;

        foreach ($graph[$minNode] as $edge) {
            $alt = $dist[$minNode] + $edge["distance"];
            if ($alt < $dist[$edge["child"]]) {
                $dist[$edge["child"]] = $alt;
                $prev[$edge["child"]] = $minNode;
            }
        }
    }

    if (!isset($prev[$end]) && $start !== $end) return null;

    // Build path
    $path = [];
    for ($u = $end; isset($prev[$u]); $u = $prev[$u]) {
        array_unshift($path, $u);
    }
    array_unshift($path, $start);

    return [
        "path" => $path,
        "distance" => $dist[$end]
    ];
}
