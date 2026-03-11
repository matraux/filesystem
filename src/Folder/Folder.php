<?php declare(strict_types=1);

namespace Matraux\FileSystem\Folder;

use Composer\InstalledVersions;
use RuntimeException;
use Stringable;

/**
 * @property-read self $absolute Will be printed as absolute path
 * @property-read self $relative Will be printed as relative path
 * @property-read bool $exists
 */
class Folder implements Stringable
{
	protected const string Root = './';

	/**
	 * Will be printed as absolute path
	 */
	protected self $cacheAbsolute;

	/**
	 * Will be printed as relative path
	 */
	protected self $cacheRelative;

	/** @var array<int,string> */
	protected array $paths = [];

	protected bool $isAbsolute = false;

	protected string $print;

	private static string $rootCache;

	/** @var array<string,static> */
	private static array $instanceCache = [];

	/** @param array<int,string> $paths */
	final protected function __construct(array $paths = [], bool $isAbsolute = false)
	{
		$this->paths = $paths;
		$this->isAbsolute = $isAbsolute;
	}

	final public static function fromPath(string|Stringable|null $path = self::Root): static
	{
		return self::getInstanceCache([(string) $path], false);
	}

	final public function addPath(string|Stringable $path): static
	{
		$paths = $this->paths;
		$paths[] = (string) $path;

		return self::getInstanceCache($paths, $this->isAbsolute);
	}

	final public function create(): static
	{
		if (!$this->exists) {
			if (!@mkdir((string) $this->absolute, recursive: true)) {
				throw new RuntimeException(sprintf('Unable to create directory "%s".', (string) $this->absolute));
			}
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

	final protected function getExists(): bool
	{
		return is_dir((string) $this->absolute);
	}

	protected function getRoot(): string
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

	protected function print(): string
	{

		if (isset($this->print)) {
			return $this->print;
		}

		$path = implode(DIRECTORY_SEPARATOR, $this->paths);
		$path = self::normalizePath($path);

		if ($this->isAbsolute && !str_starts_with($path, $this->getRoot()) && !(bool) preg_match('#([a-z]:)?[/\\\]|[a-z][a-z0-9+.-]*://#Ai', $path)) {
			$path = self::normalizePath($this->getRoot() . DIRECTORY_SEPARATOR . $path);
		} elseif (!$this->isAbsolute && str_starts_with($path, $this->getRoot())) {
			$path = substr($path, strlen($this->getRoot()));
		}

		return $this->print = $path;

	}

	public function __get(string $name): mixed
	{
		return match ($name) {
			'absolute' => $this->cacheAbsolute ??= self::getInstanceCache($this->paths, true),
			'relative' => $this->cacheRelative ??= self::getInstanceCache($this->paths, false),
			'exists' => $this->getExists(),
			default => throw new RuntimeException(sprintf('Undefined property $%s', $name)),
		};
	}

	final public function __toString(): string
	{
		return $this->print();
	}
}
