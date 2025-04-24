<?php
   namespace app\controllers;

   use app\core\AuthHelper;
   use app\models\User;
   
   class AuthController extends Controller {
        public function loginView() {
            AuthHelper::nonAuthRoute();
            $this->returnView('./assets/views/auth/login.html');
        }

        public function login() {
            $inputData = [
                'username' => $_POST['username'] ? $_POST['username'] : false,
                'password' => $_POST['password'] ? $_POST['password'] : false,
            ];

            $user = new User();
            $authedUser = $user->login(
                [
                    'username' => $inputData['username'],
                    'password' => $inputData['password'],
                ]
            );

            if (!$authedUser) {
                // Authentication failed
                http_response_code(401);
                $this->returnJSON([
                    'success' => false,
                    'message' => 'Invalid username or password'
                ]);
            }

            AuthHelper::startSession($authedUser);

            http_response_code(200);
            $this->returnJSON([
                'route' => '/admin'
            ]);
        }

        public function logout() {
            AuthHelper::endSession();
            http_response_code(200);
            $this->returnJSON([
                'route' => '/login'
            ]);
        }
    
   }


?>