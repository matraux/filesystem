<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Nette\IOException;
use Nette\Utils\FileSystem;
use Matraux\FileSystem\Folder\Folder;
use Psr\Http\Message\StreamInterface;
use Matraux\FileSystem\Exception\FileContentException;

/**
 * @mixin File
 */
trait Stream
{

	final protected const int StreamDataPart = 1024;

	/**
	 * Create file from PSR Response
	 *
	 * @throws FileContentException
	 * @throws IOException
	 */
	final public static function fromStream(StreamInterface $stream, ?Folder $folder = null): static
	{
		$folder ??= Folder::create()->addPath('temp')->addPath('file');
		$folder = (string) $folder;

		FileSystem::createDir($folder);

		if (!$file = tempnam($folder, 'stream-')) {
			throw new FileContentException('Unable to create temporary file.');
		} elseif (!$handle = fopen($file, 'w')) {
			throw new FileContentException(sprintf('Unable to open temporary file "%s".', $file));
		}

		while (!$stream->eof()) {
			fwrite($handle, $stream->read(self::StreamDataPart));
		}

		fclose($handle);

		return new static($file);
	}

}
