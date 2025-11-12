<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest\Unit;

use Throwable;
use Nette\Utils\Random;
use Nette\Utils\Strings;
use Matraux\FileSystem\File\File;
use Matraux\FileSystemTest\FileSystem\Folder;
use Matraux\FileSystemTest\Support\UnitTester;

final class FileCest
{

	public function testFileFromPath(UnitTester $tester): void
	{
		File::fromPath(Folder::create()->data . 'fromPath.txt');
	}

	public function testFileFromContent(UnitTester $tester): void
	{
		$content = Random::generate(2048);
		$folder = Folder::create()->temp;
		File::fromContent($content, $folder);
	}

	public function testFileReadContent(UnitTester $tester): void
	{
		$contents = [
			Random::generate(1024),
			Random::generate(1024),
			Random::generate(1024),
		];
		$folder = Folder::create()->temp;
		$file = File::fromContent(implode('', $contents), $folder);

		$tester->assertEquals(implode('', $contents), $file->content);

		foreach ($file as $index => $data) {
			$tester->assertEquals($contents[$index], $data);
		}
	}

	public function testFileReadSize(UnitTester $tester): void
	{
		$content = Random::generate(2048);
		$folder = Folder::create()->temp;
		$file = File::fromContent($content, $folder);

		$tester->assertEquals(Strings::length($content), $file->size);
		$tester->assertEquals(Strings::length($content), count($file));
	}

	public function testFileDelete(UnitTester $tester): void
	{
		$folder = Folder::create()->temp;
		$file = File::fromContent('', $folder);
		$file->delete();
		$tester->expectThrowable(Throwable::class, fn () => (string) $file);
	}

}
