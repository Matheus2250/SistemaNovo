<?php
require_once __DIR__ . '/../helpers/auth.php';

class HomeController extends Controller
{
    public function index()
    {
        requireLogin();
        // Redirecionar para a tela de seleção de módulos
        header('Location: ' . BASE_URL . 'modulos/index');
        exit;
    }
}
