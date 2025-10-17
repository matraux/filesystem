<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest\Utils;

use Matraux\FileSystem\File\File;
use Matraux\FileSystem\Folder\Folder;
use Nette\Neon\Neon;
use Throwable;
use Tracy\Debugger;

final class Tracy
{

	public static function setup(): void
	{
		try {
			$file = File::fromPath(Folder::create()->absolute . 'tracy.neon');
		} catch (Throwable $th) {
			return;
		}

		/** @var array{tracy?:array<string,bool|int|string>} */
		$neon = Neon::decodeFile((string) $file);
		if ($config = $neon['tracy'] ?? null) {
			Debugger::enable(Debugger::Development);
			foreach ($config as $property => $value) {
				Debugger::$$property = $value;
			}
		}
	}

}
