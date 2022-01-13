<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../classes/Database.php';
include_once '../classes/ApiMessage.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();
$ApiMessage = new ApiMessage();

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if($_SERVER["REQUEST_METHOD"] != "POST") {
    $returnData = $ApiMessage->Message(false,$ApiMessage->http_response_code(404),'Invalid Request Method');
} elseif (!isset($data->username) || !isset($data->password) || empty(trim($data->username)) || empty(trim($data->password))) {

    $fields = ['fields' => ['username','password']];
    $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'Please Fill in all Required Fields!', $fields);
} else {
    $username = trim($data->username);
    $password = trim($data->password);

    if(strlen($password) < 8) {
        $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'Your password must be at least 8 characters long!');
    } else {
        try{
            $fetch_user_by_username = "SELECT * FROM `account_users` WHERE `Username`=:username";
            $query_stmt = $conn->prepare($fetch_user_by_username);
            $query_stmt->bindValue(':username', $username,PDO::PARAM_STR);
            $query_stmt->execute();

            // IF THE USER IS FOUNDED BY EMAIL
            if ($query_stmt->rowCount()) {
                $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                $check_password = password_verify($password, $row['Password']);

                // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
                // IF PASSWORD IS CORRECT THEN SEND THE LOGIN TOKEN
                if($check_password){
                    $returnData = $ApiMessage->Message(true, $ApiMessage->http_response_code(200), 'You have successfully logged in.');

                // IF INVALID PASSWORD
                } else {
                    $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'Invalid Password!');
                }
            } else {
                $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'Invalid Username!');
            }
        }
        catch(PDOException $e){
            $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(500), $e->getMessage());
        }
    }
}

echo json_encode($returnData, JSON_PRETTY_PRINT);