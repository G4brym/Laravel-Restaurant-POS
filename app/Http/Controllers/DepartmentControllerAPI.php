<?php

namespace App\Http\Controllers;

use App\Http\Resources\Department as DepartmentResource;

use Illuminate\Http\Request;
use App\Department;

class DepartmentControllerAPI extends Controller
{
    //
    public function index()
    {
        return DepartmentResource::collection(Department::all());
    }
}
