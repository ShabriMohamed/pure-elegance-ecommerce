<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->handleRequest(Illuminate\Http\Request::capture());

use App\Models\ProductImage;

$images = ProductImage::all();
$existing = ['black-watch.png', 'face-serum.png', 'j5FcLVyixeFUJPGVFYRWgYVxLfeY44xQ0SXWoOQ1.jpg', 'parfum.png', 'white-sneakers.png'];

echo "=== All Product Image Records ===" . PHP_EOL;
foreach ($images as $img) {
    $file = basename($img->image_path);
    $exists = in_array($file, $existing);
    echo "ID:{$img->id} P:{$img->product_id} | {$img->image_path} | " . ($exists ? 'EXISTS' : 'MISSING') . PHP_EOL;
}
