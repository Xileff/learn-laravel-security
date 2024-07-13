<?php

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class SimpleProvider implements UserProvider
{
  private GenericUser $user;

  public function __construct()
  {
    $this->user = new GenericUser([
      'id' => 'felix',
      'name' => 'felix',
      'token' => 'secret'
    ]);
  }

  public function retrieveById($identifier)
  {
  }

  public function retrieveByToken($identifier, $token)
  {
  }

  public function updateRememberToken(Authenticatable $user, $token)
  {
  }

  public function retrieveByCredentials(array $credentials)
  {
    if ($credentials['token'] == $this->user->__get('token')) {
      return $this->user;
    }
    return null;
  }

  public function validateCredentials(Authenticatable $user, array $credentials)
  {
  }
}
