<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CRUDController extends Controller
{
    public function __construct()
    {
       // Apply the jwt.auth middleware to all methods in this controller
       // except for the authenticate method. We don't want to prevent
       // the user from retrieving their token if they don't already have it
       //$this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }
    
    public function index($table)
    {
        if (!\Schema::hasTable($table)) {
            return response()->json(null, 404);
        }
        return \DB::table($table)->paginate(15);
    }
 
    public function show($table,$id)
    {
        if (!\Schema::hasTable($table)) {
            return response()->json(null, 404);
        }

        $result = \DB::table($table)->where('id', $id)->get();
        if(count($result)==0)
            return response()->json(null, 404);
        else
            return $result;
    }

    public function store(Request $request,$table)
    {
        if (!\Schema::hasTable($table)) {
            return response()->json(null, 404);
        }

        $id = \DB::table($table)->insertGetId($request->all());
        $result = \DB::table($table)->where('id', $id)->get();
        return response()->json($result,201);
    }

    public function update(Request $request,$table,$id)
    {
        if (!\Schema::hasTable($table)) {
            return response()->json(null, 404);
        }

        \DB::table($table)
                ->where('id', $id)  
                ->limit(1) 
                ->update($request->all()); 
        $result = \DB::table($table)->where('id', $id)->get();
        return $result;
    }

    public function delete(Request $request,$table,$id)
    {
        if (!\Schema::hasTable($table)) {
            return response()->json(null, 404);
        }

        \DB::table($table)
                ->where('id', $id)  
                ->limit(1)
                ->delete();
        return response()->json(null, 204);
    }
}