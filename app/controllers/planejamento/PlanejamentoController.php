<?php
require_once __DIR__ . '/../../helpers/auth.php';

class PlanejamentoController extends Controller
{
    private $planejamentoModel;

    public function __construct()
    {
        $this->planejamentoModel = $this->model('planejamento/PlanejamentoModel');
    }

    public function index()
    {
        requireLogin();
        
        // Parâmetros de paginação
        $paginaAtual = (int) ($_GET['pagina'] ?? 1);
        $porPagina = 15;
        
        // Buscar dados com paginação
        $contratacoes = $this->planejamentoModel->listarContratacoes($paginaAtual, $porPagina);
        $totalContratacoes = $this->planejamentoModel->contarContratacoes();
        $resumoPorStatus = $this->planejamentoModel->resumoContratacoesPorStatus();
        $resumoGeral = $this->planejamentoModel->obterResumoPlanejamento();
        
        // Verificar quais já foram licitadas
        foreach ($contratacoes as &$contratacao) {
            $contratacao['ja_licitada'] = $this->planejamentoModel->verificarSeJaFoiLicitada($contratacao['numero_contratacao']);
        }
        
        // Criar objeto de paginação
        require_once __DIR__ . '/../../helpers/pagination.php';
        $baseUrl = BASE_URL . 'planejamento/index';
        $pagination = new Pagination($totalContratacoes, $porPagina, $paginaAtual, $baseUrl);
        
        $this->view('planejamento/index', [
            'contratacoes' => $contratacoes,
            'resumoPorStatus' => $resumoPorStatus,
            'resumoGeral' => $resumoGeral,
            'pagination' => $pagination
        ], 'Planejamento');
    }

    public function importar()
    {
        requirePlanejamentoOuCoordenador();
        $this->view('planejamento/importar', [], 'Importar Planejamento');
    }

    /** Exibe detalhes de uma contratação do PCA */
    public function details($numeroContratacao)
    {
        requireLogin();
        $contratacao = $this->planejamentoModel->buscarContratacaoPorNumero($numeroContratacao);
        
        if (!$contratacao) {
            echo "Contratação não encontrada.";
            return;
        }
        
        // Buscar todos os itens desta contratação
        $sql = "SELECT * FROM pca_dados WHERE numero_contratacao = :numero_contratacao ORDER BY id";
        $stmt = $this->planejamentoModel->getDb()->prepare($sql);
        $stmt->execute([':numero_contratacao' => $numeroContratacao]);
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Verificar se já foi licitada
        $jaLicitada = $this->planejamentoModel->verificarSeJaFoiLicitada($numeroContratacao);
        
        $this->view('planejamento/view', [
            'contratacao' => $contratacao,
            'itens' => $itens,
            'ja_licitada' => $jaLicitada
        ], 'Detalhes da Contratação');
    }
}
