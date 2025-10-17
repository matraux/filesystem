<?php declare(strict_types=1);

namespace Matraux\FileSystemTest\Helper;

use Codeception\Module;

final class Path extends Module
{

	public static function temp(): string
	{
		return implode(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), 'codeception', 'filesystem']) . DIRECTORY_SEPARATOR;
	}

	public static function assets(): string
	{
		return implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'assets']) . DIRECTORY_SEPARATOR;
	}

}