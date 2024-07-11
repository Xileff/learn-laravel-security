<?php

namespace App\Providers\Guard;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;


class TokenGuard implements Guard
{
  use GuardHelpers;

  private Request $request;

  // 1. Provider utk tentuin model mana yg dipake
  // 2. Request ya requestnya
  public function __construct(UserProvider $provider, Request $request)
  {
    $this->provider = $provider;
    $this->request = $request;
  }

  // Biar 1 instance TokenGuard bisa dipake utk setiap req baru
  public function setRequest(Request $request)
  {
    $this->request = $request;
  }

  // Buat get data user saat ini
  public function user()
  {
    // Kalo user sudah terautentikasi, lgsg return datanya
    if ($this->user != null) {
      return $this->user;
    }

    // Kalo blm, coba cek dulu berdasarkan tokennya
    $token = $this->request->header("API-Key");
    if ($token) {
      $this->user = $this->provider->retrieveByCredentials(['token' => $token]); // sesuai kolom db
    }

    // Bisa data user atau null
    return $this->user;
  }

  public function validate(array $credentials = [])
  {
    return $this->provider->validateCredentials($this->user, $credentials);
  }
}
