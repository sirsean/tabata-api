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
        $rval = true;
    } else if ($method == "createUser") {
        try {
            $existing_user = getUserByEmail($email);
            throw new Exception("User exists");
        } catch (UserNotFoundException $e) {
            $rval = createUser($email, $password);
        }
    } else if ($method == "addHistory") {
        $user = checkAuthentication($email, $password);

        if (!isset($_POST["sprintSeconds"]) || !isset($_POST["restSeconds"]) || !isset($_POST["numSprints"])) {
            throw new Exception("Invalid parameters");
        }

        $rval = addHistory($user->id, $_POST["sprintSeconds"], $_POST["restSeconds"], $_POST["numSprints"]);
    } else if ($method == "getHistory") {
        $user = checkAuthentication($email, $password);
        $rval = getHistory($user->id);
    } else {
        throw new Exception("Unknown method");
    }

    echo json_encode(array("response" => $rval));
} catch (Exception $e) {
    echo json_encode(array("error" => $e->getMessage()));
}

include(dirname(__FILE__) . '/include/closedb.php');

?>
