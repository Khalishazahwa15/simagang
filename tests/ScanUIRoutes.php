<?php

$dir = new RecursiveDirectoryIterator(dirname(__DIR__) . '/app/Views');
$iterator = new RecursiveIteratorIterator($dir);

$actions = [];
$hrefs = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $filename = str_replace('\\', '/', substr($file->getPathname(), strpos($file->getPathname(), 'app/Views')));
        
        preg_match_all('/<form[^>]+action=[\'\"]([^\'\"]+)[\'\"][^>]*>/i', $content, $formMatches);
        if (!empty($formMatches[1])) {
            foreach ($formMatches[1] as $action) {
                if ($action === '' || $action === '#' || $action === '?') continue;
                $actions[] = ['file' => $filename, 'type' => 'form', 'target' => $action];
            }
        }
        
        preg_match_all('/<a[^>]+href=[\'\"]([^\'\"]+)[\'\"][^>]*>/i', $content, $linkMatches);
        if (!empty($linkMatches[1])) {
            foreach ($linkMatches[1] as $href) {
                if ($href === '' || $href === '#' || strpos($href, 'javascript:') === 0 || strpos($href, 'mailto:') === 0) continue;
                $hrefs[] = ['file' => $filename, 'type' => 'link', 'target' => $href];
            }
        }
    }
}

$all = array_merge($actions, $hrefs);
$results = [];
foreach ($all as $item) {
    $target = $item['target'];
    $target = str_replace('<?= BASE_URL ?>', '', $target);
    $target = preg_replace('/\/[0-9]+$/', '/:id', $target); 
    $target = preg_replace('/\/<\?=.*?\$[a-zA-Z0-9_\[\]\'\"]+.*?\?>/', '/:id', $target); // /detail/<?= $id ?>
    
    // Normalize consecutive slashes
    $target = preg_replace('/\/+/', '/', $target);
    
    $results[] = [
        'file' => $item['file'],
        'type' => $item['type'],
        'target' => $target
    ];
}

$unique = [];
foreach ($results as $res) {
    $key = $res['type'] . ' | ' . $res['target'];
    if (!isset($unique[$key])) {
        $unique[$key] = ['type' => $res['type'], 'target' => $res['target'], 'files' => []];
    }
    if (!in_array($res['file'], $unique[$key]['files'])) {
        $unique[$key]['files'][] = $res['file'];
    }
}

echo "=== UI ACTIONS AND LINKS ===\n";
foreach ($unique as $data) {
    echo str_pad($data['type'], 5) . " : " . $data['target'] . " -> " . implode(', ', $data['files']) . "\n";
}
