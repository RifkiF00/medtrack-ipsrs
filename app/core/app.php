<?php
class App {
    // Controller dan Method default (jika URL kosong, ini yang dipanggil)
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();

        // 1. Cari file Controller
        if(isset($url[0])) {
            if(file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Cari Method (Fungsi di dalam Controller)
        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Ambil Parameters (Sisa URL)
        if(!empty($url)) {
            $this->params = array_values($url);
        }

        // 4. Jalankan Controller, Method, dan kirim parameter jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Fungsi untuk memecah URL dan membersihkannya dari karakter aneh
    public function parseURL() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}