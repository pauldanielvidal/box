<?php

namespace Romby\Box\Services;

class Search extends AbstractService {

    /**
     * Query the Box API with the given search string and options.
     *
     * @param string $token   the OAuth2 token.
     * @param string $query   the search string.
     * @param array  $options the options to modify the query with.
     * @return array the response.
     */
    public function query($token, $query, $options = [])
    {
        $options['query'] = $query;

        return $this->getQuery($this->getFullUrl('/search'), $token, ['query' => $options]);
    }

}
