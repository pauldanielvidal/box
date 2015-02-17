# box
Wrapper in PHP for the Box.com API

## Installation via composer

Add the following lines to your composer.json:
```json
"repositories": [  
    {
        "type": "git",
        "url": "https://github.com/henrikromby/box"
    }
],

"require": {
  "henrikromby/box": "dev-master"
}
```
_(until it's submited to packagist)_

## Usage

### Instantiate HTTP Client
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
```

### Instantiate the required library class
```php
$folders = new \Romby\Box\Services\Folders($http);
$files = new \Romby\Box\Services\Files($http);
$comments = new \Romby\Box\Services\Comments($http);
$collaborations = new \Romby\Box\Services\Collaborations($http);
$sharedItems = new \Romby\Box\Services\SharedItems($http);
```

### Example (uploading a file in Laravel)
```php
// Get Input
$file = Input::file('file');
// Extract data from file
$path = $file->getRealPath();
$name = $file->getClientOriginalName();
// Specify the parent_id for the folder
$parent_id = "0" // 0 = root of your box.com folder
// Insert your token here
$token = "Your API Key";

$BoxFile = new \Romby\Box\Services\Files($http);
$resp = $BoxFile->upload($token, $path, $name, $parent);
```

### Documentation
You can refer to the documentation [here](docs/)