<?php 
require_once realpath(__DIR__ . '/../vendor/autoload.php');
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type : application/json');

// Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$request_method = $_SERVER['REQUEST_METHOD'];

function auth(){
 try{
    $request_method = $_SERVER['REQUEST_METHOD'];
if($request_method == 'POST'){
    $body = file_get_contents('php://input');

    $payload = json_decode($body, true);

    if($payload == null){
        http_response_code(400);
        return json_encode(['message'=> 'provide user_name and password']);
    }

    if(!isset($payload['user_name']) || !isset($payload['password'])){
       http_response_code(400);
       return json_encode(['message'=> 'incorrect user_name and password']); 
    }

    $username = $payload['user_name'];

    $password = $payload['password'];

    if($username == null || $password == null){
        http_response_code(400);
        return json_encode(['message'=> 'incorrect user_name and password']);
    }

    if($username != $_ENV['UPG_AUTH_USERNAME'] || $password != $_ENV['UPG_AUTH_PASSWORD'] ){
      http_response_code(401);

      return json_encode(['message'=> 'unauthorized access']);
    }

    // json web token
    $jwt_payload = ['iat' => time(),'exp' => time() + 3600,'sub' => 123456,'user_name'=> $username];

    $secretKey = $_ENV['UPG_AUTH_SECRET_KEY'];

    $jwt = JWT::encode($jwt_payload, $secretKey, 'HS256');

    http_response_code(200);

    return json_encode(['data'=> ['token'=> 'Bearer '.$jwt]]);
}else{
    http_response_code(404);
    return json_encode(['message'=> 'API not found']);
}
 }catch(Exception $e){
   http_response_code(500);
   return json_encode(['message'=> 'internal server error']);
 }
}

echo auth();
?>