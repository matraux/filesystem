<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest;

use Nette\StaticClass;
use Nette\Utils\FileSystem;
use Tester\Dumper;
use Tester\Environment;
use Tester\Helpers;
use Tracy\Debugger;
use Nette\Neon\Neon;

require_once __DIR__ . '/../vendor/autoload.php';

final class Bootstrap
{

	use StaticClass;

	public const Root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

	public const Assets = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;

	public const Temp = self::Root . 'temp' . DIRECTORY_SEPARATOR;

	public static function tester(): void
	{
		Environment::setup();
	}

	public static function purgeTemp(string $subfolder): string
	{
		$folder = self::Temp . $subfolder;
		FileSystem::createDir($folder);
		Dumper::$dumpDir = $folder;
		Helpers::purge($folder);

		return $folder;
	}

	public static function dumper(): void
	{
		Debugger::enable(Debugger::Development);
		Debugger::$strictMode = true;
		Debugger::$maxDepth = 10;
		Debugger::$maxLength = 1000;
		Debugger::$logDirectory = self::Temp;

		if (is_file($config = self::Root . 'editor.tracy.neon')) {
			/** @var array<string,array<string,string>> $neon */
			$neon = Neon::decodeFile($config);
			Debugger::$editor = $neon['tracy']['editor'] ?? null;
		}
	}

}
