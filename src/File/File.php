<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Countable;
use Stringable;
use SplFileObject;
use RuntimeException;
use IteratorAggregate;
use Nette\Utils\Strings;
use Nette\Utils\FileSystem;
use Matraux\FileSystem\Folder\Folder;
use Matraux\FileSystem\Exception\FileNotFoundException;

/**
 * @implements IteratorAggregate<int,string>
 */
class File implements Stringable, Countable, IteratorAggregate
{

	use FileUpload;
	use Content;
	use Size;
	use Temporary;
	use Stream;

	final protected SplFileObject $file;

	/**
	 * Absolute directory path
	 */
	final public string $path
	{
		set(string $path){
			FileSystem::rename((string) $this, $path . $this->name);
			$this->initFile($path . $this->name);
		}
		get {
			return $this->file->getPath() . DIRECTORY_SEPARATOR;
		}
	}

	/**
	 * Relative directory path
	 */
	final public string $relativePath
	{
		get {
			return (string) Folder::create($this->file->getPath())->relative;
		}
	}

	/**
	 * Relative directory path for browser
	 */
	final public string $webPath
	{
		get {
			return Strings::replace($this->relativePath, '~\\\\~', '/');
		}
	}

	/**
	 * File name
	 */
	final public string $name
	{
		set(string $name) {
			FileSystem::rename((string) $this, $this->path . $name);
			$this->initFile($this->path . $name);
		}
		get {
			return $this->file->getFilename();
		}
	}

	/**
	 * File name without extension
	 */
	final public string $basename {
		set(string $basename) {
			FileSystem::rename((string) $this, $this->path . $basename . '.' . $this->extension);
			$this->initFile($this->path . $basename . '.' . $this->extension);
		}
		get {
			return $this->file->getBasename('.' . $this->extension);
		}
	}

	/**
	 * File extension
	 */
	final public ?string $extension
	{
		set(?string $extension) {
			$this->name = Strings::replace($this->name, '~\.' . $this->file->getExtension() . '$~') . '.' . $extension;
		}
		get {
			$extension = $this->file->getExtension();

			return !empty($extension) ? $extension : null;
		}
	}

	/**
	 * File MIME type
	 */
	final public ?string $type
	{
		get {
			if (!$finfo = finfo_open(FILEINFO_MIME_TYPE)) {
				return null;
			}

			return finfo_file($finfo, (string) $this) ?: null;
		}
	}

	/**
	 * File MTime
	 */
	final public ?int $mTime
	{
		get {
			$mTime = $this->file->getMTime();

			return $mTime !== false ? $mTime : null;
		}
	}

	/**
	 * @throws FileNotFoundException If can not open file
	 */
	final private function __construct(string $file)
	{
		if (!is_file($file)) {
			throw new FileNotFoundException(sprintf('Failed to open file: No such file "%s".', $file));
		}

		$this->initFile($file);
	}

	final public function delete(): void
	{
		FileSystem::delete((string) $this);
		unset($this->file);
	}

	/**
	 * Create file from existing file
	 *
	 */
	final public static function fromPath(string $file): static
	{
		return new static($file);
	}

	private function initFile(string $file): void
	{
		$this->file = new SplFileObject($file, 'r');
	}

	final public function __toString(): string
	{
		return $this->path . $this->name;
	}

}