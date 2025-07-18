<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Iterator;
use Matraux\FileSystem\Folder\Folder;
use Nette\IOException;
use Nette\Utils\FileSystem;
use RuntimeException;

/**
 * @mixin File
 * @implements Iterator<int,string>
 */
trait Content
{

	private const int DataPart = 1024;

	/**
	 * Whole content of file
	 */
	final public string $content {
		get {
			return FileSystem::read((string) $this);
		}
	}

	/**
	 * Create file from content
	 *
	 * @throws RuntimeException If can not create file
	 * @throws IOException If can not create temporary dir
	 */
	final public static function fromContent(string $content, ?Folder $folder = null): static
	{
		$folder ??= Folder::create()->addPath('temp')->addPath('file');
		$folder = (string) $folder;

		FileSystem::createDir($folder);

		if (!$file = tempnam($folder, 'content-')) {
			throw new RuntimeException('Failed to create temporary file.');
		}

		FileSystem::write($file, $content);

		return new static($file);
	}

	/**
	 * @throws RuntimeException If can not read part of file
	 */
	final public function current(): string
	{
		$content = $this->file->fread(self::DataPart);

		if ($content === false) {
			throw new RuntimeException(sprintf('Can not read data on file "%s"', (string) $this));
		}

		$this->file->fseek($this->key() - self::DataPart);

		return $content;
	}

	final public function next(): void
	{
		if ($this->file->fseek($this->key() + self::DataPart) !== 0) {
			throw new RuntimeException(sprintf('Can not seek on file "%s".', (string) $this));
		}
	}

	final public function key(): int
	{
		$key = $this->file->ftell();

		if ($key === false) {
			throw new RuntimeException(sprintf('Can not obtaint pointer on file "%s".', (string) $this));
		}

		return $key;
	}

	final public function valid(): bool
	{
		return $this->size > $this->key();
	}

	final public function rewind(): void
	{
		$this->file->rewind();
	}

}
