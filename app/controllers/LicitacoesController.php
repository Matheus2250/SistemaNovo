<?php
require_once __DIR__ . '/../helpers/auth.php';

class LicitacoesController extends Controller
{
    private $licitacaoModel;

    public function __construct()
    {
        $this->licitacaoModel = $this->model('LicitacaoModel');
    }

    /** Lista todas as licitações */
    public function index()
    {
        requireLogin();
        
        // Parâmetros de paginação
        $paginaAtual = (int) ($_GET['pagina'] ?? 1);
        $porPagina = 15;
        
        // Buscar dados com paginação
        $licitacoes = $this->licitacaoModel->buscarTodos($paginaAtual, $porPagina);
        $totalLicitacoes = $this->licitacaoModel->contarLicitacoes();
        $resumoPorSituacao = $this->licitacaoModel->resumoPorSituacao();
        $economiaTotal = $this->licitacaoModel->economiaTotal();
        
        // Criar objeto de paginação
        require_once __DIR__ . '/../helpers/pagination.php';
        $baseUrl = BASE_URL . 'licitacoes/index';
        $pagination = new Pagination($totalLicitacoes, $porPagina, $paginaAtual, $baseUrl);
        
        $this->view('licitacoes/index', [
            'licitacoes' => $licitacoes,
            'resumoPorSituacao' => $resumoPorSituacao,
            'economiaTotal' => $economiaTotal,
            'pagination' => $pagination
        ], 'Licitações');
    }

    /** Exibe detalhes de uma licitação */
    public function details($id)
    {
        requireLogin();
        $licitacao = $this->licitacaoModel->buscarPorId($id);
        
        if (!$licitacao) {
            echo "Licitação não encontrada.";
            return;
        }
        
        $this->view('licitacoes/view', [
            'licitacao' => $licitacao
        ], 'Detalhes da Licitação');
    }

    /** Exibe formulário para nova licitação */
    public function create()
    {
        requireLicitacaoOuCoordenador();
        $numerosContratacao = $this->licitacaoModel->listarNumerosContratacaoDisponiveis();
        
        $this->view('licitacoes/create', [
            'numerosContratacao' => $numerosContratacao
        ], 'Nova Licitação');
    }

    /** Processa criação de nova licitação */
    public function store()
    {
        requireLicitacaoOuCoordenador();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'licitacoes/index');
            return;
        }

        $dados = [
            ':nup' => $_POST['nup'] ?? '',
            ':data_entrada_dipli' => $_POST['data_entrada_dipli'] ?? null,
            ':resp_instrucao' => $_POST['resp_instrucao'] ?? '',
            ':area_demandante' => $_POST['area_demandante'] ?? '',
            ':pregoeiro' => $_POST['pregoeiro'] ?? '',
            ':pca_dados_id' => $_POST['pca_dados_id'] ?? null,
            ':numero_processo' => $_POST['numero_processo'] ?? '',
            ':tipo_licitacao' => $_POST['tipo_licitacao'] ?? '',
            ':modalidade' => $_POST['modalidade'] ?? '',
            ':tipo' => $_POST['tipo'] ?? '',
            ':numero_contratacao' => $_POST['numero_contratacao'] ?? '',
            ':numero' => $_POST['numero'] ?? null,
            ':ano' => $_POST['ano'] ? (int)$_POST['ano'] : date('Y'),
            ':objeto' => $_POST['objeto'] ?? '',
            ':valor_estimado' => $_POST['valor_estimado'] ? (float)str_replace(['.', ','], ['', '.'], $_POST['valor_estimado']) : null,
            ':qtd_itens' => $_POST['qtd_itens'] ? (int)$_POST['qtd_itens'] : null,
            ':data_abertura' => $_POST['data_abertura'] ?? null,
            ':data_publicacao' => $_POST['data_publicacao'] ?? null,
            ':data_homologacao' => $_POST['data_homologacao'] ?? null,
            ':valor_homologado' => $_POST['valor_homologado'] ? (float)str_replace(['.', ','], ['', '.'], $_POST['valor_homologado']) : null,
            ':qtd_homol' => $_POST['qtd_homol'] ? (int)$_POST['qtd_homol'] : null,
            ':link' => $_POST['link'] ?? '',
            ':usuario_id' => $_SESSION['usuario']['id'],
            ':situacao' => $_POST['situacao'] ?? 'PREPARACAO',
            ':observacoes' => $_POST['observacoes'] ?? ''
        ];

        // Converte datas do formato brasileiro para MySQL
        $dados = $this->licitacaoModel->prepararDadosParaInsercao($dados);

        if ($this->licitacaoModel->inserir($dados)) {
            header('Location: ' . BASE_URL . 'licitacoes/index');
        } else {
            echo "Erro ao criar licitação.";
        }
    }

    /** Exibe formulário para editar licitação */
    public function edit($id)
    {
        requireLicitacaoOuCoordenador();
        $licitacao = $this->licitacaoModel->buscarPorId($id);
        
        if (!$licitacao) {
            echo "Licitação não encontrada.";
            return;
        }

        $numerosContratacao = $this->licitacaoModel->listarNumerosContratacaoDisponiveis();
        
        $this->view('licitacoes/edit', [
            'licitacao' => $licitacao,
            'numerosContratacao' => $numerosContratacao
        ], 'Editar Licitação');
    }

    /** Processa atualização de licitação */
    public function update($id)
    {
        requireLicitacaoOuCoordenador();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'licitacoes/index');
            return;
        }

        $dados = [
            ':id' => $id,
            ':nup' => $_POST['nup'] ?? '',
            ':data_entrada_dipli' => $_POST['data_entrada_dipli'] ?? null,
            ':resp_instrucao' => $_POST['resp_instrucao'] ?? '',
            ':area_demandante' => $_POST['area_demandante'] ?? '',
            ':pregoeiro' => $_POST['pregoeiro'] ?? '',
            ':pca_dados_id' => $_POST['pca_dados_id'] ?? null,
            ':numero_processo' => $_POST['numero_processo'] ?? '',
            ':tipo_licitacao' => $_POST['tipo_licitacao'] ?? '',
            ':modalidade' => $_POST['modalidade'] ?? '',
            ':tipo' => $_POST['tipo'] ?? '',
            ':numero_contratacao' => $_POST['numero_contratacao'] ?? '',
            ':numero' => $_POST['numero'] ?? null,
            ':ano' => $_POST['ano'] ? (int)$_POST['ano'] : date('Y'),
            ':objeto' => $_POST['objeto'] ?? '',
            ':valor_estimado' => $_POST['valor_estimado'] ? (float)str_replace(['.', ','], ['', '.'], $_POST['valor_estimado']) : null,
            ':qtd_itens' => $_POST['qtd_itens'] ? (int)$_POST['qtd_itens'] : null,
            ':data_abertura' => $_POST['data_abertura'] ?? null,
            ':data_publicacao' => $_POST['data_publicacao'] ?? null,
            ':data_homologacao' => $_POST['data_homologacao'] ?? null,
            ':valor_homologado' => $_POST['valor_homologado'] ? (float)str_replace(['.', ','], ['', '.'], $_POST['valor_homologado']) : null,
            ':qtd_homol' => $_POST['qtd_homol'] ? (int)$_POST['qtd_homol'] : null,
            ':link' => $_POST['link'] ?? '',
            ':situacao' => $_POST['situacao'] ?? 'PREPARACAO',
            ':observacoes' => $_POST['observacoes'] ?? ''
        ];

        // Converte datas do formato brasileiro para MySQL
        $dados = $this->licitacaoModel->prepararDadosParaInsercao($dados);

        if ($this->licitacaoModel->atualizar($dados)) {
            header('Location: ' . BASE_URL . 'licitacoes/index');
        } else {
            echo "Erro ao atualizar licitação.";
        }
    }

    /** Exclui uma licitação */
    public function delete($id)
    {
        requireLicitacaoOuCoordenador();
        
        if ($this->licitacaoModel->excluir($id)) {
            header('Location: ' . BASE_URL . 'licitacoes/index');
        } else {
            echo "Erro ao excluir licitação.";
        }
    }

    /** API para buscar dados do PCA pelo número da contratação (AJAX) */
    public function buscarDadosPca()
    {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['erro' => 'Método não permitido']);
            return;
        }

        $numeroContratacao = $_POST['numero_contratacao'] ?? '';
        
        if (empty($numeroContratacao)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Número da contratação é obrigatório']);
            return;
        }

        $dadosPca = $this->licitacaoModel->buscarDadosPcaPorNumeroContratacao($numeroContratacao);
        
        if ($dadosPca) {
            // Verifica se já existe licitação para esta contratação
            $jaExiste = $this->licitacaoModel->verificarExisteLicitacaoParaContratacao($numeroContratacao);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'dados' => $dadosPca,
                'ja_existe_licitacao' => $jaExiste
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Número da contratação não encontrado no PCA']);
        }
    }

    /** API para pesquisar contratações por termo (AJAX) */
    public function pesquisarContratacoes()
    {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['erro' => 'Método não permitido']);
            return;
        }

        $termo = $_POST['termo'] ?? '';
        
        if (strlen($termo) < 2) {
            echo json_encode(['contratacoes' => []]);
            return;
        }

        // Buscar contratações que contenham o termo
        $sql = "SELECT DISTINCT 
                    numero_contratacao,
                    titulo_contratacao,
                    area_requisitante,
                    valor_total_contratacao
                FROM pca_dados 
                WHERE numero_contratacao IS NOT NULL 
                AND numero_contratacao != ''
                AND (numero_contratacao LIKE :termo 
                     OR titulo_contratacao LIKE :termo
                     OR area_requisitante LIKE :termo)
                ORDER BY numero_contratacao
                LIMIT 10";
        
        $stmt = $this->licitacaoModel->getDb()->prepare($sql);
        $stmt->execute([':termo' => "%$termo%"]);
        $contratacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode(['contratacoes' => $contratacoes]);
    }

    /** Exibe relatórios e estatísticas */
    public function relatorios()
    {
        requireLogin();
        
        $resumoPorSituacao = $this->licitacaoModel->resumoPorSituacao();
        $resumoPorModalidade = $this->licitacaoModel->resumoPorModalidade();
        $licitacoesProximas = $this->licitacaoModel->licitacoesProximasVencimento();
        $economiaTotal = $this->licitacaoModel->economiaTotal();
        
        $this->view('licitacoes/relatorios', [
            'resumoPorSituacao' => $resumoPorSituacao,
            'resumoPorModalidade' => $resumoPorModalidade,
            'licitacoesProximas' => $licitacoesProximas,
            'economiaTotal' => $economiaTotal
        ], 'Relatórios - Licitações');
    }
}