<?php declare(strict_types=1);

namespace Matraux\FileSystemTest\Unit;

use Matraux\FileSystem\Folder\Folder;
use Matraux\FileSystemTest\Support\UnitTester;
use Nette\Utils\FileSystem;

final class FolderCest
{

	public function testFolderCreate(): void
	{
		Folder::create();
	}

	public function testFolderAbsolute(UnitTester $tester): void
	{
		$folder = Folder::create(__DIR__);
		$tester->assertEquals(__DIR__ . DIRECTORY_SEPARATOR, (string) $folder->absolute);
	}

	public function testFolderRelative(UnitTester $tester): void
	{
		$folder = Folder::create(__DIR__);
		$root = Folder::create();
		$tester->assertEquals((string) $folder->relative, str_replace((string) $root->absolute, '', __DIR__ . DIRECTORY_SEPARATOR));
	}

	public function testFolderInit(UnitTester $tester): void
	{
		$folder = Folder::create($tester->temp())->addPath('initFolder');
		FileSystem::delete((string) $folder->absolute);

		$tester->assertDirectoryDoesNotExist((string) $folder->absolute);
		$folder->init();
		$tester->assertDirectoryExists((string) $folder->absolute);
	}

}