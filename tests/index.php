<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest;

use Matraux\FileSystemTest\Utils\Tracy;
use Tracy\Debugger;

require_once __DIR__ . '/../vendor/autoload.php';

Tracy::setup();
Debugger::dump('Start dump');

Debugger::dump('Finish dump');
exit;