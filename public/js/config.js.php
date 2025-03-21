<?php
header('Content-Type: application/javascript');
require_once __DIR__ . '/../../config.php';
?>

const SUPABASE_CONFIG = {
    url: '<?= $_ENV["SUPABASE_URL"] ?>',
    key: '<?= $_ENV["SUPABASE_KEY"] ?>'
};
