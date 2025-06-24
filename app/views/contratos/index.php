<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i data-lucide="file-signature" style="width: 20px; height: 20px; margin-right: 10px;"></i>
                        Módulo de Contratos
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="my-4">
                        <i data-lucide="construction" style="width: 80px; height: 80px; color: #6c757d;"></i>
                    </div>
                    <h2 class="text-muted">Módulo em Desenvolvimento</h2>
                    <p class="lead text-muted mb-4">
                        O módulo de contratos está sendo desenvolvido e estará disponível em breve.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <h6><strong>Funcionalidades Planejadas:</strong></h6>
                                <ul class="list-unstyled mb-0">
                                    <li>✓ Gestão de contratos resultantes de licitações</li>
                                    <li>✓ Acompanhamento de vigências e prazos</li>
                                    <li>✓ Controle de aditivos contratuais</li>
                                    <li>✓ Fiscalização da execução contratual</li>
                                    <li>✓ Relatórios de performance</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>modulos/index" class="btn btn-primary">
                            <i data-lucide="arrow-left" style="width: 16px; height: 16px; margin-right: 5px;"></i>
                            Voltar aos Módulos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>