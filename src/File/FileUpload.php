<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Matraux\FileSystem\Folder\Folder;
use Nette\Http\FileUpload as NetteFileUpload;
use RuntimeException;

/**
 * @mixin File
 */
trait FileUpload
{

	final public NetteFileUpload $fileUpload
	{
		get {
			return new NetteFileUpload([
				'name' => $this->name,
				'type' => $this->type,
				'size' => $this->size,
				'tmp_name' => (string) $this,
				'error' => 0,
			]);
		}
	}

	/**
	 * Create file from FileUpload
	 */
	final public static function fromFileUpload(NetteFileUpload $fileUpload, ?Folder $folder = null): static
	{
		$folder ??= Folder::create(sys_get_temp_dir());
		$folder = (string) $folder;

		$file = $folder . $fileUpload->sanitizedName;

		if (!@rename($fileUpload->temporaryFile, $file)) {
			throw new RuntimeException(sprintf('Unable to rename file "%s" to "%s".', $fileUpload->temporaryFile, $file));
		}

		if (!@chmod($file, 0644)) {
			throw new RuntimeException(sprintf('Unable to chmod file "%s" to mode %s.', $file, 0644));
		}

		return new static($file);
	}

}
