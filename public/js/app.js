/**
 * JavaScript Principal do Sistema de Licitações
 * Funcionalidades gerais e interações da interface
 */

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar ícones Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Configurar tooltips do Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Configurar popovers do Bootstrap
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Inicializar funcionalidades
    initSidebar();
    initTableFeatures();
    initFormValidation();
    initModuleCards();
    initSearchFeatures();
});

/**
 * Funcionalidades da Sidebar
 */
function initSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('[data-sidebar-toggle]');
    const contentArea = document.querySelector('.content-area');
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            if (contentArea) {
                contentArea.classList.toggle('expanded');
            }
        });
    }
    
    // Marcar item ativo no menu
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.nav-link');
    
    menuLinks.forEach(link => {
        if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
}

/**
 * Funcionalidades dos Cards de Módulo
 */
function initModuleCards() {
    const moduleCards = document.querySelectorAll('.module-card');
    
    moduleCards.forEach(card => {
        // Efeito hover melhorado
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
        
        // Loading ao clicar
        card.addEventListener('click', function() {
            if (this.hasAttribute('onclick')) {
                const icon = this.querySelector('.module-icon i');
                if (icon) {
                    icon.setAttribute('data-lucide', 'loader-2');
                    icon.style.animation = 'spin 1s linear infinite';
                    lucide.createIcons();
                }
                
                // Adicionar classe de loading
                this.classList.add('loading');
                
                // Executar o onclick após um delay
                setTimeout(() => {
                    const onclickCode = this.getAttribute('onclick');
                    if (onclickCode) {
                        eval(onclickCode);
                    }
                }, 300);
            }
        });
    });
}

/**
 * Funcionalidades das Tabelas
 */
function initTableFeatures() {
    // Tabelas responsivas
    const tables = document.querySelectorAll('.table');
    
    tables.forEach(table => {
        // Adicionar hover nas linhas
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
        
        // Ordenação simples por clique no cabeçalho
        const headers = table.querySelectorAll('th[data-sortable]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                sortTable(table, this);
            });
        });
    });
}

/**
 * Ordenação de tabela
 */
function sortTable(table, header) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const columnIndex = Array.from(header.parentNode.children).indexOf(header);
    const isAscending = header.classList.contains('sort-asc');
    
    // Limpar classes de ordenação
    header.parentNode.querySelectorAll('th').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
    });
    
    // Ordenar linhas
    rows.sort((a, b) => {
        const aText = a.children[columnIndex].textContent.trim();
        const bText = b.children[columnIndex].textContent.trim();
        
        // Tentar converter para número
        const aNum = parseFloat(aText.replace(/[^\d.-]/g, ''));
        const bNum = parseFloat(bText.replace(/[^\d.-]/g, ''));
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return isAscending ? bNum - aNum : aNum - bNum;
        } else {
            return isAscending ? bText.localeCompare(aText) : aText.localeCompare(bText);
        }
    });
    
    // Aplicar nova ordem
    rows.forEach(row => tbody.appendChild(row));
    
    // Adicionar classe de ordenação
    header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
}

/**
 * Validação de formulários
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            this.classList.add('was-validated');
        });
        
        // Validação em tempo real
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    });
}

/**
 * Validar campo individual
 */
function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let message = '';
    
    // Validações específicas
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        message = 'Este campo é obrigatório.';
    } else if (field.type === 'email' && value && !isValidEmail(value)) {
        isValid = false;
        message = 'Digite um email válido.';
    } else if (field.type === 'tel' && value && !isValidPhone(value)) {
        isValid = false;
        message = 'Digite um telefone válido.';
    } else if (field.hasAttribute('data-min-length') && value.length < parseInt(field.getAttribute('data-min-length'))) {
        isValid = false;
        message = `Mínimo de ${field.getAttribute('data-min-length')} caracteres.`;
    }
    
    // Aplicar feedback visual
    field.classList.remove('is-valid', 'is-invalid');
    field.classList.add(isValid ? 'is-valid' : 'is-invalid');
    
    // Mostrar mensagem
    let feedback = field.parentNode.querySelector('.invalid-feedback, .valid-feedback');
    if (feedback) {
        feedback.textContent = message;
    }
    
    return isValid;
}

/**
 * Validar formulário completo
 */
function validateForm(form) {
    const fields = form.querySelectorAll('input, select, textarea');
    let isFormValid = true;
    
    fields.forEach(field => {
        if (!validateField(field)) {
            isFormValid = false;
        }
    });
    
    return isFormValid;
}

/**
 * Funcionalidades de busca
 */
function initSearchFeatures() {
    const searchInputs = document.querySelectorAll('[data-search]');
    
    searchInputs.forEach(input => {
        const target = input.getAttribute('data-search');
        const targetElements = document.querySelectorAll(target);
        
        input.addEventListener('input', debounce(function() {
            const searchTerm = this.value.toLowerCase();
            
            targetElements.forEach(element => {
                const text = element.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                
                element.style.display = shouldShow ? '' : 'none';
                
                // Destacar termo encontrado
                if (shouldShow && searchTerm) {
                    highlightText(element, searchTerm);
                }
            });
        }, 300));
    });
}

/**
 * Utilitários
 */

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Validar email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validar telefone
function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
    return phoneRegex.test(phone.replace(/\D/g, ''));
}

// Destacar texto
function highlightText(element, searchTerm) {
    // Implementação básica - pode ser melhorada
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    const originalText = element.textContent;
    
    if (regex.test(originalText)) {
        element.innerHTML = originalText.replace(regex, '<mark>$1</mark>');
    }
}

// Formatar valores monetários
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Formatar datas
function formatDate(date, format = 'dd/MM/yyyy') {
    if (!(date instanceof Date)) {
        date = new Date(date);
    }
    
    return new Intl.DateTimeFormat('pt-BR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).format(date);
}

// Mostrar notificação toast
function showToast(message, type = 'info') {
    // Criar toast element se não existir
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
    toastEl.setAttribute('role', 'alert');
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toastEl);
    
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
    
    // Remover após esconder
    toastEl.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

// Confirmar ação
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Loading state para botões
function setButtonLoading(button, loading = true) {
    if (loading) {
        button.disabled = true;
        button.innerHTML = '<i data-lucide="loader-2" class="spinner"></i> Processando...';
    } else {
        button.disabled = false;
        button.innerHTML = button.getAttribute('data-original-text') || 'Enviar';
    }
    lucide.createIcons();
}

// Exportar funções globais
window.SistemaLicitacoes = {
    showToast,
    confirmAction,
    setButtonLoading,
    formatCurrency,
    formatDate,
    validateForm,
    validateField
};