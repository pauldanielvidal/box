<?php

namespace Romby\Box\Services;

class Users extends AbstractService {

    /**
     * Retrieves information about the user who is currently logged in i.e. the user for whom this auth token was
     * generated.
     *
     * @param string $token the OAuth Token.
     * @return array the user.
     */
    public function me($token)
    {
        return $this->getQuery($this->getFullUrl('/users/me'), $token);
    }

    /**
     * Used to provision a new user in an enterprise. This method only works for enterprise admins.
     *
     * @param string $token      the OAuth Token.
     * @param string $login      the email address this user uses to login
     * @param string $name       the name of this user.
     * @param array  $properties the properties of the user.
     * @return array the new user.
     */
    public function create($token, $login, $name, $properties = [])
    {
        $options = [
            'json' => $properties
        ];

        $options['json']['login'] = $login;

        $options['json']['name'] = $name;

        return $this->postQuery($this->getFullUrl('/users'), $token, $options);
    }

}
