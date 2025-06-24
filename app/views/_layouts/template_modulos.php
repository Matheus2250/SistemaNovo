<?php 
// A página de módulos é standalone - não precisa de template
// O conteúdo já é uma página HTML completa
if (isset($viewPath) && file_exists($viewPath)) {
    include $viewPath;
} else {
    echo "Página não encontrada.";
}
?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Inicializar ícones Lucide
        document.addEventListener('DOMContentLoaded', function() {
            // Aguardar carregamento completo e inicializar ícones
            setTimeout(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 100);
        });

        // Animações melhoradas para os cards de módulo
        document.addEventListener('DOMContentLoaded', function() {
            const moduleCards = document.querySelectorAll('.module-card');
            
            moduleCards.forEach(card => {
                // Hover effect melhorado
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                    this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    this.style.boxShadow = 'var(--shadow)';
                });
                
                // Click effect com loading
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Adicionar efeito de loading
                    const icon = this.querySelector('.module-icon i');
                    if (icon) {
                        const originalIcon = icon.getAttribute('data-lucide');
                        icon.setAttribute('data-lucide', 'loader-2');
                        icon.style.animation = 'spin 1s linear infinite';
                        lucide.createIcons();
                    }
                    
                    // Adicionar classe loading
                    this.classList.add('loading');
                    this.style.opacity = '0.8';
                    
                    // Navegar após delay
                    const href = this.getAttribute('onclick');
                    if (href) {
                        const url = href.replace("window.location.href='", '').replace("'", '');
                        setTimeout(() => {
                            window.location.href = url;
                        }, 500);
                    }
                });
            });
        });
        
        // Prevenir comportamentos indesejados
        document.addEventListener('DOMContentLoaded', function() {
            // Remover qualquer navbar do Bootstrap que possa estar interferindo
            const navbars = document.querySelectorAll('.navbar');
            navbars.forEach(navbar => {
                if (!navbar.classList.contains('modules-nav')) {
                    navbar.style.display = 'none';
                }
            });
            
            // Garantir que não há overflow horizontal
            document.body.style.overflowX = 'hidden';
            
            // Prevenir scroll horizontal
            const containers = document.querySelectorAll('.container-fluid, .container');
            containers.forEach(container => {
                container.style.maxWidth = '100%';
                container.style.overflowX = 'hidden';
            });
        });
    </script>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Garantir que não há elementos que causam scroll horizontal */
        * {
            box-sizing: border-box;
        }
        
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        
        .container, .container-fluid {
            overflow-x: hidden;
            max-width: 100%;
        }
        
        /* Esconder qualquer navegação não desejada */
        .navbar:not(.modules-nav),
        .nav-tabs,
        .nav-pills {
            display: none !important;
        }
        
        /* Melhorar loading state */
        .module-card.loading {
            pointer-events: none;
            opacity: 0.8;
        }
    </style>
</body>
</html>