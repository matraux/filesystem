<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest\Unit;

use Nette\Utils\FileSystem;
use Matraux\FileSystemTest\FileSystem\Folder;
use Matraux\FileSystemTest\Support\UnitTester;

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
		$folder = Folder::create()->temp->addPath('initFolder');
		FileSystem::delete((string) $folder->absolute);

		$tester->assertDirectoryDoesNotExist((string) $folder->absolute);
		$folder->init();
		$tester->assertDirectoryExists((string) $folder->absolute);
	}

	public function testFolderExists(UnitTester $tester): void
	{
		$folder = Folder::create()->temp->addPath('existsFolder')->init();
		FileSystem::delete((string) $folder->absolute);

		$tester->assertEquals(false, $folder->exists);
		$folder->init();
		$tester->assertEquals(true, $folder->exists);
	}

}
