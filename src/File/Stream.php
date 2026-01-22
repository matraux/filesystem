<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Matraux\FileSystem\Folder\Folder;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * @mixin File
 */
trait Stream
{

	final protected const int StreamDataPart = 1024;

	/**
	 * Create file from PSR Response
	 *
	 * @throws RuntimeException
	 */
	final public static function fromStream(StreamInterface $stream, ?Folder $folder = null): static
	{
		$folder ??= Folder::create(sys_get_temp_dir());
		$folder = (string) $folder;

		if (!$file = tempnam($folder, 'stream-')) {
			throw new RuntimeException('Unable to create temporary file.');
		} elseif (!$handle = fopen($file, 'w')) {
			throw new RuntimeException(sprintf('Unable to open temporary file "%s".', $file));
		}

		while (!$stream->eof()) {
			if (fwrite($handle, $stream->read(self::StreamDataPart)) === false) {
				throw new RuntimeException(sprintf('Unable to write file "%s".', $file));
			}
		}

		fclose($handle);

		return new static($file);
	}

}
