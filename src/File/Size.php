<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Matraux\FileSystem\Exception\FileContentException;

/**
 * @mixin File
 */
trait Size
{

	/**
	 * @var int<0,max> $size File size in bytes
	 * @throws FileContentException
	 */
	final public int $size
	{
		get {
			$size = $this->file->getSize();

			if (!is_int($size) || $size < 0) {
				throw new FileContentException(sprintf('Unable to get size of file "%s".', (string) $this));
			}

			return $size;
		}
	}

	final public function count(): int
	{
		return $this->size;
	}

}
