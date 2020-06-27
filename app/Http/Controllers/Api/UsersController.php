<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        $search = request('name');

        $val =  User::where('name', 'LIKE', "$search%")
                ->pluck('name')
                ->take(5);

        return $val->map(function ($name) {
            return ['value' => $name];
        });
    }
}
