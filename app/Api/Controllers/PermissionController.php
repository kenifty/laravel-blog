<?php


namespace App\Api\Controllers;


use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $this->authorize('index',Permission::class);
    }
}
