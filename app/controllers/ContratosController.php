<?php
require_once __DIR__ . '/../helpers/auth.php';

class ContratosController extends Controller
{
    public function __construct()
    {
        // Controller para contratos (módulo futuro)
    }

    /** Exibe página em desenvolvimento */
    public function index()
    {
        requireLogin();
        
        // Verificar se usuário tem permissão
        if (!in_array($_SESSION['usuario']['tipo_usuario'], ['admin', 'operador'])) {
            header('Location: ' . BASE_URL . 'modulos/index');
            exit;
        }
        
        $this->view('contratos/index', [], 'Contratos');
    }
}