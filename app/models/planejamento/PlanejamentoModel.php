<?php
class PlanejamentoModel extends Model
{
    /* ===== Utilidades internas ===== */
    /** Converte “1.234,56” → 1234.56  |  vazio → null */
    private function toDecimal(?string $v): ?float
    {
        if ($v === null || $v === '') return null;
        $v = str_replace(['.', ','], ['', '.'], $v);
        return (float) $v;
    }

    /** Converte “31/12/2024” → 2024-12-31  |  vazio → null */
    private function toDate(?string $v): ?string
    {
        if (!$v) return null;
        [$d,$m,$y] = array_pad(explode('/', $v), 3, null);
        return ($d && $m && $y) ? "$y-$m-$d" : null;
    }

    /* ====== Mapeia uma linha CSV → array para inserir ====== */
public function mapearCsvParaArray(array $cab, array $val): array
{
    // 1. Limpa cabeçalhos
    $cab = array_map(fn($h) => trim(str_replace(['"', "\xEF\xBB\xBF"], '', $h)), $cab);

    // 2. Verifica se os arrays têm o mesmo tamanho
    if (count($cab) !== count($val)) {
        error_log("Erro no CSV: cabeçalhos e valores não coincidem.");
        error_log("Cabeçalhos (" . count($cab) . "): " . json_encode($cab));
        error_log("Valores (" . count($val) . "): " . json_encode($val));
        return []; // ignora a linha
    }

    // 3. Combina os dados
    $out = array_combine($cab, $val);
    if (!$out) return [];

    // 4. Conversão segura (como já definido)
    return [
        'numero_contratacao'          => $out['Número da contratação']      ?? '',
        'status_contratacao'          => $out['Status da contratação']      ?? '',
        'situacao_execucao'           => $out['Situação da Execução']       ?? '',
        'titulo_contratacao'          => $out['Título da contratação']      ?? '',
        'categoria_contratacao'       => $out['Categoria da contratação']   ?? '',
        'uasg_atual'                  => $out['UASG Atual']                 ?? '',
        'valor_total_contratacao'     => $this->toDecimal($out['Valor total da contratação'] ?? ''),
        'data_inicio_processo'        => $this->toDate($out['Data início processo'] ?? ''),
        'data_conclusao_processo'     => $this->toDate($out['Data conclusão processo'] ?? ''),
        'prazo_duracao_dias'          => $out['Prazo duração (dias)']       ?? null,
        'area_requisitante'           => $out['Área requisitante']          ?? '',
        'numero_dfd'                  => $out['Nº DFD']                     ?? '',
        'prioridade'                  => $out['Prioridade']                 ?? '',
        'numero_item_dfd'             => $out['Nº item DFD']                ?? '',
        'data_conclusao_dfd'          => $this->toDate($out['Data conclusão DFD'] ?? ''),
        'classificacao_contratacao'   => $out['Classificação da contratação'] ?? '',
        'codigo_classe_grupo'         => $out['Código classe/grupo']        ?? '',
        'nome_classe_grupo'           => $out['Nome classe/grupo']          ?? '',
        'codigo_pdm_material'         => $out['Código PDM material']        ?? '',
        'nome_pdm_material'           => $out['Nome PDM material']          ?? '',
        'codigo_material_servico'     => $out['Código material/serviço']    ?? '',
        'descricao_material_servico'  => $out['Descrição material/serviço'] ?? '',
        'unidade_fornecimento'        => $out['Unidade Fornecimento']       ?? '',
        'valor_unitario'              => $this->toDecimal($out['Valor Unitário']  ?? ''),
        'quantidade'                  => $this->toDecimal($out['Quantidade']      ?? ''),
        'valor_total'                 => $this->toDecimal($out['Valor Total']     ?? ''),
        'ano_pca'                     => 2025
    ];
}

    /* ===== Upsert: insere ou atualiza ===== */
public function upsert(array $d): string
{
    /* --- nomes das colunas e placeholders --- */
    $cols         = array_keys($d);
    $placeholders = ':' . implode(', :', $cols);

    /* --- campos da chave única que NÃO devem ser atualizados --- */
    $unique = ['numero_contratacao', 'numero_item_dfd', 'numero_dfd', 'ano_pca'];

    /* --- gera lista de col = VALUES(col) para update --- */
    $updates = [];
    foreach ($cols as $col) {
        if (!in_array($col, $unique)) {
            $updates[] = "$col = VALUES($col)";
        }
    }

    $sql = "INSERT INTO pca_dados (" . implode(', ', $cols) . ")
            VALUES ($placeholders)
            ON DUPLICATE KEY UPDATE " . implode(', ', $updates);

    $stmt = $this->db->prepare($sql);
    $stmt->execute($d);

    /* rowCount(): 1 = insert, 2 = update em MariaDB */
    return $stmt->rowCount() === 1 ? 'novo' : 'atualizado';
}

    /* --------- Métodos de agrupamento --------- */

    public function resumoPorContratacao(): array
    {
        $sql = "SELECT numero_contratacao,
                       COUNT(*) AS itens,
                       SUM(valor_total) AS valor_total
                FROM pca_dados
                GROUP BY numero_contratacao";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resumoPorDfd(): array
    {
        $sql = "SELECT numero_dfd,
                       COUNT(*) AS itens,
                       SUM(valor_total) AS valor_total
                FROM pca_dados
                GROUP BY numero_dfd";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

public function listarImportacoes(): array
{
    $sql = "
        SELECT 
            i.id,
            i.nome_arquivo,
            i.ano_pca,
            i.status,
            i.data_hora,
            u.nome AS nome_usuario
        FROM pca_importacoes i
        LEFT JOIN usuarios u ON u.id = i.usuario_id
        ORDER BY i.data_hora DESC
    ";

    return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


/** Atualiza o status da importação */
/** Atualiza o status da importação */
public function atualizarStatusImportacao(int $id, string $status): void
{
    $sql = "UPDATE pca_importacoes SET status = :status WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':status' => $status,
        ':id'     => $id
    ]);
}

public function obterResumoPlanejamento(): array
{
    $db = $this->getDb();

    // Total por ano_pca
    $porAno = $db->query("
        SELECT ano_pca, COUNT(*) AS total 
        FROM pca_dados 
        GROUP BY ano_pca 
        ORDER BY ano_pca DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Total por status_contratacao
    $porStatus = $db->query("
        SELECT status_contratacao, COUNT(*) AS total 
        FROM pca_dados 
        GROUP BY status_contratacao 
        ORDER BY total DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Soma total de valor_total_contratacao
    $totalValor = $db->query("
        SELECT SUM(valor_total_contratacao) 
        FROM pca_dados
    ")->fetchColumn();

    return [
        'por_ano'    => $porAno,
        'por_status' => $porStatus,
        'valor_total'=> $totalValor
    ];
}

/* ========== MÉTODOS PARA LISTAGEM DE CONTRATAÇÕES ========== */

/** Lista todas as contratações do PCA com informações resumidas */
public function listarContratacoes(int $pagina = 1, int $porPagina = 15): array
{
    $offset = ($pagina - 1) * $porPagina;
    
    $sql = "SELECT DISTINCT
                numero_contratacao,
                titulo_contratacao,
                area_requisitante,
                status_contratacao,
                valor_total_contratacao,
                data_inicio_processo,
                data_conclusao_processo,
                COUNT(*) as total_itens,
                MIN(id) as primeiro_id
            FROM pca_dados 
            WHERE numero_contratacao IS NOT NULL 
            AND numero_contratacao != ''
            GROUP BY numero_contratacao, titulo_contratacao, area_requisitante, 
                     status_contratacao, valor_total_contratacao, 
                     data_inicio_processo, data_conclusao_processo
            ORDER BY data_inicio_processo DESC, numero_contratacao DESC
            LIMIT :limit OFFSET :offset";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/** Conta o total de contratações para paginação */
public function contarContratacoes(): int
{
    $sql = "SELECT COUNT(DISTINCT numero_contratacao) 
            FROM pca_dados 
            WHERE numero_contratacao IS NOT NULL 
            AND numero_contratacao != ''";
    
    $stmt = $this->db->query($sql);
    return (int) $stmt->fetchColumn();
}

/** Busca contratação específica por número */
public function buscarContratacaoPorNumero(string $numeroContratacao): ?array
{
    $sql = "SELECT DISTINCT
                numero_contratacao,
                titulo_contratacao,
                area_requisitante,
                status_contratacao,
                valor_total_contratacao,
                data_inicio_processo,
                data_conclusao_processo,
                prioridade,
                numero_dfd,
                COUNT(*) as total_itens
            FROM pca_dados 
            WHERE numero_contratacao = :numero_contratacao
            GROUP BY numero_contratacao, titulo_contratacao, area_requisitante, 
                     status_contratacao, valor_total_contratacao, 
                     data_inicio_processo, data_conclusao_processo, 
                     prioridade, numero_dfd";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':numero_contratacao' => $numeroContratacao]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/** Resumo de contratações por status */
public function resumoContratacoesPorStatus(): array
{
    $sql = "SELECT status_contratacao, 
                   COUNT(DISTINCT numero_contratacao) as total_contratacoes,
                   SUM(valor_total_contratacao) as valor_total
            FROM pca_dados 
            WHERE numero_contratacao IS NOT NULL 
            AND numero_contratacao != ''
            GROUP BY status_contratacao 
            ORDER BY total_contratacoes DESC";
    
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/** Verifica se contratação já foi licitada */
public function verificarSeJaFoiLicitada(string $numeroContratacao): bool
{
    $sql = "SELECT COUNT(*) FROM licitacoes WHERE numero_contratacao = :numero_contratacao";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':numero_contratacao' => $numeroContratacao]);
    return $stmt->fetchColumn() > 0;
}


}
