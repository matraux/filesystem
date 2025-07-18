<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use RuntimeException;

/**
 * @mixin File
 */
trait Size
{

	/**
	 * @var int<0,max> $size File size in bytes
	 * @throws RuntimeException If can not obtain file size
	 */
	final public int $size {
		get {
			$size = $this->file->getSize();

	if (!is_int($size) || $size < 0) {
		throw new RuntimeException(sprintf('Can not obtaint size of file "%s".', (string) $this));
	}

			return $size;
		}
	}

	final public function count(): int
	{
		return $this->size;
	}

}
