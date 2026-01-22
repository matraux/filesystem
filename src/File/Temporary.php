<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Throwable;

/**
 * @mixin File
 */
trait Temporary
{

	final public bool $temporary = false;

	public function __destruct()
	{
		if ($this->temporary) {
			try {
				$this->delete();
			} catch (Throwable $th) {

			}
		}
	}

}