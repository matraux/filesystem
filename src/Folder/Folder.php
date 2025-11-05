<?php declare(strict_types = 1);

namespace Matraux\FileSystem\Folder;

use Composer\InstalledVersions;
use Matraux\FileSystem\Exception\FolderRootException;
use Nette\Utils\FileSystem;
use Stringable;

class Folder implements Stringable
{
	protected const string Root = './';

	/**
	 * Will be printed as absolute path
	 */
	final public self $absolute
	{
		get {
			return $this->absolute ??= self::getInstanceCache($this->paths, true);
		}
	}

	/**
	 * Will be printed as relative path
	 */
	final public self $relative
	{
		get {
			return $this->relative ??= self::getInstanceCache($this->paths, false);
		}
	}

	final public bool $exists
	{
		get {
			return is_dir((string) $this->absolute);
		}
	}

	/** @var array<int,string> */
	final protected array $paths = [];

	final protected bool $isAbsolute = false;

	final protected string $root
	{
		get {
			if (isset(self::$rootCache)) {
				return self::$rootCache;
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
				throw new FolderRootException('Unable to resolve root directory.');
			}

			return self::$rootCache = self::normalizePath($root);
		}
	}

	final protected string $print
	{
		get {
			if (isset($this->print)) {
				return $this->print;
			}

			$path = implode(DIRECTORY_SEPARATOR, $this->paths);
			$path = self::normalizePath($path);

			if ($this->isAbsolute && !str_starts_with($path, $this->root) && !FileSystem::isAbsolute($path)) {
				$path = self::normalizePath($this->root . DIRECTORY_SEPARATOR . $path);
			} elseif (!$this->isAbsolute && str_starts_with($path, $this->root)) {
				$path = substr($path, strlen($this->root));
			}

			return $this->print = $path;
		}
	}

	private static string $rootCache;

	/** @var array<string,static> */
	private static array $instanceCache = [];

	/** @param array<int,string> $paths */
	final protected function __construct(array $paths = [], bool $isAbsolute = false)
	{
		$this->paths = $paths;
		$this->isAbsolute = $isAbsolute;
	}

	final public static function create(string|Stringable|null $path = self::Root): static
	{
		return self::getInstanceCache([(string) $path], false);
	}

	final public function addPath(string|Stringable $path): static
	{
		$paths = $this->paths;
		$paths[] = (string) $path;

		return self::getInstanceCache($paths, $this->isAbsolute);
	}

	final public function init(): static
	{
		if (!$this->exists) {
			FileSystem::createDir((string) $this->absolute);
		}

		return $this;
	}

	/**
	 * @param array<int,string> $paths
	 */
	final protected static function getInstanceCache(array $paths, bool $isAbsolute): static
	{
		$index = $isAbsolute . '|' . implode('|', $paths);

		return self::$instanceCache[$index] ??= new static($paths, $isAbsolute);
	}

	final protected static function normalizePath(string $path): string
	{
		if (!$parts = preg_split('~[/\\\\]+~', $path)) {
			return DIRECTORY_SEPARATOR;
		}

		$result = [];
		foreach ($parts as $index => $part) {
			if ($part === '..' && end($result) !== '.' && end($result) !== '..') {
				array_pop($result);
			} elseif ($index === 0 || ($part !== '' && $part !== '.')) {
				$result[] = $part;
			}
		}

		return implode(DIRECTORY_SEPARATOR, $result) . DIRECTORY_SEPARATOR;
	}

	final public function __toString(): string
	{
		return $this->print;
	}

}