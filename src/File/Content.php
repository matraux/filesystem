<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Traversable;
use IteratorAggregate;
use Nette\IOException;
use Nette\Utils\FileSystem;
use Matraux\FileSystem\Folder\Folder;
use Matraux\FileSystem\Exception\FileContentException;

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
			return FileSystem::read((string) $this);
		}
	}

	/**
	 * Create file from content
	 *
	 * @throws FileContentException
	 * @throws IOException
	 */
	final public static function fromContent(string $content, ?Folder $folder = null): static
	{
		$folder ??= Folder::create()->addPath('temp')->addPath('file');
		$folder = (string) $folder;

		FileSystem::createDir($folder);

		if (!$file = tempnam($folder, 'content-')) {
			throw new FileContentException(sprintf('Unable to create temporary file in directory "%s".', $folder));
		}

		FileSystem::write($file, $content);

		return new static($file);
	}

	/**
	 * @throws FileContentException
	 */
	final public function getIterator(): Traversable
	{
		$this->file->rewind();

		while ($this->size > $this->file->ftell() ) {
			$content = $this->file->fread(self::ContentDataPart);
			if ($content === false) {
				throw new FileContentException(sprintf('Unable to read from file "%s".', (string) $this));
			}

			yield $content;
		}
	}

}