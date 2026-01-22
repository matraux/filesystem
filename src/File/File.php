<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Countable;
use Stringable;
use SplFileObject;
use IteratorAggregate;
use Matraux\FileSystem\Folder\Folder;
use RuntimeException;

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
	 * @throws RuntimeException If can not rename file
	 */
	final protected function rename(string $name): void
	{
		if (!@rename((string) $this, $name)) {
			throw new RuntimeException(sprintf('Unable to rename file "%s" to "%s".', (string) $this, $name));
		}

		$this->init($name);
	}

	/**
	 * Absolute directory path
	 */
	final public string $path
	{
		set {
			$this->rename(Folder::create($value) . $this->name);
		}
		get => $this->file->getPath() . DIRECTORY_SEPARATOR;
	}

	/**
	 * Relative directory path
	 */
	final public string $relativePath
	{
		get => (string) Folder::create($this->path)->relative;
	}

	/**
	 * Relative directory path for browser
	 */
	final public string $webPath
	{
		get => (string) preg_replace('~\\\\~', '/', $this->relativePath);
	}

	/**
	 * File name
	 */
	final public string $name
	{
		set {
			$this->rename($this->path . $value);
		}
		get => $this->file->getFilename();
	}

	/**
	 * File name without extension
	 */
	final public string $basename
	{
		set {
			$this->extension !== null ? $this->rename($this->path . $value . '.' . $this->extension) : $this->rename($this->path . $value);
		}
		get => $this->extension !== null ? $this->file->getBasename('.' . $this->extension) : $this->file->getBasename();
	}

	/**
	 * File extension
	 */
	final public ?string $extension
	{
		set {
			if($value) {
				$value = ltrim($value, '.');
			}

			$this->name = $value === null || $value === '' ? $this->basename : $this->basename . '.' . $value;
		}
		get {
			$extension = $this->file->getExtension();

			return $extension !== '' ? $extension : null;
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

			$type = finfo_file($finfo, (string) $this);
			finfo_close($finfo);

			return $type ?: null;
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
	 * @throws RuntimeException If can not open file
	 */
	final protected function __construct(string $file)
	{
		if (!is_file($file)) {
			throw new RuntimeException(sprintf('Failed to open file: No such file "%s".', $file));
		}

		$this->init($file);
	}

	/**
	 * @throws RuntimeException If can not delete file
	 */
	final public function delete(): void
	{
		if(!@unlink((string) $this)) {
			throw new RuntimeException(sprintf('Unable to delete file "%s".', (string) $this));
		}

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

	private function init(string $file): void
	{
		$this->file = new SplFileObject($file, 'r');
	}

	final public function __toString(): string
	{
		return $this->path . $this->name;
	}

}