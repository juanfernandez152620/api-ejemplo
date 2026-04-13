<?php

class apiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Executes the login Stored Procedure and returns the status code.
     */
    public function loginUser($email, $password)
    {
        // 1. Establish connection using your Database class
        $conexion = $this->db->connect();

        // 2. Sanitize inputs to prevent SQL injection during the CALL
        $email_sql = mysqli_real_escape_string($conexion, $email);
        $pass_sql = mysqli_real_escape_string($conexion, $password);

        // 3. Prepare the call to the Stored Procedure
        $consulta = "CALL sp_loginUser('$email_sql', '$pass_sql')";
        
        // 4. Execute the query
        $resultado = mysqli_query($conexion, $consulta);

        if ($resultado) {
            // Fetch the status and message defined in the SP
            $respuesta = $resultado->fetch_assoc();
            
            // Critical for Stored Procedures: free the result to avoid "Commands out of sync"
            mysqli_free_result($resultado);
        } else {
            // Error handling if the query fails to execute
            $respuesta = [
                "status" => 500, 
                "message" => "Database error: " . mysqli_error($conexion)
            ];
        }

        // 5. Close connection
        mysqli_close($conexion);
        
        return $respuesta;
    }
}

?>
