<?php

class UserNotFoundException extends Exception {}
class AuthenticationFailedException extends Exception {}

function checkAuthentication($arg_email, $arg_password) {
    $email = mysql_real_escape_string($arg_email);
    $password = sha1($arg_password);

    $res = mysql_query("select * from users where email='{$email}' and password='{$password}'");
    if (mysql_num_rows($res) > 0) {
        $user = mysql_fetch_object($res);
        return $user;
    } else {
        throw new AuthenticationFailedException("Invalid email/password");
    }
}

function createUser($arg_email, $arg_password) {
    $email = mysql_real_escape_string($arg_email);
    $password = sha1($arg_password);

    $res = mysql_query("insert into users (email, password) values ('{$email}', '{$password}')");
    return true;
}

function getUserByEmail($arg_email) {
    $email = mysql_real_escape_string($arg_email);

    $res = mysql_query("select * from users where email='{$email}'");
    if (mysql_num_rows($res) == 0) {
        throw new UserNotFoundException("User not found");
    } else {
        return mysql_fetch_object($res);
    }
}

function addHistory($arg_user_id, $arg_sprint_seconds, $arg_rest_seconds, $arg_num_sprints) {
    $user_id = mysql_real_escape_string($arg_user_id);
    $sprint_seconds = mysql_real_escape_string($arg_sprint_seconds);
    $rest_seconds = mysql_real_escape_string($arg_rest_seconds);
    $num_sprints = mysql_real_escape_string($arg_num_sprints);

    $res = mysql_query("insert into history (userId, sprintSeconds, restSeconds, numSprints) values ('{$user_id}', '{$sprint_seconds}', '{$rest_seconds}', '{$num_sprints}')");
    return $res;
}

function getHistory($arg_user_id) {
    $user_id = mysql_real_escape_string($arg_user_id);

    $list = array();
    $res = mysql_query("select * from history where userId='{$user_id}' order by performedAt desc");
    while (($row = mysql_fetch_object($res)) != null) {
        array_push($list, $row);
    }
    return $list;
}

?>
