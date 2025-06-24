<?php
require_once __DIR__ . '/../helpers/auth.php';

class ModulosController extends Controller
{
    public function __construct()
    {
        // Controller para seleção de módulos
    }

    /** Exibe a tela de seleção de módulos */
    public function index()
    {
        requireLogin();
        
        // Buscar estatísticas dos módulos
        $licitacaoModel = $this->model('LicitacaoModel');
        $planejamentoModel = $this->model('planejamento/PlanejamentoModel');
        
        // Estatísticas de licitações
        $totalLicitacoes = $licitacaoModel->contarLicitacoes();
        $licitacoesAndamento = $this->contarLicitacoesPorSituacao($licitacaoModel, 'EM_ANDAMENTO');
        $licitacoesHomologadas = $this->contarLicitacoesPorSituacao($licitacaoModel, 'HOMOLOGADO');
        $economiaTotal = $licitacaoModel->economiaTotal();
        
        // Estatísticas de planejamento (PCA)
        $totalPca = $this->contarTotalPca($planejamentoModel);
        $valorTotalPca = $this->valorTotalPca($planejamentoModel);
        $importacoesRealizadas = $this->contarImportacoes($planejamentoModel);
        
        $this->view('modulos/index', [
            'licitacoes' => [
                'total' => $totalLicitacoes,
                'em_andamento' => $licitacoesAndamento,
                'homologadas' => $licitacoesHomologadas,
                'economia_total' => $economiaTotal
            ],
            'planejamento' => [
                'total_pca' => $totalPca,
                'valor_total' => $valorTotalPca,
                'importacoes' => $importacoesRealizadas
            ]
        ], 'Seleção de Módulos', 'template_modulos');
    }

    /** Redireciona para o módulo de licitações */
    public function licitacoes()
    {
        requireLogin();
        header('Location: ' . BASE_URL . 'licitacoes/index');
        exit;
    }

    /** Redireciona para o módulo de planejamento */
    public function planejamento()
    {
        requireLogin();
        header('Location: ' . BASE_URL . 'planejamento/index');
        exit;
    }

    /** Redireciona para o módulo de contratos */
    public function contratos()
    {
        requireLogin();
        header('Location: ' . BASE_URL . 'contratos/index');
        exit;
    }

    /** Métodos auxiliares para estatísticas */
    private function contarLicitacoesPorSituacao($model, $situacao)
    {
        try {
            $sql = "SELECT COUNT(*) FROM licitacoes WHERE situacao = :situacao";
            $stmt = $model->getDb()->prepare($sql);
            $stmt->execute([':situacao' => $situacao]);
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function contarTotalPca($model)
    {
        try {
            $sql = "SELECT COUNT(DISTINCT numero_contratacao) FROM pca_dados WHERE numero_contratacao IS NOT NULL";
            $stmt = $model->getDb()->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function valorTotalPca($model)
    {
        try {
            $sql = "SELECT SUM(valor_total_contratacao) FROM pca_dados";
            $stmt = $model->getDb()->query($sql);
            return (float) $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0.0;
        }
    }

    private function contarImportacoes($model)
    {
        try {
            $sql = "SELECT COUNT(*) FROM pca_importacoes WHERE status = 'concluido'";
            $stmt = $model->getDb()->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
}