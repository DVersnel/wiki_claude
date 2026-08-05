<?php
namespace MDJ\test;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config.php';

use MDJ\Model\Repositories\TagRepository;

if (isset($_GET['action']) && $_GET['action'] === 'suggestions') {
    header('Content-Type: application/json; charset=utf-8');

    $tagRepository = new TagRepository();
    $tags = $tagRepository->getTags() ?: [];
    $query = strtolower(trim($_GET['q'] ?? ''));

    $suggestions = [];
    foreach ($tags as $tag) {
        $label = $tag->getTag();
        if ($query === '' || strpos(strtolower($label), $query) !== false) {
            $suggestions[] = $label;
        }
    }

    echo json_encode(array_values($suggestions));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tag Autocomplete Test</title>
    <style>
        #tag-autocomplete { position: relative; max-width: 360px; }
        #tag-input { width: 100%; padding: 8px; font-size: 1rem; }
        #tag-suggestions { list-style: none; margin: 0; padding: 0; position: absolute; width: 100%; background: #fff; border: 1px solid #ccc; border-top: none; z-index: 10; max-height: 200px; overflow-y: auto; }
        #tag-suggestions li { padding: 8px; cursor: pointer; }
        #tag-suggestions li:hover { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Tag Autocomplete Demo</h1>
    <div id="tag-autocomplete">
        <label for="tag-input">Search tag:</label>
        <input id="tag-input" type="text" autocomplete="off" placeholder="Start typing a tag...">
        <ul id="tag-suggestions"></ul>
    </div>

    <script src="../src/jquery.js"></script>
    <script src="../Scripts/Ratings.js"></script>
</body>
</html>



