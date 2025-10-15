<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest;

use Matraux\FileSystem\Folder\Folder;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class FolderTest extends TestCase
{

	public function testFolderCreate(): void
	{
		$folder = Folder::create();
		Assert::type(Folder::class, $folder);
	}

	public function testFolderAbsolute(): void
	{
		$folder = Folder::create(__DIR__);
		Assert::equal(__DIR__ . DIRECTORY_SEPARATOR, (string) $folder->absolute);
	}

	public function testFolderRelative(): void
	{
		$folder = Folder::create(__DIR__);
		Assert::equal(basename(__DIR__) . DIRECTORY_SEPARATOR, (string) $folder->relative);
	}

	public function testFolderInit(): void
	{
		$path = Bootstrap::purgeTemp(__FUNCTION__);
		$folder = Folder::create($path)->addPath('A')->addPath('B');
		Assert::equal(false, is_dir((string) $folder));
		Assert::equal(true, is_dir((string) $folder->init()));
	}

}

new FolderTest()->run();
