<?php declare(strict_types=1);

namespace Matraux\FileSystem\File;

use RuntimeException;

/**
 * @mixin File
 * @property-read int<0,max> $size file size in bytes
 */
trait Size
{
	/**
	 * @return int<0,max> file size in bytes
	 * @throws RuntimeException
	 */
	final protected function getSize(): int {

			$size = $this->file->getSize();

			if (!is_int($size) || $size < 0) {
				throw new RuntimeException(sprintf('Unable to get size of file "%s".', (string) $this));
			}

			return $size;

	}

	final public function count(): int
	{
		return $this->size;
	}
}
