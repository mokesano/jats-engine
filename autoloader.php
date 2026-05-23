<?php

declare(strict_types=1);

/**
 * PSR-4 Autoloader untuk Wizdam\JatsEngine
 * 
 * Mengimplementasikan standar PSR-4 untuk autoloading class
 * dengan mapping namespace ke struktur direktori src/
 */

spl_autoload_register(static function (string $class): void {
    // Prefix namespace untuk library JatsEngine
    $prefix = 'Wizdam\\JatsEngine\\';
    
    // Base directory untuk namespace prefix ini
    $baseDir = __DIR__ . '/src/';
    
    // Cek apakah class menggunakan prefix namespace ini
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Bukan class milik namespace ini, biarkan autoloader lain menanganinya
        return;
    }
    
    // Ambil bagian relative class name setelah prefix
    $relativeClass = substr($class, $len);
    
    // Ganti backslash namespace dengan directory separator untuk mendapatkan path file
    $relativePath = str_replace('\\', '/', $relativeClass);
    
    // Bangun path lengkap ke file class
    $file = $baseDir . $relativePath . '.php';
    
    // Load file jika ada
    if (file_exists($file)) {
        require_once $file;
    }
});
