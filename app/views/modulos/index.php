<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Licitações - Seleção de Módulos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <style>
        .selecao-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #667eea 100%);
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .selecao-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
            pointer-events: none;
        }

        .header-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 0;
            position: relative;
            z-index: 10;
        }

        .header-content {
            width: 100%;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .logo-section p {
            color: rgba(255, 255, 255, 0.9);
            margin: 5px 0 0 0;
            font-size: 14px;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .user-info h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .user-info small {
            opacity: 0.9;
            font-size: 12px;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            z-index: 5;
        }

        .modules-container {
            max-width: 1200px;
            width: 100%;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 50px;
        }

        .welcome-section h2 {
            color: white;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .welcome-section p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
            margin-bottom: 0;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .module-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .module-card:hover::before {
            left: 100%;
        }

        .module-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.15);
        }

        .module-card.planejamento {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .module-card.licitacao {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.2) 0%, rgba(46, 204, 113, 0.2) 100%);
            border-color: rgba(39, 174, 96, 0.3);
        }

        .module-card.contratos {
            background: linear-gradient(135deg, rgba(155, 89, 182, 0.2) 0%, rgba(142, 68, 173, 0.2) 100%);
            border-color: rgba(155, 89, 182, 0.3);
        }

        .module-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .module-card:hover .module-icon {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.3);
        }

        .module-title {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .module-description {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .module-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            color: white;
            font-size: 28px;
            font-weight: 700;
            display: block;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(30, 60, 114, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            color: white;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .header-content {
                padding: 0 20px;
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .user-section {
                flex-direction: column;
                gap: 10px;
            }

            .welcome-section h2 {
                font-size: 28px;
            }

            .welcome-section p {
                font-size: 16px;
            }

            .modules-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .module-card {
                padding: 30px 20px;
            }

            .module-stats {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Animação de entrada */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        .fade-in:nth-child(1) { animation-delay: 0.1s; }
        .fade-in:nth-child(2) { animation-delay: 0.3s; }
        .fade-in:nth-child(3) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="selecao-container">
        <!-- Header -->
        <header class="header-section">
            <div class="header-content">
                <div class="logo-section">
                    <h1><?= APP_NAME ?></h1>
                    <p>Ministério da Saúde - Coordenação-Geral de Licitações</p>
                </div>

                <?php if (isset($_SESSION['usuario'])): ?>
                <div class="user-section">
                    <div class="user-avatar">
                        <?= strtoupper(substr($_SESSION['usuario']['nome'], 0, 2)) ?>
                    </div>
                    <div class="user-info">
                        <h3><?= $_SESSION['usuario']['nome'] ?></h3>
                        <small><?= $_SESSION['usuario']['departamento'] ?? 'CGLIC' ?> - <?= ucfirst($_SESSION['usuario']['tipo_usuario']) ?></small>
                    </div>
                    
                    <?php if ($_SESSION['usuario']['tipo_usuario'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>usuarios/index" class="btn-logout">
                        <i data-lucide="users" style="width: 14px; height: 14px;"></i>
                        Usuários
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>usuarios/logout" class="btn-logout">
                        <i data-lucide="log-out" style="width: 14px; height: 14px;"></i>
                        Sair
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Conteúdo Principal -->
        <main class="main-content">
            <div class="modules-container">
                <div class="welcome-section">
                    <h2>Selecione um Módulo</h2>
                    <p>Escolha o sistema que deseja acessar para gerenciar processos e informações</p>
                </div>

                <div class="modules-grid">
                    <!-- Módulo Planejamento -->
                    <?php if (in_array($_SESSION['usuario']['tipo_usuario'], ['admin', 'operador'])): ?>
                    <div class="module-card planejamento fade-in floating" data-url="<?= BASE_URL ?>modulos/planejamento">
                        <div class="module-icon">
                            <i data-lucide="calendar-check" style="width: 40px; height: 40px; color: white;"></i>
                        </div>
                        <h3 class="module-title">Planejamento</h3>
                        <p class="module-description">
                            Gerencie o Plano de Contratações Anuais (PCA), importe dados de planilhas e monitore o planejamento de contratações
                        </p>
                        <div class="module-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?= number_format($planejamento['total_pca']) ?></span>
                                <span class="stat-label">Contratações</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">R$ <?= number_format($planejamento['valor_total'] / 1000000, 1) ?>M</span>
                                <span class="stat-label">Valor Total</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?= number_format($planejamento['importacoes']) ?></span>
                                <span class="stat-label">Importações</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Módulo Licitações -->
                    <div class="module-card licitacao fade-in floating" data-url="<?= BASE_URL ?>modulos/licitacoes">
                        <div class="module-icon">
                            <i data-lucide="clipboard-list" style="width: 40px; height: 40px; color: white;"></i>
                        </div>
                        <h3 class="module-title">Licitações</h3>
                        <p class="module-description">
                            Controle processos licitatórios, acompanhe prazos, gerencie documentação e monitore o andamento das licitações
                        </p>
                        <div class="module-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?= number_format($licitacoes['total']) ?></span>
                                <span class="stat-label">Total</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?= number_format($licitacoes['em_andamento']) ?></span>
                                <span class="stat-label">Ativas</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?= number_format($licitacoes['homologadas']) ?></span>
                                <span class="stat-label">Finalizadas</span>
                            </div>
                        </div>
                    </div>

                    <!-- Módulo Contratos -->
                    <?php if (in_array($_SESSION['usuario']['tipo_usuario'], ['admin', 'operador'])): ?>
                    <div class="module-card contratos fade-in floating" data-url="<?= BASE_URL ?>modulos/contratos">
                        <div class="module-icon">
                            <i data-lucide="file-signature" style="width: 40px; height: 40px; color: white;"></i>
                        </div>
                        <h3 class="module-title">Contratos</h3>
                        <p class="module-description">
                            Gerencie contratos resultantes de licitações, acompanhe vigências, fiscalize execução e controle aditivos
                        </p>
                        <div class="module-stats">
                            <div class="stat-item">
                                <span class="stat-number">0</span>
                                <span class="stat-label">Ativos</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">0</span>
                                <span class="stat-label">Vencendo</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">0</span>
                                <span class="stat-label">Finalizados</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <i data-lucide="loader-2" class="loading-spinner" style="width: 48px; height: 48px;"></i>
    </div>

    <script>
        // Inicializar ícones
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Configurar cliques nos cards de módulo
        document.addEventListener('DOMContentLoaded', function() {
            const moduleCards = document.querySelectorAll('.module-card[data-url]');
            
            moduleCards.forEach(card => {
                card.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    if (url) {
                        const overlay = document.getElementById('loadingOverlay');
                        overlay.style.display = 'flex';
                        
                        setTimeout(() => {
                            window.location.href = url;
                        }, 800);
                    }
                });
            });
        });

        // Efeitos de paralaxe suave no mouse
        document.addEventListener('mousemove', function(e) {
            const cards = document.querySelectorAll('.module-card');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            cards.forEach((card, index) => {
                const xRotation = (mouseY - 0.5) * 5;
                const yRotation = (mouseX - 0.5) * -5;
                
                card.style.transform = `perspective(1000px) rotateX(${xRotation}deg) rotateY(${yRotation}deg)`;
            });
        });

        // Reset da rotação quando mouse sai da tela
        document.addEventListener('mouseleave', function() {
            const cards = document.querySelectorAll('.module-card');
            cards.forEach(card => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
            });
        });
    </script>
</body>
</html>