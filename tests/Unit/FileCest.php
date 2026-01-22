<?php declare(strict_types = 1);

namespace Matraux\FileSystem\Test\Unit;

use Throwable;
use Nette\Utils\Random;
use Nette\Utils\Strings;
use Codeception\Configuration;
use Matraux\FileSystem\File\File;
use Matraux\FileSystem\Folder\Folder;
use Matraux\FileSystem\Test\Support\UnitTester;

final class FileCest
{

	public function testFileFromPath(UnitTester $tester): void
	{
		File::fromPath(Folder::fromPath(Configuration::dataDir()) . 'fromPath.txt');
	}

	public function testFileFromContent(UnitTester $tester): void
	{
		$content = Random::generate(2048);
		$folder = Folder::fromPath(Configuration::outputDir());
		File::fromContent($content, $folder);
	}

	public function testFileReadContent(UnitTester $tester): void
	{
		$contents = [
			Random::generate(1024),
			Random::generate(1024),
			Random::generate(1024),
		];
		$folder = Folder::fromPath(Configuration::outputDir());
		$file = File::fromContent(implode('', $contents), $folder);

		$tester->assertEquals(implode('', $contents), $file->content);

		foreach ($file as $index => $data) {
			$tester->assertEquals($contents[$index], $data);
		}
	}

	public function testFileReadSize(UnitTester $tester): void
	{
		$content = Random::generate(2048);
		$folder = Folder::fromPath(Configuration::outputDir());
		$file = File::fromContent($content, $folder);

		$tester->assertEquals(Strings::length($content), $file->size);
		$tester->assertEquals(Strings::length($content), count($file));
	}

	public function testFileDelete(UnitTester $tester): void
	{
		$folder = Folder::fromPath(Configuration::outputDir());
		$file = File::fromContent('', $folder);
		$file->delete();
		$tester->expectThrowable(Throwable::class, fn () => (string) $file);
	}

	public function testFileTemporary(UnitTester $tester): void
	{
		$folder = Folder::fromPath(Configuration::outputDir());
		$file = File::fromContent('', $folder);
		$file->name = 'temporary';
		$file->extension = 'txt';
		$file->temporary = true;

		$filename = (string) $file;
		$tester->assertFileExists($filename);
		unset($file);
		$tester->assertFileNotExists($filename);
	}

	public function testFileProperties(UnitTester $tester): void
	{
		$folder = Folder::fromPath(Configuration::outputDir());
		$file = File::fromContent('', $folder);

		$file->name = 'abcd';
		$tester->assertEquals('abcd', $file->name);

		$file->extension = 'txt';
		$tester->assertEquals('abcd', $file->basename);
		$tester->assertEquals('abcd.txt', $file->name);

		$file->basename = 'xyz';
		$tester->assertEquals('xyz', $file->basename);
		$tester->assertEquals('xyz.txt', $file->name);

		$file->extension = '0';
		$tester->assertEquals('xyz', $file->basename);
		$tester->assertEquals('xyz.0', $file->name);

		$file->extension = null;
		$tester->assertEquals('xyz', $file->basename);
		$tester->assertEquals('xyz', $file->name);

		$tester->assertEquals(Configuration::outputDir(), $file->path);
	}

}
