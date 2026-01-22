<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use IteratorAggregate;
use Matraux\FileSystem\Folder\Folder;
use RuntimeException;
use Traversable;

/**
 * @mixin File
 * @implements IteratorAggregate<int,string>
 */
trait Content
{

	final protected const int ContentDataPart = 1024;

	/**
	 * Whole content of file
	 */
	final public string $content
	{
		get {
			$content = @file_get_contents((string) $this);

			return $content !== false ? $content : throw new RuntimeException(sprintf('Unable to read file "%s".', (string) $this));
		}
	}

	/**
	 * Create file from content
	 *
	 * @throws RuntimeException
	 */
	final public static function fromContent(string $content, ?Folder $folder = null): static
	{
		$folder ??= Folder::fromPath(sys_get_temp_dir());
		$folder = (string) $folder;

		if (!$file = tempnam($folder, 'content-')) {
			throw new RuntimeException(sprintf('Unable to create temporary file in folder "%s".', $folder));
		}

		if (@file_put_contents($file, $content) === false) {
			throw new RuntimeException(sprintf('Unable to write file "%s".', $file));
		}

		return new static($file);
	}

	/**
	 * @throws RuntimeException
	 */
	final public function getIterator(): Traversable
	{
		$this->file->rewind();

		while ($this->file->ftell() !== false && $this->size > $this->file->ftell()) {
			$content = $this->file->fread(self::ContentDataPart);
			if ($content === false) {
				throw new RuntimeException(sprintf('Unable to read from file "%s".', (string) $this));
			}

			yield $content;
		}
	}

}
