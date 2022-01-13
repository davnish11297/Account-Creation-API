<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../classes/Database.php';
include_once '../classes/ApiMessage.php';

$connection = new Database();
$conn = $connection->dbConnection();
$ApiMessage = new ApiMessage();

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] != 'POST') {
    $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(404), 'Invalid Request Method');
} elseif (!isset($data->username) || !isset($data->password) || empty(trim($data->username)) || empty(trim($data->password))) {
    $fields = ['fields' => ['username', 'password']];
    $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'Please Fill in all Required Fields!', $fields);
} else {
    $username = trim($data->username);
    $password = trim($data->password);

    if (strpos($username, ' ')) {
        $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'Your username cannot have spaces. Please try again!');
    } elseif (strlen($password) < 8) {
        $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'Your password must be at least 8 characters long!');
    } elseif (strlen($username) < 3) {
        $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'Your name must be at least 3 characters long!'); 
    } else {
        try {
            $check_username = "SELECT `Username` FROM `account_users` WHERE `Username`=:username";
            $check_username_stmt = $conn->prepare($check_username);
            $check_username_stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $check_username_stmt->execute();

            if ($check_username_stmt->rowCount()) {
                $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(422), 'This username already in use!');
            } else {

                $insert_query = "INSERT INTO `account_users`(`Username`,`Password`) VALUES(:username,:password)";
                $insert_stmt = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':username', htmlspecialchars(strip_tags($username)), PDO::PARAM_STR);
                $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);

                $insert_stmt->execute();

                $returnData = $ApiMessage->Message(true, $ApiMessage->http_response_code(201), 'You have successfully registered.');
            }
        } catch (PDOException $ex) {
            $returnData = $ApiMessage->Message(false, $ApiMessage->http_response_code(500), $ex->getMessage());
        }
    }
}

echo json_encode($returnData, JSON_PRETTY_PRINT);