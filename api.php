<?php

include(dirname(__FILE__) . '/include/config.php');
include(dirname(__FILE__) . '/include/opendb.php');
require_once(dirname(__FILE__) . '/include/api_methods.php');

$method = $_POST['method'];
$email = $_POST['email'];
$password = $_POST['password'];

try {
    if ($method == "checkAuthentication") {
        $user = checkAuthentication($email, $password);
        echo json_encode(array("success" => true));
    } else if ($method == "createUser") {
        if (strlen($email) < 4) {
            throw new Exception("Email must be at least 4 characters");
        }
        if (strlen($password) < 4) {
            throw new Exception("Password must be at least 4 characters");
        }
        try {
            $existing_user = getUserByEmail($email);
            throw new Exception("User exists");
        } catch (UserNotFoundException $e) {
            $created = createUser($email, $password);
            echo json_encode(array("success" => $created));
        }
    } else if ($method == "addHistory") {
        $user = checkAuthentication($email, $password);

        if (!isset($_POST["sprintSeconds"]) || !isset($_POST["restSeconds"]) || !isset($_POST["numSprints"])) {
            throw new Exception("Invalid parameters");
        }

        addHistory($user->id, $_POST["sprintSeconds"], $_POST["restSeconds"], $_POST["numSprints"]);
        echo json_encode(array("success" => true));
    } else if ($method == "getHistory") {
        $user = checkAuthentication($email, $password);
        echo json_encode(array("history" => getHistory($user->id)));
    } else {
        throw new Exception("Unknown method");
    }
} catch (Exception $e) {
    echo json_encode(array("error" => $e->getMessage()));
}

include(dirname(__FILE__) . '/include/closedb.php');

?>
