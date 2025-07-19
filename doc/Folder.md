**[Back](../README.md)**

# Folder
Basic usage
```php
use Matraux\FileSystem\Folder\Folder;

$folder = Folder::create();
echo $folder; // ".\"

echo $folder->absolute; // "C:\Users\MATRAUX\Webs\Resources\FileSystem\"

echo $folder->addPath('subFolder'); // ".\subFolder\"

echo $folder->addPath('subFolder')->absolute; // "C:\Users\MATRAUX\Webs\Resources\FileSystem\subFolder\"
```

Customization
```php
use Matraux\FileSystem\Folder\Folder;

/**
 * @property-read static $temp Nette SmartObject propery access
 */
final class CustomFolder extends Folder
{

	protected const string Temp = self::Root . 'temp' . DIRECTORY_SEPARATOR;

	public self $temp {
		get {
			$clone = clone $this;
			$clone->path = self::Temp;

			return $clone;
		}
	}

}

$folder = CustomFolder::create();

echo $folder->temp; // ".\temp\"

echo $folder->temp->absolute; // "C:\Users\MATRAUX\Webs\Resources\FileSystem\temp\"

echo $folder->temp->addPath('subFolder'); // ".\temp\subFolder\"

echo $folder->temp->addPath('subFolder')->absolute; // "C:\Users\MATRAUX\Webs\Resources\FileSystem\temp\subFolder\"
```