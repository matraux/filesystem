<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest\Unit;

use Matraux\FileSystem\File\File;
use Matraux\FileSystem\Folder\Folder;
use Matraux\FileSystemTest\Support\UnitTester;
use Nette\Utils\Random;
use Nette\Utils\Strings;
use Throwable;

final class FileCest
{

	public function testFileFromPath(UnitTester $tester): void
	{
		File::fromPath($tester->assets() . 'fromPath.txt');
	}

	public function testFileFromContent(UnitTester $tester): void
	{
		$content = Random::generate(2048);
		$folder = Folder::create($tester->temp());
		File::fromContent($content, $folder);
	}

	public function testFileReadContent(UnitTester $tester): void
	{
		$contents = [
			Random::generate(1024),
			Random::generate(1024),
			Random::generate(1024),
		];
		$folder = Folder::create($tester->temp());
		$file = File::fromContent(implode('', $contents), $folder);

		$tester->assertEquals(implode('', $contents), $file->content);

		foreach ($file as $index => $data) {
			$tester->assertEquals($contents[$index], $data);
		}
	}

	public function testFileReadSize(UnitTester $tester): void
	{
		$content = Random::generate(2048);
		$folder = Folder::create($tester->temp());
		$file = File::fromContent($content, $folder);

		$tester->assertEquals(Strings::length($content), $file->size);
		$tester->assertEquals(Strings::length($content), count($file));
	}

	public function testFileDelete(UnitTester $tester): void
	{
		$folder = Folder::create($tester->temp());
		$file = File::fromContent('', $folder);
		$file->delete();
		$tester->expectThrowable(Throwable::class, fn () => (string) $file);
	}

}
