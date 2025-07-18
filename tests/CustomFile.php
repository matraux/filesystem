<?php declare(strict_types = 1);

namespace Matraux\FileSystemTest;

use Matraux\FileSystem\File\File;

final class CustomFile extends File
{

	/**
	 * Folder depth
	 */
	public int $depth { // phpcs:ignore
		get {
			return substr_count($this->relativePath, DIRECTORY_SEPARATOR); // phpcs:ignore
		}
	}

}
