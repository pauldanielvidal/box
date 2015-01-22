<?php

namespace Romby\Box\Services;

class SharedItems extends AbstractService {

    /**
     * Get the shared item.
     *
     * @param string      $token      the OAuth token.
     * @param string      $sharedLink the shared item to get.
     * @param string|null $password   the password for the shared itembo.
     * @return array the response.
     */
    public function get($token, $sharedLink, $password = null)
    {
        $options = [
            'headers' => ['BoxApi' => 'shared_link=' . $sharedLink]
        ];

        if( ! is_null($password)) $options['headers']['BoxApi'] = $options['headers']['BoxApi'] . ',shared_link_password=' . $password;

        return $this->getQuery($this->getFullUrl('/shared_items'), $token, $options);
    }

}
