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

    /**
     * Get all users in an enterprise.
     *
     * @param string $token the OAuth Token.
     * @return array the users.
     */
    public function all($token)
    {
        return $this->getQuery($this->getFullUrl('/users'), $token);
    }

    /**
     * Get information about a specific user.
     *
     * @param string $token the OAuth Token.
     * @param int    $id    the ID of the user.
     * @return array the user's information.
     */
    public function get($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/users/' . $id), $token);
    }

    /**
     * Update information about a user.
     *
     * @param string $token      the OAuth Token.
     * @param int    $id         the ID of the user.
     * @param array  $properties the properties to update.
     * @return array the updated user.
     */
    public function update($token, $id, $properties)
    {
        return $this->putQuery($this->getFullUrl('/users/' . $id), $token, ['json' => $properties]);
    }

    /**
     * Delete the given user.
     *
     * @param string $token the OAuth Token.
     * @param int    $id    the ID of the user.
     * @return void
     */
    public function delete($token, $id)
    {
        $this->deleteQuery($this->getFullUrl('/users/' . $id), $token);
    }

    /**
     * Get all email aliases for the given user.
     *
     * @param string $token the OAuth Token.
     * @param int    $id    the ID of the user.
     * @return array the email aliases.
     */
    public function getAllEmailAliases($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/users/' . $id . '/email_aliases'), $token);
    }

    /**
     * Create an email alias for the given user.
     *
     * @param string $token the OAuth Token.
     * @param int    $id    the ID of the user.
     * @param string $alias the email alias.
     * @return array the response.
     */
    public function createEmailAlias($token, $id, $alias)
    {
        return $this->postQuery($this->getFullUrl('/users/' . $id . '/email_aliases'), $token, ['json' => ['email' => $alias]]);
    }

}
