<?php declare(strict_types=1);

namespace Matraux\FileSystem\Folder;

use Composer\InstalledVersions;
use RuntimeException;

/**
 * @property-read self $absolute Will be printed as absolute path
 * @property-read self $relative Will be printed as relative path
 * @property-read bool $exists
 */
class Folder
{
	private const Root = './';

	private static string $rootCache;

	/** @var array<string,static> */
	private static array $instanceCache = [];

	/**
	 * Will be printed as absolute path
	 */
	private self $cacheAbsolute;

	/**
	 * Will be printed as relative path
	 */
	private self $cacheRelative;

	/** @var array<int,string> */
	private array $paths = [];

	private bool $isAbsolute = false;

	private string $print;

	/** @param array<int,string> $paths */
	final private function __construct(array $paths = [], bool $isAbsolute = false)
	{
		$this->paths = $paths;
		$this->isAbsolute = $isAbsolute;
	}

	final public static function fromPath(?string $path = self::Root): self
	{
		return self::getInstanceCache([(string) $path], false);
	}

	/**
	 * @param string $path
	 */
	final public function addPath($path): self
	{
		$paths = $this->paths;
		$paths[] = $path;

		return self::getInstanceCache($paths, $this->isAbsolute);
	}

	final public function create(): self
	{
		if (!$this->exists) {
			if (!@mkdir((string) $this->absolute, 0777, true)) {
				throw new RuntimeException(sprintf('Unable to create directory "%s".', (string) $this->absolute));
			}
		}

		return $this;
	}

	/**
	 * @param array<int,string> $paths
	 */
	private static function getInstanceCache(array $paths, bool $isAbsolute): self
	{
		$index = implode('|', [static::class, $isAbsolute, ...$paths]);

		return self::$instanceCache[$index] ??= new static($paths, $isAbsolute);
	}

	private static function normalizePath(string $path): string
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

	private static function str_starts_with(string $haystack, string $needle): bool
	{
		return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
	}

	private function getExists(): bool
	{
		return is_dir((string) $this->absolute);
	}

	private function getRoot(): string
	{
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
			throw new RuntimeException('Unable to resolve root directory.');
		}

		return self::$rootCache = self::normalizePath($root);
	}

	private function print(): string
	{
		if (isset($this->print)) {
			return $this->print;
		}

		$path = implode(DIRECTORY_SEPARATOR, $this->paths);
		$path = self::normalizePath($path);

		if ($this->isAbsolute && !self::str_starts_with($path, $this->getRoot()) && !(bool) preg_match('#([a-z]:)?[/\\\]|[a-z][a-z0-9+.-]*://#Ai', $path)) {
			$path = self::normalizePath($this->getRoot() . DIRECTORY_SEPARATOR . $path);
		} elseif (!$this->isAbsolute && self::str_starts_with($path, $this->getRoot())) {
			$path = substr($path, strlen($this->getRoot()));
		}

		return $this->print = $path;
	}

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		switch ($name) {
			case 'absolute':
				return $this->cacheAbsolute ??= self::getInstanceCache($this->paths, true);
			case 'relative':
				return $this->cacheRelative ??= self::getInstanceCache($this->paths, false);
			case 'exists':
				return $this->getExists();
			default:
				throw new RuntimeException(sprintf('Undefined property %s::$%s.', static::class, $name));
		}
	}

	final public function __toString(): string
	{
		return $this->print();
	}
}
