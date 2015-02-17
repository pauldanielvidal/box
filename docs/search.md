## [Search](https://developers.box.com/docs/#search)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$search = new \Romby\Box\Services\Search($http);
```

### [Searching for Content](https://developers.box.com/docs/#search-searching-for-content)
```php
/* @param string $token   the OAuth2 token.
 * @param string $query   the search string.
 * @param array  $options the options to modify the query with.
 * @return array the response.
 */
$search->query($token, $query, $options = []);
```