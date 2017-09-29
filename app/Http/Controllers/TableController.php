<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct()
    {
       // Apply the jwt.auth middleware to all methods in this controller
       // except for the authenticate method. We don't want to prevent
       // the user from retrieving their token if they don't already have it
       //$this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }
    
    public function index()
    {
        $schema = \DB::getDoctrineSchemaManager();
        $result = $schema->listTableNames();
        if(count($result)==0)
            return response()->json(null, 404);
        else
        {
            $listtable = array();
            foreach ($result as $table) { 
                array_push($listtable, [
                                            "name" => $table,
                                            "url" => url('/')."/api/".$table,
                                            "info" => url('/')."/api/tables/".$table
                                        ]);
            }
            return $listtable;
        }
    }
 
    public function show($id)
    {
        $schema = \DB::getDoctrineSchemaManager();
        $result = $schema->listTableColumns($id);
        if(count($result)==0)
            return response()->json(null, 404);
        else
        {
            $listtable = array();
            foreach ($result as $col) { 
                array_push($listtable, [
                                            "name" => $col->getName(),
                                            "type" => $col->getType()->getName()
                                        ]);
            }
            return $listtable;
        }
    }
}