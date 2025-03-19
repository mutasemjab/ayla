<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\SectionUser;


class SectionUserController extends Controller
{

     public function index()
     {
        $data = SectionUser::get();
        return response()->json($data);
     }

}

