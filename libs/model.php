<?php
    class Model{
        function __construct(){
            $this->db = new Database();
            $this->db2024 = new Database2024();
            $this->db2025 = new Database2025();
        }
    }