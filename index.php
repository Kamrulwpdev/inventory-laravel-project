cat > ~/domains/quickspace.shopilax.com/public_html/index.php << 'PHP'
<?php
// Redirect all requests to public/
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

// Bootstrap Laravel
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
require __DIR__ . '/public/index.php';
