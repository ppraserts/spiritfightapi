<?php
use Firebase\JWT\JWT;
use Tuupola\Base62;
use Doctrine\DBAL\Connection as DoctrineConnection;

// Routes
$app->post("/api/token",  function ($request, $response, $args) use ($container){
    $settings = $container->get('settings');
 
  	$requested_scopes = $request->getParsedBody() ?: [];
 
    $now = new DateTime();
    $future = new DateTime($settings['JWT_TokenLifeTime']);
    $server = $request->getServerParams();
    $jti = (new Base62)->encode(random_bytes(16));

    if($server["PHP_AUTH_USER"] == ""){
        $user = "guest";
        $role =  ["guest"];
    }
    else{

        $username = $server["PHP_AUTH_USER"];
        $password = $server["PHP_AUTH_PW"];

        if($username == "superadmin" && $password == "P@ssw0rd") 
        {
            $user = $server["PHP_AUTH_USER"];
            $role = ["Admin", "SuperUser"];
        }
        else{
             return $response->withStatus(401);
        }
       
    }

    $payload = [
        "iat" => $now->getTimeStamp(),
        "exp" => $future->getTimeStamp(),
        "jti" => $jti,
        "sub" => $user,
        "roles"=> $role
    ];
    $secret = $settings['JWT_Secret'];
    $token = JWT::encode($payload, $secret, "HS256");
    $data["token"] = $token;
    $data["expires"] = $future->getTimeStamp();
    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->get('/api/home', function ($request, $response) use ($container){
    //$this->logger->info("/api/home");
    //JWT::decode(, $secret, "HS256");
    //echo $this->jwt->roles;
    $data = array('message' => 'Welcome to REST API');
    return $response->withJson($data);
});

$app->get('/api/table', function ($request, $response) use ($container){
    
    $data = array();
    $settings = $container->get('settings');
    $sql = "SHOW TABLES";
    $result = $container['db']::select($sql);
    foreach ($result as $tableResult => $tableobj) { 
        $table = $tableobj->{'Tables_in_' . $settings['db']['database']};
        array_push($data, [
            "name" => $table,
            "url" => $settings["REST_Url"]."/api/table/".$table,
            "info" => $settings["REST_Url"]."/api/schema/".$table
        ]);
    }
    return $response->withJson($data);
});

$app->get('/api/table/{tablename}', function ($request, $response) use ($container){
    
    $tablename = $request->getAttribute('tablename');
    $data = array();
    if($tablename != "")
    {
        $settings = $container->get('settings');
        $data = $container['db']->table($tablename)->get();
    }
    return $response->withJson($data);
});

$app->get('/api/table/{tablename}/{id}', function ($request, $response) use ($container){
    
    $tablename = $request->getAttribute('tablename');
    $id = $request->getAttribute('id');
    $data = array();
    if($tablename != "" && $id != "")
    {
        $settings = $container->get('settings');
        $data = $container['db']->table($tablename)->where('id', '=', $id)->get();
    }
    return $response->withJson($data);
});

$app->get('/api/schema/{tablename}', function ($request, $response) use ($container){

    $tablename = $request->getAttribute('tablename');
    $data = array();
    if($tablename != "")
    {
        $sql = "SHOW COLUMNS FROM " . $tablename;
        $result = $container['db']::select($sql);
        foreach ($result as $colResult => $colobj) { 
            array_push($data, [
                "name" => $colobj->Field,
                "type" => $colobj->Type
            ]);
        } 
    }
    return $response->withJson($data);
});