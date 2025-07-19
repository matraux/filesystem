<?php declare(strict_types = 1);

namespace Matraux\FileSystem\Folder;

use Composer\InstalledVersions;
use RuntimeException;
use Stringable;

class Folder implements Stringable
{

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
	final protected array $paths = [];

	final protected bool $isAbsolute = false;

	final protected string $path;

	final protected function __construct(string $path)
	{
		$this->path = $path;
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

	final protected static function normalizePath(string $path): string
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

	final protected string $root {
		get {
			if (isset($this->root)) {
				return $this->root;
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

			return $this->root = self::normalizePath($root);
		}
	}

	final protected ?string $print {
		get {
			if(isset($this->print)) {
				return $this->print;
			}

			$path = $this->path;

			if (!empty($this->paths)) {
				$path .= DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $this->paths);
			}

			$path = self::normalizePath($path);

			if ($this->isAbsolute && !str_starts_with($path, $this->root)) {
				$path = self::normalizePath($this->root . DIRECTORY_SEPARATOR . $path);
			} elseif (!$this->isAbsolute && str_starts_with($path, $this->root)) {
				$path = self::normalizePath(str_replace($this->root, '', $path));
			}

			return $this->print = $path;
		}
	}

	final public function __clone()
	{
		$this->print = null;
	}

	final public function __toString(): string
	{
		return (string) $this->print;
	}

}
