<?php declare(strict_types=1);

namespace Matraux\FileSystem\File;

use Countable;
use IteratorAggregate;
use Matraux\FileSystem\Folder\Folder;
use RuntimeException;
use SplFileObject;
use Stringable;

/**
 * @implements IteratorAggregate<int,string>
 * @property string $path Absolute directory path
 * @property-read string $relativePath Relative directory path
 * @property-read string $webPath Relative directory path for browser
 * @property string $name File name
 * @property string $basename File name without extension
 * @property ?string $extension File extension
 * @property-read ?string $type File MIME type
 * @property-read ?int $mTime File MTime
 */
class File implements Stringable, Countable, IteratorAggregate
{
	use FileUpload;
	use Content;
	use Size;
	use Temporary;
	use Stream;

	protected SplFileObject $file;

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
	 * Create file from existing file
	 */
	final public static function fromPath(string $filepath): static
	{
		return new static($filepath);
	}

	/**
	 * @throws RuntimeException If can not delete file
	 */
	final public function delete(): void
	{
		if (!@unlink((string) $this)) {
			$message = error_get_last()['message'] ?? null;

			throw new RuntimeException(sprintf('Unable to delete file "%s". %s', (string) $this, $message));
		}

		unset($this->file);
	}

	final protected function setPath(string $value): void
	{
		$this->rename(Folder::fromPath($value) . $this->name);
	}

	final protected function getPath(): string
	{
		return $this->file->getPath() . DIRECTORY_SEPARATOR;
	}

	final protected function getRelativePath(): string
	{
		return (string) Folder::fromPath($this->path)->relative;
	}

	final protected function getWebPath(): string
	{
		return (string) preg_replace('~\\\\~', '/', $this->relativePath);
	}

	final protected function setName(string $value): void
	{

		$this->rename($this->path . $value);
	}

	final protected function getName(): string
	{
		return $this->file->getFilename();
	}

	final protected function setBasename(string $value): void
	{

		$this->extension !== null ? $this->rename($this->path . $value . '.' . $this->extension) : $this->rename($this->path . $value);
	}

	final protected function getBasename(): string
	{
		return $this->extension !== null ? $this->file->getBasename('.' . $this->extension) : $this->file->getBasename();
	}

	final protected function setExtension(?string $value): void
	{

		if ($value) {
			$value = ltrim($value, '.');
		}

		$this->name = $value === null || $value === '' ? $this->basename : $this->basename . '.' . $value;
	}

	final protected function getExtension(): ?string
	{

		$extension = $this->file->getExtension();

		return $extension !== '' ? $extension : null;
	}

	final protected function getType(): ?string
	{
		if (!$finfo = finfo_open(FILEINFO_MIME_TYPE)) {
			return null;
		}

		$type = finfo_file($finfo, (string) $this);
		finfo_close($finfo);

		return $type ?: null;
	}

	final protected function getMTime(): ?int
	{
		$mTime = $this->file->getMTime();

		return $mTime !== false ? $mTime : null;
	}

	/**
	 * @throws RuntimeException If can not rename file
	 */
	final protected function rename(string $name): void
	{
		if (!@rename((string) $this, $name)) {
			$message = error_get_last()['message'] ?? null;

			throw new RuntimeException(sprintf('Unable to rename file "%s" to "%s". %s', (string) $this, $name, $message));
		}

		$this->init($name);
	}

	private function init(string $file): void
	{
		$this->file = new SplFileObject($file, 'r');
	}

	public function __get(string $name): mixed
	{
		return match ($name) {
			'path' => $this->getPath(),
			'relativePath' => $this->getRelativePath(),
			'webPath' => $this->getWebPath(),
			'name' => $this->getName(),
			'basename' => $this->getBasename(),
			'extension' => $this->getExtension(),
			'type' => $this->getType(),
			'mTime' => $this->getMTime(),

			'content' => $this->getContent(),
			'fileUpload' => $this->getFileUpload(),
			'size' => $this->getSize(),
			default => throw new RuntimeException(sprintf('Undefined property %s::$%s.', static::class, $name)),
		};
	}

	public function __set(string $name, mixed $value): void
	{
		match ($name) {
			'path' => $this->setPath($value), // @phpstan-ignore argument.type
			'name' => $this->setName($value), // @phpstan-ignore argument.type
			'basename' => $this->setBasename($value), // @phpstan-ignore argument.type
			'extension' => $this->setExtension($value), // @phpstan-ignore argument.type
			default => throw new RuntimeException(sprintf('Undefined property %s::$%s.', static::class, $name)),
		};
	}

	final public function __toString(): string
	{
		return $this->path . $this->name;
	}
}
