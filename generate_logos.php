<?php
$brands = ["Bloom", "Maison", "Oud Noir", "Pure Elegance", "SoundPure"];

foreach ($brands as $brand) {
    $slug = strtolower(str_replace(' ', '-', $brand));
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="140" height="50" viewBox="0 0 140 50">
        <rect width="140" height="50" fill="transparent"/>
        <text x="70" y="32" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="#333333" text-anchor="middle" letter-spacing="1">' . htmlspecialchars(strtoupper($brand)) . '</text>
    </svg>';
    
    // We will save it as .png in the view but wait, the view has `.png` hardcoded.
    // I can save the SVG content in a file ending in .png, but browsers might be confused.
    // Let's modify the home.blade.php to expect SVG, or I can just save it as SVG and replace the extension in the view.
}
