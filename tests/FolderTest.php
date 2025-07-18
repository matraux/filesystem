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

}

(new FolderTest())->run();
