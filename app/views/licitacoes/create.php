<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Nova Licitação</h3>
        <a href="<?= BASE_URL ?>licitacoes/index" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <form action="<?= BASE_URL ?>licitacoes/store" method="POST" id="formLicitacao">
        <div class="row">
            <!-- Coluna Esquerda -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-info-circle"></i> Informações Básicas
                    </div>
                    <div class="card-body">
                        <!-- Número da Contratação (Busca PCA) -->
                        <div class="mb-3">
                            <label for="numero_contratacao" class="form-label">
                                Número da Contratação (PCA) <span class="text-danger">*</span>
                            </label>
                            <div class="position-relative">
                                <input type="text" class="form-control" name="numero_contratacao" id="numero_contratacao" 
                                       placeholder="Digite o número da contratação..." autocomplete="off" required>
                                <div class="position-absolute top-100 start-0 w-100 bg-white border rounded shadow-sm" 
                                     id="contratacoes-dropdown" style="display: none; z-index: 1050; max-height: 300px; overflow-y: auto;">
                                </div>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Digite para pesquisar por número, título ou área da contratação
                            </div>
                        </div>

                        <!-- NUP -->
                        <div class="mb-3">
                            <label for="nup" class="form-label">NUP (Número Único de Protocolo)</label>
                            <input type="text" class="form-control" name="nup" id="nup" 
                                   placeholder="Ex: 25000.123456/2024-12">
                        </div>

                        <!-- Data de Entrada DIPLI -->
                        <div class="mb-3">
                            <label for="data_entrada_dipli" class="form-label">Data de Entrada na DIPLI</label>
                            <input type="date" class="form-control" name="data_entrada_dipli" id="data_entrada_dipli">
                        </div>

                        <!-- Responsável pela Instrução -->
                        <div class="mb-3">
                            <label for="resp_instrucao" class="form-label">Responsável pela Instrução</label>
                            <input type="text" class="form-control" name="resp_instrucao" id="resp_instrucao">
                        </div>

                        <!-- Área Demandante (será preenchida automaticamente) -->
                        <div class="mb-3">
                            <label for="area_demandante" class="form-label">Área Demandante</label>
                            <input type="text" class="form-control" name="area_demandante" id="area_demandante" readonly>
                            <div class="form-text">Preenchido automaticamente ao selecionar a contratação do PCA</div>
                        </div>

                        <!-- Pregoeiro -->
                        <div class="mb-3">
                            <label for="pregoeiro" class="form-label">Pregoeiro</label>
                            <input type="text" class="form-control" name="pregoeiro" id="pregoeiro">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-file-earmark-text"></i> Detalhes da Licitação
                    </div>
                    <div class="card-body">
                        <!-- Modalidade -->
                        <div class="mb-3">
                            <label for="modalidade" class="form-label">Modalidade <span class="text-danger">*</span></label>
                            <select class="form-select" name="modalidade" id="modalidade" required>
                                <option value="">Selecione...</option>
                                <option value="PREGAO">Pregão</option>
                                <option value="DISPENSA">Dispensa</option>
                                <option value="INEXIGIBILIDADE">Inexigibilidade</option>
                                <option value="CONCORRENCIA">Concorrência</option>
                                <option value="TOMADA_PRECOS">Tomada de Preços</option>
                                <option value="CONVITE">Convite</option>
                            </select>
                        </div>

                        <!-- Tipo -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" id="tipo">
                                <option value="">Selecione...</option>
                                <option value="TRADICIONAL">Tradicional</option>
                                <option value="SRP">Sistema de Registro de Preços</option>
                                <option value="COTACAO">Cotação</option>
                            </select>
                        </div>

                        <!-- Número e Ano -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <input type="number" class="form-control" name="numero" id="numero">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ano" class="form-label">Ano</label>
                                    <input type="number" class="form-control" name="ano" id="ano" 
                                           value="<?= date('Y') ?>" min="2020" max="2030">
                                </div>
                            </div>
                        </div>

                        <!-- Objeto (será preenchido automaticamente) -->
                        <div class="mb-3">
                            <label for="objeto" class="form-label">Objeto</label>
                            <textarea class="form-control" name="objeto" id="objeto" rows="3"></textarea>
                            <div class="form-text">Preenchido automaticamente ao selecionar a contratação do PCA</div>
                        </div>

                        <!-- Valor Estimado (será preenchido automaticamente) -->
                        <div class="mb-3">
                            <label for="valor_estimado" class="form-label">Valor Estimado</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" name="valor_estimado" id="valor_estimado" 
                                       placeholder="0,00" readonly>
                            </div>
                            <div class="form-text">Preenchido automaticamente ao selecionar a contratação do PCA</div>
                        </div>

                        <!-- Quantidade de Itens -->
                        <div class="mb-3">
                            <label for="qtd_itens" class="form-label">Quantidade de Itens</label>
                            <input type="number" class="form-control" name="qtd_itens" id="qtd_itens" min="1">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Datas -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-calendar-event"></i> Cronograma
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="data_abertura" class="form-label">Data de Abertura</label>
                            <input type="date" class="form-control" name="data_abertura" id="data_abertura">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="data_publicacao" class="form-label">Data de Publicação</label>
                            <input type="date" class="form-control" name="data_publicacao" id="data_publicacao">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="data_homologacao" class="form-label">Data de Homologação</label>
                            <input type="date" class="form-control" name="data_homologacao" id="data_homologacao">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Resultado -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <i class="bi bi-trophy"></i> Resultado
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="situacao" class="form-label">Situação</label>
                            <select class="form-select" name="situacao" id="situacao">
                                <option value="PREPARACAO">Preparação</option>
                                <option value="EM_ANDAMENTO">Em Andamento</option>
                                <option value="HOMOLOGADO">Homologado</option>
                                <option value="FRACASSADO">Fracassado</option>
                                <option value="REVOGADO">Revogado</option>
                                <option value="CANCELADO">Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="valor_homologado" class="form-label">Valor Homologado</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" name="valor_homologado" id="valor_homologado" 
                                       placeholder="0,00">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="qtd_homol" class="form-label">Quantidade Homologada</label>
                            <input type="number" class="form-control" name="qtd_homol" id="qtd_homol" min="0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="link" class="form-label">Link para Documentos</label>
                            <input type="url" class="form-control" name="link" id="link" 
                                   placeholder="https://...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="numero_processo" class="form-label">Número do Processo</label>
                            <input type="text" class="form-control" name="numero_processo" id="numero_processo">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" name="observacoes" id="observacoes" rows="3"></textarea>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="<?= BASE_URL ?>licitacoes/index" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Salvar Licitação
            </button>
        </div>

        <!-- Campos ocultos -->
        <input type="hidden" name="pca_dados_id" id="pca_dados_id">
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const numeroContratacaoInput = document.getElementById('numero_contratacao');
    const dropdown = document.getElementById('contratacoes-dropdown');
    let timeoutId;
    
    // Verificar se há parâmetro na URL
    const urlParams = new URLSearchParams(window.location.search);
    const numeroContratacaoParam = urlParams.get('numero_contratacao');
    if (numeroContratacaoParam) {
        numeroContratacaoInput.value = numeroContratacaoParam;
        buscarDadosContratacao(numeroContratacaoParam);
    }

    // Evento de digitação para pesquisa
    numeroContratacaoInput.addEventListener('input', function() {
        const termo = this.value.trim();
        
        // Limpar timeout anterior
        clearTimeout(timeoutId);
        
        if (termo.length < 2) {
            dropdown.style.display = 'none';
            return;
        }
        
        // Aguardar 300ms antes de fazer a pesquisa
        timeoutId = setTimeout(() => {
            pesquisarContratacoes(termo);
        }, 300);
    });

    // Esconder dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!numeroContratacaoInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    // Função para pesquisar contratações
    function pesquisarContratacoes(termo) {
        fetch('<?= BASE_URL ?>licitacoes/pesquisarContratacoes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'termo=' + encodeURIComponent(termo)
        })
        .then(response => response.json())
        .then(data => {
            exibirResultados(data.contratacoes || []);
        })
        .catch(error => {
            console.error('Erro na pesquisa:', error);
        });
    }

    // Função para exibir resultados da pesquisa
    function exibirResultados(contratacoes) {
        if (contratacoes.length === 0) {
            dropdown.style.display = 'none';
            return;
        }

        dropdown.innerHTML = '';
        
        contratacoes.forEach(contratacao => {
            const item = document.createElement('div');
            item.className = 'p-2 border-bottom cursor-pointer';
            item.style.cursor = 'pointer';
            
            item.innerHTML = `
                <div class="fw-bold">${contratacao.numero_contratacao}</div>
                <div class="text-muted small">${contratacao.titulo_contratacao}</div>
                <div class="text-info small">${contratacao.area_requisitante}</div>
            `;
            
            item.addEventListener('click', function() {
                numeroContratacaoInput.value = contratacao.numero_contratacao;
                dropdown.style.display = 'none';
                buscarDadosContratacao(contratacao.numero_contratacao);
            });
            
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
            
            dropdown.appendChild(item);
        });
        
        dropdown.style.display = 'block';
    }

    // Função para buscar dados completos da contratação
    function buscarDadosContratacao(numeroContratacao) {
        fetch('<?= BASE_URL ?>licitacoes/buscarDadosPca', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'numero_contratacao=' + encodeURIComponent(numeroContratacao)
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                document.getElementById('area_demandante').value = data.dados.area_requisitante || '';
                document.getElementById('objeto').value = data.dados.objeto || '';
                document.getElementById('valor_estimado').value = formatMoney(data.dados.valor_estimado || '');
                document.getElementById('pca_dados_id').value = data.dados.pca_dados_id || '';
                
                if (data.ja_existe_licitacao) {
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-warning alert-dismissible fade show mt-2';
                    alert.innerHTML = `
                        <i class="bi bi-exclamation-triangle"></i> 
                        <strong>Atenção:</strong> Já existe uma licitação cadastrada para esta contratação!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    numeroContratacaoInput.parentNode.appendChild(alert);
                }
            } else {
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show mt-2';
                alert.innerHTML = `
                    <i class="bi bi-x-circle"></i> 
                    <strong>Erro:</strong> ${data.erro}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                numeroContratacaoInput.parentNode.appendChild(alert);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }

    // Função para formatar valores monetários
    function formatMoney(value) {
        if (!value) return '';
        return parseFloat(value).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Formatação automática de valores monetários
    document.getElementById('valor_homologado').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value) {
            value = (value / 100).toFixed(2);
            this.value = value.replace('.', ',');
        }
    });
});
</script>