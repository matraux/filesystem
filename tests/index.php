<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::dumper();
dump('Test dumper');

dump('Exit dumper');
exit;
