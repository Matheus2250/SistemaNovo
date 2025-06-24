<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Detalhes da Contratação</h3>
        <div>
            <?php if (podeEditarLicitacoes() && !$ja_licitada): ?>
                <a href="<?= BASE_URL ?>licitacoes/create?numero_contratacao=<?= urlencode($contratacao['numero_contratacao']) ?>" 
                   class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Criar Licitação
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>planejamento/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Coluna Esquerda - Informações Principais -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-info-circle"></i> Informações da Contratação
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Número:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-primary fs-6"><?= htmlspecialchars($contratacao['numero_contratacao']) ?></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Área Requisitante:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-info"><?= htmlspecialchars($contratacao['area_requisitante']) ?></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Status:</strong></div>
                        <div class="col-sm-8">
                            <?php
                            $badgeClass = match($contratacao['status_contratacao']) {
                                'PLANEJADO' => 'bg-warning',
                                'EM_ANDAMENTO' => 'bg-primary',
                                'CONCLUIDO' => 'bg-success',
                                'CANCELADO' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= ucfirst(str_replace('_', ' ', $contratacao['status_contratacao'])) ?>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Valor Total:</strong></div>
                        <div class="col-sm-8">
                            <?php if ($contratacao['valor_total_contratacao']): ?>
                                <span class="text-success fw-bold fs-5">
                                    R$ <?= number_format($contratacao['valor_total_contratacao'], 2, ',', '.') ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Não informado</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Total de Itens:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-light text-dark"><?= $contratacao['total_itens'] ?> itens</span>
                        </div>
                    </div>

                    <?php if ($contratacao['numero_dfd']): ?>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Número DFD:</strong></div>
                            <div class="col-sm-8"><?= htmlspecialchars($contratacao['numero_dfd']) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($contratacao['prioridade']): ?>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Prioridade:</strong></div>
                            <div class="col-sm-8">
                                <span class="badge bg-warning"><?= htmlspecialchars($contratacao['prioridade']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Coluna Direita - Cronograma e Status -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-calendar-event"></i> Cronograma
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6"><strong>Início do Processo:</strong></div>
                        <div class="col-sm-6">
                            <?= $contratacao['data_inicio_processo'] ? date('d/m/Y', strtotime($contratacao['data_inicio_processo'])) : '-' ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6"><strong>Conclusão Prevista:</strong></div>
                        <div class="col-sm-6">
                            <?= $contratacao['data_conclusao_processo'] ? date('d/m/Y', strtotime($contratacao['data_conclusao_processo'])) : '-' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status da Licitação -->
            <div class="card mb-4">
                <div class="card-header <?= $ja_licitada ? 'bg-success' : 'bg-warning' ?> text-white">
                    <i class="bi bi-<?= $ja_licitada ? 'check-circle' : 'clock' ?>"></i> Status da Licitação
                </div>
                <div class="card-body text-center">
                    <?php if ($ja_licitada): ?>
                        <div class="text-success">
                            <i class="bi bi-check-circle-fill fs-1"></i>
                            <h5 class="mt-2">Licitação Criada</h5>
                            <p class="text-muted">Esta contratação já possui processo licitatório iniciado.</p>
                        </div>
                    <?php else: ?>
                        <div class="text-warning">
                            <i class="bi bi-clock-fill fs-1"></i>
                            <h5 class="mt-2">Aguardando Licitação</h5>
                            <p class="text-muted">Esta contratação ainda não foi licitada.</p>
                            <?php if (podeEditarLicitacoes()): ?>
                                <a href="<?= BASE_URL ?>licitacoes/create?numero_contratacao=<?= urlencode($contratacao['numero_contratacao']) ?>" 
                                   class="btn btn-warning">
                                    <i class="bi bi-plus-circle"></i> Iniciar Licitação
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Título/Objeto -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-file-text"></i> Objeto da Contratação
        </div>
        <div class="card-body">
            <p class="mb-0 fs-5">
                <?= htmlspecialchars($contratacao['titulo_contratacao']) ?>
            </p>
        </div>
    </div>

    <!-- Itens da Contratação -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <i class="bi bi-list-ul"></i> Itens da Contratação (<?= count($itens) ?>)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Unidade</th>
                            <th>Quantidade</th>
                            <th>Valor Unit.</th>
                            <th>Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <?php if ($item['codigo_material_servico']): ?>
                                        <small class="text-muted"><?= htmlspecialchars($item['codigo_material_servico']) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($item['descricao_material_servico']) ?>">
                                        <?= htmlspecialchars($item['descricao_material_servico']) ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($item['unidade_fornecimento']) ?></td>
                                <td><?= $item['quantidade'] ? number_format($item['quantidade'], 2, ',', '.') : '-' ?></td>
                                <td>
                                    <?= $item['valor_unitario'] ? 'R$ ' . number_format($item['valor_unitario'], 2, ',', '.') : '-' ?>
                                </td>
                                <td>
                                    <?= $item['valor_total'] ? 'R$ ' . number_format($item['valor_total'], 2, ',', '.') : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>