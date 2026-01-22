<?php declare(strict_types = 1);

namespace Matraux\FileSystem\Test\Unit;

use Codeception\Configuration;
use Matraux\FileSystem\Folder\Folder;
use Matraux\FileSystem\Test\Support\UnitTester;
use Nette\Utils\FileSystem;

final class FolderCest
{

	public function testFolderCreate(): void
	{
		Folder::fromPath();
	}

	public function testFolderAbsolute(UnitTester $tester): void
	{
		$folder = Folder::fromPath(__DIR__);
		$tester->assertEquals(__DIR__ . DIRECTORY_SEPARATOR, (string) $folder->absolute);
	}

	public function testFolderRelative(UnitTester $tester): void
	{
		$folder = Folder::fromPath(__DIR__);
		$root = Folder::fromPath();
		$tester->assertEquals((string) $folder->relative, str_replace((string) $root->absolute, '', __DIR__ . DIRECTORY_SEPARATOR));
	}

	public function testFolderInit(UnitTester $tester): void
	{
		$folder = Folder::fromPath(Configuration::outputDir())->addPath('initFolder');
		FileSystem::delete((string) $folder->absolute);

		$tester->assertDirectoryDoesNotExist((string) $folder->absolute);
		$folder->create();
		$tester->assertDirectoryExists((string) $folder->absolute);
	}

	public function testFolderExists(UnitTester $tester): void
	{
		$folder = Folder::fromPath(Configuration::outputDir())->addPath('existsFolder')->create();
		FileSystem::delete((string) $folder->absolute);

		$tester->assertEquals(false, $folder->exists);
		$folder->create();
		$tester->assertEquals(true, $folder->exists);
	}

}
