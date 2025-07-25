**[Back](../README.md)**


# File
Basic usage
```php
$file = File::fromPath('C:\Users\MATRAUX\Webs\Resources\FileSystem\subFolder\test.txt');

echo $file; // "C:\Users\MATRAUX\Webs\Resources\FileSystem\subFolder\test.txt"

echo $file->name; // "test.txt"

echo $file->basename; // "test"

echo $file->extension; // "txt"

echo $file->path; // "C:\Users\MATRAUX\Webs\Resources\FileSystem\subFolder"

echo $file->relativePath; // ".\subFolder"

echo $file->type; // text/plain

echo $file->size; // 0 (0 bytes for empty file)

echo count($file); // Alias for $size

foreach($file as $part) {
	echo $part; // Part of file content of 1024 length
}

$file->delete(); // Remove permanently file

$file->temporary = true; // File will be removed after PHP instance shutdown

$file->name = 'xyz'; // Rename file to "xyz"

$file->path = 'C:\Users\MATRAUX\Webs\Resources\FileSystem\extraFolder'; // Move file from "subFolder" to "extraFolder"

$file->basename = 'abcd'; // Rename file to "abcd.txt"

$file->extension = 'js'; // Change file extension to "js"
```

Customization
```php
use Matraux\FileSystem\File\File;

/**
 * @property-read int $depth Nette SmartObject propery access
 */
final class CustomFile extends File
{

	protected function getDepth(): int
	{
		return substr_count($this->relativePath, DIRECTORY_SEPARATOR)
	}

}

$file = File::fromPath('C:\Users\MATRAUX\Webs\Resources\FileSystem\subFolder\test.txt');
echo $file->depth; // 6
```