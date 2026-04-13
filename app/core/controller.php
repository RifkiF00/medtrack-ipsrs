<?php
class Controller {
    // Fungsi untuk memanggil tampilan (UI/HTML)
    public function view($view, $data = []) {
        require_once '../app/views/' . $view . '.php';
    }

    // Fungsi untuk memanggil query database
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }
}