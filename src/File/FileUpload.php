<?php declare(strict_types = 1);

namespace Matraux\FileSystem\File;

use Matraux\FileSystem\Folder\Folder;
use Nette\Http\FileUpload as NetteFileUpload;
use Nette\IOException;
use Nette\Utils\FileSystem;
use RuntimeException;

/**
 * @mixin File
 */
trait FileUpload
{

	final public NetteFileUpload $fileUpload {
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
	 *
	 * @throws RuntimeException If can not create file
	 * @throws IOException If can not create temporary dir
	 */
	final public static function fromFileUpload(NetteFileUpload $fileUpload, ?Folder $folder = null): static
	{
		$folder ??= Folder::create()->addPath('temp')->addPath('file');
		$folder = (string) $folder;

		$file = $folder . $fileUpload->sanitizedName;
		FileSystem::createDir($folder);
		FileSystem::rename($fileUpload->temporaryFile, $file);
		FileSystem::makeWritable(path: $file, fileMode: 0644);

		return new static($file);
	}

}
