<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest;

use Matraux\FileSystem\File\File;
use Matraux\FileSystem\Folder\Folder;
use Nette\Utils\Random;
use Nette\Utils\Strings;
use Tester\Assert;
use Tester\TestCase;
use Throwable;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class FileTest extends TestCase
{

	public function testFileFromPath(): void
	{
		Assert::noError(function (): void {
			File::fromPath(Bootstrap::Assets . 'fromPath.txt');
		});
	}

	public function testFileFromContent(): void
	{
		$folder = Folder::create(Bootstrap::Temp . __FUNCTION__)->absolute;
		Bootstrap::purgeTemp(__FUNCTION__);

		$content = Random::generate(2048);

		Assert::noError(function () use ($content, $folder): void {
			File::fromContent($content, $folder);
		});
	}

	public function testFileReadContent(): void
	{
		$folder = Folder::create(Bootstrap::Temp . __FUNCTION__)->absolute;
		Bootstrap::purgeTemp(__FUNCTION__);

		$contents = [
			Random::generate(1024),
			Random::generate(1024),
			Random::generate(1024),
		];

		$file = File::fromContent(implode('', $contents), $folder);

		Assert::equal($file->content, implode('', $contents));

		foreach ($file as $index => $data) {
			Assert::contains($data, $contents[$index]);
		}
	}

	public function testFileReadSize(): void
	{
		$folder = Folder::create(Bootstrap::Temp . __FUNCTION__)->absolute;
		Bootstrap::purgeTemp(__FUNCTION__);

		$content = Random::generate(2048);
		$file = File::fromContent($content, $folder);

		Assert::equal($file->size, Strings::length($content));
		Assert::equal(count($file), Strings::length($content));
	}

	public function testFileDelete(): void
	{
		$folder = Folder::create(Bootstrap::Temp . __FUNCTION__)->absolute;
		Bootstrap::purgeTemp(__FUNCTION__);

		$file = File::fromContent('', $folder);

		Assert::noError(function () use ($file): void {
			$file->delete();
		});

		Assert::error(fn () => (string) $file, Throwable::class);
	}

}

(new FileTest())->run();
