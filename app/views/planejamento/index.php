<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planejamento - Sistema de Licitações</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
            left: 0;
            top: 0;
        }

        .sidebar.closed {
            transform: translateX(-280px);
        }

        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h2 {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .sidebar-header small {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-section {
            margin-bottom: 25px;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 25px 10px 25px;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-item {
            margin-bottom: 3px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #4CAF50;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-link.active {
            background: rgba(76, 175, 80, 0.2);
            border-left-color: #4CAF50;
        }

        .nav-icon {
            margin-right: 15px;
            width: 20px;
            height: 20px;
        }

        /* Sidebar Summary */
        .sidebar-summary {
            padding: 20px;
            margin: 20px 0;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-title {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            text-align: center;
        }

        .summary-stats {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .summary-number {
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            color: white;
            margin-bottom: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 40px 30px 30px 30px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.sidebar-closed {
            margin-left: 0;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 20px;
            left: 290px;
            z-index: 1001;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 12px;
            padding: 14px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        body.sidebar-closed .sidebar-toggle {
            left: 20px !important;
        }


        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background: #45a049;
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 18px;
            padding: 30px 25px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .stat-card:hover:before {
            transform: translateX(100%);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-title {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-icon {
            width: 24px;
            height: 24px;
        }

        .stat-card:nth-child(1) .stat-icon {
            color: #E74C3C !important;
        }

        .stat-card:nth-child(2) .stat-icon {
            color: #2ECC71 !important;
        }

        .stat-card:nth-child(3) .stat-icon {
            color: #3498DB !important;
        }

        .stat-card:nth-child(4) .stat-icon {
            color: #F39C12 !important;
        }

        .stat-value {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
        }

        /* Content Sections */
        .content-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .section-header {
            padding: 20px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            color: white;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-actions {
            display: flex;
            gap: 10px;
        }

        /* Chart Container */
        .chart-container {
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .chart-wrapper {
            width: 150px !important;
            max-width: 150px !important;
            height: 150px !important;
        }

        .chart-canvas {
            max-height: 150px !important;
            max-width: 150px !important;
            width: 150px !important;
            height: 150px !important;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .data-table th {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .data-table td {
            color: rgba(255, 255, 255, 0.9);
        }

        .data-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .badge-warning {
            background: rgba(255, 193, 7, 0.2);
            color: #FFC107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .badge-danger {
            background: rgba(244, 67, 54, 0.2);
            color: #F44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        .badge-info {
            background: rgba(33, 150, 243, 0.2);
            color: #2196F3;
            border: 1px solid rgba(33, 150, 243, 0.3);
        }

        .badge-secondary {
            background: rgba(158, 158, 158, 0.2);
            color: #9E9E9E;
            border: 1px solid rgba(158, 158, 158, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.7);
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: white;
            margin-bottom: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-280px);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar-toggle {
                left: 20px !important;
            }
        }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .data-table {
                font-size: 14px;
            }

            .data-table th,
            .data-table td {
                padding: 10px;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
        </button>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Sistema de Licitações</h2>
                <small>Planejamento PCA</small>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="<?= BASE_URL ?>modulos/index" class="nav-link">
                        <i data-lucide="home" class="nav-icon"></i>
                        Início
                    </a>
                </div>
                
                <!-- Seção Planejamento -->
                <div class="nav-section">
                    <div class="nav-section-title">Planejamento</div>
                    <div class="nav-item">
                        <a href="<?= BASE_URL ?>planejamento/index" class="nav-link active">
                            <i data-lucide="calendar-check" class="nav-icon"></i>
                            Dashboard
                        </a>
                    </div>
                    <?php if (podeImportarPCA()): ?>
                    <div class="nav-item">
                        <a href="<?= BASE_URL ?>planejamento/importar" class="nav-link">
                            <i data-lucide="upload" class="nav-icon"></i>
                            Importar PCA
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="nav-item">
                        <a href="#" onclick="scrollToTable()" class="nav-link">
                            <i data-lucide="table" class="nav-icon"></i>
                            Ver Contratações
                        </a>
                    </div>
                </div>
                
                <!-- Seção Sistema -->
                <div class="nav-section">
                    <div class="nav-section-title">Sistema</div>
                    <div class="nav-item">
                        <a href="<?= BASE_URL ?>licitacoes/index" class="nav-link">
                            <i data-lucide="clipboard-list" class="nav-icon"></i>
                            Licitações
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="<?= BASE_URL ?>contratos/index" class="nav-link">
                            <i data-lucide="file-signature" class="nav-icon"></i>
                            Contratos
                        </a>
                    </div>
                    <?php if ($_SESSION['usuario']['tipo_usuario'] === 'admin'): ?>
                    <div class="nav-item">
                        <a href="<?= BASE_URL ?>usuarios/index" class="nav-link">
                            <i data-lucide="users" class="nav-icon"></i>
                            Usuários
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Seção Relatórios -->
                <div class="nav-section">
                    <div class="nav-section-title">Relatórios</div>
                    <div class="nav-item">
                        <a href="#" onclick="exportarDados()" class="nav-link">
                            <i data-lucide="download" class="nav-icon"></i>
                            Exportar Dados
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" onclick="gerarRelatorio()" class="nav-link">
                            <i data-lucide="bar-chart-3" class="nav-icon"></i>
                            Relatório PCA
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Resumo Rápido -->
            <div class="sidebar-summary">
                <div class="summary-title">Resumo Rápido</div>
                <div class="summary-stats">
                    <div class="summary-item">
                        <span class="summary-number"><?= count($contratacoes) ?></span>
                        <span class="summary-label">Contratações</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-number">R$ <?= number_format(($resumoGeral['valor_total'] ?: 0) / 1000000, 1) ?>M</span>
                        <span class="summary-label">Valor Total</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-number"><?= count($contratacoes) - array_sum(array_column($contratacoes, 'ja_licitada')) ?></span>
                        <span class="summary-label">Pendentes</span>
                    </div>
                </div>
            </div>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?= strtoupper(substr($_SESSION['usuario']['nome'], 0, 2)) ?>
                    </div>
                    <div>
                        <div style="font-weight: 600;"><?= $_SESSION['usuario']['nome'] ?></div>
                        <small><?= $_SESSION['usuario']['departamento'] ?? 'CGLIC' ?></small>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>usuarios/logout" class="logout-btn">
                    <i data-lucide="log-out" style="width: 16px; height: 16px; margin-right: 5px;"></i>
                    Sair
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Total de Contratações</div>
                        <i data-lucide="briefcase" class="stat-icon" style="color: #E74C3C !important;"></i>
                    </div>
                    <div class="stat-value"><?= count($contratacoes) ?></div>
                    <div class="stat-label">Registros no PCA</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Valor Total</div>
                        <i data-lucide="dollar-sign" class="stat-icon"></i>
                    </div>
                    <div class="stat-value">R$ <?= number_format($resumoGeral['valor_total'] ?: 0, 0, ',', '.') ?></div>
                    <div class="stat-label">Planejamento Anual</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Licitações Criadas</div>
                        <i data-lucide="check-circle" class="stat-icon"></i>
                    </div>
                    <div class="stat-value"><?= array_sum(array_column($contratacoes, 'ja_licitada')) ?></div>
                    <div class="stat-label">Processos Iniciados</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Pendentes</div>
                        <i data-lucide="clock" class="stat-icon"></i>
                    </div>
                    <div class="stat-value"><?= count($contratacoes) - array_sum(array_column($contratacoes, 'ja_licitada')) ?></div>
                    <div class="stat-label">Aguardando Licitação</div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="content-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i data-lucide="pie-chart" style="width: 20px; height: 20px;"></i>
                        Distribuição por Status
                    </h3>
                </div>
                <div class="chart-container">
                    <div class="chart-wrapper">
                        <canvas id="statusChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="content-section" id="tabela-contratacoes">
                <div class="section-header">
                    <h3 class="section-title">
                        <i data-lucide="list" style="width: 20px; height: 20px;"></i>
                        Contratações do PCA
                        <span class="badge badge-info" style="margin-left: 10px; font-size: 10px;">
                            <?= count($contratacoes) ?> registros
                        </span>
                    </h3>
                    <div class="section-actions">
                        <button class="btn btn-secondary btn-sm" onclick="exportarDados()">
                            <i data-lucide="download" style="width: 14px; height: 14px;"></i>
                            Exportar
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <?php if (empty($contratacoes)): ?>
                        <div class="empty-state">
                            <i data-lucide="inbox" class="empty-state-icon"></i>
                            <h3>Nenhuma contratação encontrada</h3>
                            <p>
                                <?php if (podeImportarPCA()): ?>
                                    <a href="<?= BASE_URL ?>planejamento/importar" class="btn btn-primary">
                                        <i data-lucide="upload" style="width: 16px; height: 16px;"></i>
                                        Importar dados do PCA
                                    </a>
                                <?php else: ?>
                                    Entre em contato com o coordenador para importar dados do PCA.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Título</th>
                                    <th>Área</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Situação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contratacoes as $contratacao): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($contratacao['numero_contratacao']) ?></strong>
                                            <?php if ($contratacao['data_inicio_processo']): ?>
                                                <br><small style="opacity: 0.7;">Início: <?= date('d/m/Y', strtotime($contratacao['data_inicio_processo'])) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($contratacao['titulo_contratacao']) ?>">
                                                <?= htmlspecialchars($contratacao['titulo_contratacao']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= htmlspecialchars($contratacao['area_requisitante']) ?></span>
                                        </td>
                                        <td>
                                            <?php if ($contratacao['valor_total_contratacao']): ?>
                                                R$ <?= number_format($contratacao['valor_total_contratacao'], 2, ',', '.') ?>
                                            <?php else: ?>
                                                <span style="opacity: 0.5;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = match($contratacao['status_contratacao']) {
                                                'PLANEJADO' => 'badge-warning',
                                                'EM_ANDAMENTO' => 'badge-info', 
                                                'CONCLUIDO' => 'badge-success',
                                                'CANCELADO' => 'badge-danger',
                                                default => 'badge-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= ucfirst(str_replace('_', ' ', $contratacao['status_contratacao'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($contratacao['ja_licitada']): ?>
                                                <span class="badge badge-success">
                                                    <i data-lucide="check-circle" style="width: 12px; height: 12px; margin-right: 4px;"></i>
                                                    Licitada
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">
                                                    <i data-lucide="clock" style="width: 12px; height: 12px; margin-right: 4px;"></i>
                                                    Pendente
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <a href="<?= BASE_URL ?>planejamento/details/<?= urlencode($contratacao['numero_contratacao']) ?>" 
                                                   class="btn btn-secondary" style="padding: 6px 10px; font-size: 12px;" title="Ver Detalhes">
                                                    <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                                </a>
                                                <?php if (podeEditarLicitacoes() && !$contratacao['ja_licitada']): ?>
                                                    <a href="<?= BASE_URL ?>licitacoes/create?numero_contratacao=<?= urlencode($contratacao['numero_contratacao']) ?>" 
                                                       class="btn btn-primary" style="padding: 6px 10px; font-size: 12px;" title="Criar Licitação">
                                                        <i data-lucide="plus-circle" style="width: 14px; height: 14px;"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pagination -->
            <?php if (!empty($contratacoes)): ?>
                <div style="display: flex; justify-content: center; margin-top: 20px;">
                    <?= $pagination->renderizar() ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        // Inicializar ícones
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            initChart();
        });

        // Inicializar gráfico
        function initChart() {
            const ctx = document.getElementById('statusChart');
            if (!ctx) return;

            const statusData = <?= json_encode($resumoPorStatus) ?>;
            const labels = statusData.map(item => item.status_contratacao.replace('_', ' '));
            const data = statusData.map(item => item.total_contratacoes);
            const colors = [
                'rgba(231, 76, 60, 0.9)',    // Vermelho
                'rgba(46, 204, 113, 0.9)',   // Verde
                'rgba(52, 152, 219, 0.9)',   // Azul
                'rgba(243, 156, 18, 0.9)',   // Laranja
                'rgba(155, 89, 182, 0.9)'    // Roxo
            ];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderColor: colors.map(color => color.replace('0.8', '1')),
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'rgba(255, 255, 255, 0.8)',
                                padding: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }

        // Atualizar dados
        function atualizarDados() {
            const btn = event.target.closest('.btn');
            const icon = btn.querySelector('i');
            
            icon.style.animation = 'spin 1s linear infinite';
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }

        // Exportar dados
        function exportarDados() {
            const btn = event.target.closest('.nav-link') || event.target;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i data-lucide="loader-2" style="width: 16px; height: 16px; margin-right: 10px; animation: spin 1s linear infinite;"></i>Exportando...';
            
            // Simular exportação
            setTimeout(() => {
                btn.innerHTML = originalText;
                lucide.createIcons();
                
                // Criar e baixar arquivo CSV
                const csvContent = "data:text/csv;charset=utf-8," 
                    + "Número,Título,Área,Valor,Status\n"
                    + "<?php foreach($contratacoes as $c): ?>"
                    + "<?= $c['numero_contratacao'] ?>,<?= addslashes($c['titulo_contratacao']) ?>,<?= $c['area_requisitante'] ?>,<?= $c['valor_total_contratacao'] ?>,<?= $c['status_contratacao'] ?>\n"
                    + "<?php endforeach; ?>";
                    
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "planejamento_pca.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }, 2000);
        }

        // Scroll para tabela
        function scrollToTable() {
            const tableSection = document.getElementById('tabela-contratacoes');
            if (tableSection) {
                tableSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // Adicionar efeito visual temporário
                tableSection.style.boxShadow = '0 0 20px rgba(255, 255, 255, 0.3)';
                setTimeout(() => {
                    tableSection.style.boxShadow = '';
                }, 2000);
            }
        }

        // Gerar relatório
        function gerarRelatorio() {
            alert('Funcionalidade de relatório em desenvolvimento');
        }

        // Sidebar toggle function
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const body = document.body;
            
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.classList.toggle('open');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('closed');
                mainContent.classList.toggle('sidebar-closed');
                body.classList.toggle('sidebar-closed');
            }
        }

        // Auto-hide on mobile when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const toggle = document.querySelector('.sidebar-toggle');
                
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    </script>
</body>
</html>