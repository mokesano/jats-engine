<?php

declare(strict_types=1);

/**
 * Wizdam JatsEngine Wrapper
 * 
 * Entry point untuk aplikasi legacy ScholarWizdam
 * Memuat autoloader PSR-4 dan menyediakan akses ke JatsEngine
 */

// 1. Muat Autoloader PSR-4
$autoloaderPath = __DIR__ . '/autoloader.php';

if (!file_exists($autoloaderPath)) {
    error_log('Wizdam JatsEngine Critical Error: autoloader.php is missing.');
    throw new RuntimeException('Wizdam JatsEngine Error: Component autoloader.php is missing.');
}

require_once $autoloaderPath;

// 2. Class alias (opsional) untuk kompatibilitas backward
// Uncomment jika diperlukan shortcut global tanpa namespace
// if (class_exists('Wizdam\\JatsEngine\\JatsEngine') && !class_exists('JatsEngine')) {
//     class_alias('Wizdam\\JatsEngine\\JatsEngine', 'JatsEngine');
// }
