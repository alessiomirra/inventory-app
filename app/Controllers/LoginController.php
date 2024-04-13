<?php 

namespace App\Controllers; 

use App\Models\User;
use PDO; 

class LoginController extends BaseController
{
    public function __construct(
        protected ?PDO $conn
    )
    {
        parent::__construct($conn);
        $this->layout = "layout/auth.tpl.php";
    }

    private function generateToken() {
        /*
        * Generate token to pass in form
        */

        $bytes = random_bytes(32);

        $token = bin2hex($bytes);
        $_SESSION['csrf'] = $token;
        return $token;
    }

    public function showLogin() {
        /*
        * Show login page
        */

        $this->content = view(
            'login', 
            [
                'token' => $this->generateToken()
            ]
        );
    }

    public function login()
    {
        /*
        * Login 
        */

        $token = $_POST["_csrf"] ?? "";
        $email = $_POST["email"] ?? ""; 
        $password = $_POST["password"] ?? "";

        $result = $this->verifyLogin($email, $password, $token);

        $header = strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');

        if ($result['success']){
            session_regenerate_id();
            $_SESSION['loggedin'] = true; 
            $_SESSION['userID'] = $result['user']['id'];
            unset($result['user']['password']);
            $_SESSION['userData'] = $result['user'];
            $_SESSION["cart"] = [];
        } else {
            $_SESSION['message'] = $result['message'];
        };

        if ($header === 'XMLHTTPREQUEST'){
            ob_end_clean();
            echo json_encode($result);
            exit;
        } else {
            $result['success'] ? redirect('/') : redirect('/login');
        }
    }

    private function verifyLogin($email, $password, $token) :array
    {
        /*
        * Verify Login data
        */

        $result = [
            'message' => "USER LOGGED IN", 
            'success' => true 
        ]; 

        if ($token !== $_SESSION['csrf']){
            $result = [
                'message' => 'TOKEN MISMATCH', 
                'success' => false
            ];
            return $result; 
        }; 

        $email = filter_var($email, FILTER_DEFAULT);
        if (!$email){
            $result = [
                'message' => 'ENTER AN EMAIL', 
                'success' => false
            ];
            return $result;
        }; 

        if (strlen($password) < 6){
            $result = [
                'message' => 'PASSWORD TOO SMALL', 
                'success' => false
            ];
            return $result; 
        };

        $user = new User($this->conn);
        $resEmail = $user->getUserByEmail($email); 

        if (!$resEmail || !$resEmail->email){
            $result = [
                'message' => 'USER NOT FOUND', 
                'success' => false
            ]; 
            return $result; 
        };

        if (!password_verify($password, $resEmail->password)){
            $result = [
                'message' => 'WRONG PASSWORD', 
                'success' => false 
            ];
            return $result; 
        };

        $result['user'] = (array) $resEmail; 
        return $result; 
    }

    public function logout() {
        /*
        * Logout
        */

        $_SESSION = [];
        redirect('/login');
    }
}