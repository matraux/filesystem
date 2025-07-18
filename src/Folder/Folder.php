<?php declare(strict_types = 1);

namespace Matraux\FileSystem\Folder;

use Composer\InstalledVersions;
use Nette\SmartObject;
use RuntimeException;
use Stringable;

class Folder implements Stringable
{

	use SmartObject;

	protected const string Root = './';

	/**
	 * Will be printed as absolute path
	 */
	final public self $absolute {
		get {
			$clone = clone $this;
			$clone->isAbsolute = true;

			return $clone;
		}
	}

	/**
	 * Will be printed as relative path
	 */
	final public self $relative {
		get {
			$clone = clone $this;
			$clone->isAbsolute = false;

			return $clone;
		}
	}

	/** @var array<int,string> */
	protected array $paths = [];

	private static string $root;

	private bool $isAbsolute = false;

	private ?string $printed = null;

	final protected function __construct(protected string $path)
	{
	}

	final public static function create(string|Stringable|null $path = self::Root): static
	{
		return new static((string) $path);
	}

	final public function addPath(string|Stringable $path): static
	{
		$clone = clone $this;
		$clone->paths[] = (string) $path;

		return $clone;
	}

	private static function normalizePath(string $path): string
	{
		/** @var array<string> $parts */
		$parts = (array) preg_split('~[/\\\\]+~', $path);
		$result = [];

		foreach ($parts as $index => $part) {
			if ($part === '..' && end($result) !== '.' && end($result) !== '..') {
				array_pop($result);
			} elseif ($index === 0 || (!empty($part) && $part !== '.')) {
				$result[] = $part;
			}
		}

		return empty($result) ? DIRECTORY_SEPARATOR : implode(DIRECTORY_SEPARATOR, $result) . DIRECTORY_SEPARATOR;
	}

	private static function getRoot(): string
	{
		if (isset(self::$root)) {
			return self::$root;
		}

		$root = null;

		if (class_exists(InstalledVersions::class) && $path = InstalledVersions::getRootPackage()['install_path']) {
			$root = (string) $path;
		} elseif ($path = getcwd()) {
			$root = $path;
		} else {
			$debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

			if ($path = end($debug)['file'] ?? null) {
				$path = dirname($path);

				if (PHP_SAPI !== 'cli') {
					$root = $path;
				} else {
					$filename = $_SERVER['SCRIPT_FILENAME'];
					if (is_string($filename)) {
						$script = dirname($filename);
						$root = (string) preg_replace('~' . preg_quote($script, '~') . '$~', '', $path);
					}
				}
			}
		}

		if (!$root) {
			throw new RuntimeException('Can not obtain root directory.');
		}

		return self::$root = self::normalizePath($root);
	}

	private function print(): string
	{
		$path = (string) $this->path;

		if (!empty($this->paths)) {
			$path .= DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $this->paths);
		}

		$path = self::normalizePath($path);
		$root = self::getRoot();

		if ($this->isAbsolute && !str_starts_with($path, $root)) {
			$path = self::normalizePath($root . DIRECTORY_SEPARATOR . $path);
		} elseif (!$this->isAbsolute && str_starts_with($path, $root)) {
			$path = self::normalizePath(str_replace($root, '', $path));
		}

		return $path;
	}

	final public function __clone()
	{
		$this->printed = null;
	}

	final public function __toString(): string
	{
		return $this->printed ??= $this->print();
	}

}
