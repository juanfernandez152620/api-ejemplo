<?php

class Api extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Endpoint: /api/login
     * Handles the POST request from the frontend
     */
    public function login()
    {
        // 1. Set headers for JSON response and CORS
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        // 2. Get the raw POST data (JSON)
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        // 3. Basic validation
        if (!isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(["status" => 400, "message" => "Faltan credenciales"]);
            return;
        }

        // 4. Call the model method that executes the Stored Procedure
        // Your libs/app.php automatically loads the model into $this->model
        $res = $this->model->loginUser($data->email, $data->password);

        // 5. Return the status code and message from the Database
        http_response_code($res['status']);
        echo json_encode($res);
    }
}

?>
