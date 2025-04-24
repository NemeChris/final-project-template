<?php

namespace app\core;

use app\models\User;
use DateTime;
use DateInterval;

class AuthHelper
{
    public static function authRoute() {
        if (!isset($_SESSION['id'])) {
            http_response_code(401);
            header('Location: login');
            exit();
        }
    }

    public static function nonAuthRoute() {
        if (isset($_SESSION['id'])) {
            http_response_code(401);
            header('Location: /');
            exit();
        }
    }

    public static function checkSession() {
        session_start();
        if (isset($_SESSION['id'])) {
            $userModel = new User();
            $user = $userModel->getUserByID($_SESSION['id']);
            $now = new DateTime();
            $formattedCurrentTime = $now->format('Y-m-d H:i:s'); 
            if ($user['sessionExpiration'] < $formattedCurrentTime) {
                AuthHelper::endSession();
            }
        }
    }

    private static function ensureSessionStarted() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function startSession($user) {
        if (!$user || !isset($user['id'])) {
            return false;
        }
        self::ensureSessionStarted(); 
        session_regenerate_id(true);
        $_SESSION['id'] = $user['id'];           
        $_SESSION['username'] = $user['username'];

        $now = new DateTime();
        $hours = 8;
        $modified = (clone $now)->add(new DateInterval("PT{$hours}H"));
        $toString = $modified->format('Y-m-d H:i:s');
        $userModel = new User();
        $user = $userModel->getUserByID($_SESSION['id']);
        if (!$user) {
            return false;
        }
        $userModel->updateUserSessionExp(['sessionExpiration' => $toString, 'id' => $user['id']]);
    }

    public static function endSession() {
        $_SESSION = [];
        $param    = session_get_cookie_params();
        setcookie(session_name(), '', time() - 2400, $param['path'], $param['domain'],
            $param['secure'], $param['httponly']);
        session_destroy();
    }

}
