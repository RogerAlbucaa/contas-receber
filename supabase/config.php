<?php

class SupabaseConfig {
    private static $instance = null;
    private $supabaseUrl;
    private $supabaseKey;
    private $headers;

    private function __construct() {
        $this->supabaseUrl = $_ENV['SUPABASE_URL'];
        $this->supabaseKey = $_ENV['SUPABASE_KEY'];
        $this->headers = [
            'apikey: ' . $this->supabaseKey,
            'Authorization: Bearer ' . $this->supabaseKey,
            'Content-Type: application/json',
            'Prefer: return=minimal'
        ];
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new SupabaseConfig();
        }
        return self::$instance;
    }

    public function getUrl() {
        return $this->supabaseUrl;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function request($endpoint, $method = 'GET', $data = null) {
        $ch = curl_init($this->supabaseUrl . $endpoint);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        
        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('Erro na requisição: ' . $error);
        }
        
        return json_decode($response, true);
    }
}
