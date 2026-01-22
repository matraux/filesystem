<?php declare(strict_types = 1);

namespace Matraux\FileSystem\Test;

use Tracy\Debugger;
use Matraux\FileSystem\Test\Utils\Tracy;

require_once __DIR__ . '/../vendor/autoload.php';

Tracy::setup();
Debugger::dump('Start dump');

Debugger::dump('Finish dump');
exit;
