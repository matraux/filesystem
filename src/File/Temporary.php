<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

/**
 * @mixin File
 */
trait Temporary
{

	/**
	 * File will be removed on shutdown
	 */
	final public bool $temporary {
		set(bool $temporary) {
			$this->temporary = $temporary;

	if ($this->shutdownRegister) {
		return;
	}

			$this->shutdownRegister = true;

			register_shutdown_function(function (self $file): void {
				if (!$file->temporary) {
					return;
				}

				$file->delete();
			}, $this);
		}
		get {
			return $this->temporary;
		}
	}

	private bool $shutdownRegister = false;

}
