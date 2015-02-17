## [Shared Items](https://developers.box.com/docs/#shared-items)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$sharedItems = new \Romby\Box\Services\SharedItems($http);
```

### [Get A Shared Item](https://developers.box.com/docs/#shared-items-get-a-shared-item)
```php
/* @param string      $token      the OAuth token.
 * @param string      $sharedLink the shared item to get.
 * @param string|null $password   the password for the shared itembo.
 * @return array the response.
 */
$sharedItems->get($token, $sharedLink, $password = null);
```