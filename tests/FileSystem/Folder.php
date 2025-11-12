<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest\FileSystem;

use Matraux\FileSystem\Folder\Folder as FileSystemFolder;

final class Folder extends FileSystemFolder
{

	public self $temp
	{
		get => self::create(sys_get_temp_dir())->addPath('filesystem');
	}

	public self $data
	{
		get => self::create()->addPath('tests')->addPath('Data');
	}

}
