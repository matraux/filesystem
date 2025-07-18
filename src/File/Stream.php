<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Matraux\FileSystem\Folder\Folder;
use Nette\IOException;
use Nette\Utils\FileSystem;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * @mixin File
 */
trait Stream
{

	private const int DataPart = 1024;

	/**
	 * Create file from PSR Response
	 *
	 * @throws RuntimeException If can not create file
	 * @throws IOException If can not create temporary dir
	 */
	final public static function fromStream(StreamInterface $stream, ?Folder $folder = null): static
	{
		$folder ??= Folder::create()->addPath('temp')->addPath('file');
		$folder = (string) $folder;

		FileSystem::createDir($folder);

		if (!$file = tempnam($folder, 'stream-')) {
			throw new RuntimeException('Failed to create temporary file.');
		} elseif (!$handle = fopen($file, 'w')) {
			throw new RuntimeException(sprintf('Failed to handle temporary file "%s".', $file));
		}

		while (!$stream->eof()) {
			fwrite($handle, $stream->read(self::DataPart));
		}

		fclose($handle);

		return new static($file);
	}

}
