# Sistema de Licitações - Documentação Técnica

## Visão Geral do Sistema

O **Sistema de Licitações** é uma aplicação web PHP desenvolvida para gerenciar processos licitatórios do Ministério da Saúde. O sistema permite o cadastro, acompanhamento e controle de licitações, integrando dados do PCA (Plano de Contratações Anuais).

### Características Principais
- **Linguagem**: PHP puro (sem frameworks)
- **Banco de Dados**: MySQL/MariaDB
- **Arquitetura**: MVC (Model-View-Controller)
- **Interface**: Bootstrap 5 para responsividade
- **Segurança**: Sistema de autenticação e autorização por níveis

## Estrutura do Projeto

```
sistema_licitacao/
├── app/
│   ├── controllers/           # Controladores da aplicação
│   │   ├── HomeController.php
│   │   ├── LicitacoesController.php
│   │   ├── UsuariosController.php
│   │   └── planejamento/
│   │       ├── ImportacaoController.php
│   │       └── PlanejamentoController.php
│   ├── helpers/              # Funções auxiliares
│   │   ├── auth.php         # Autenticação e autorização
│   │   └── pagination.php   # Paginação
│   ├── models/              # Modelos de dados
│   │   ├── LicitacaoModel.php
│   │   ├── UsuarioModel.php
│   │   └── planejamento/
│   │       └── PlanejamentoModel.php
│   └── views/               # Templates e views
│       ├── _layouts/        # Layouts base
│       ├── home/
│       ├── licitacoes/
│       ├── planejamento/
│       └── usuarios/
├── banco/                   # Scripts SQL
│   └── sistema_licitacao.sql
├── config/                  # Configurações
│   ├── config.php          # Configurações gerais
│   └── database.php        # Conexão com banco
├── core/                   # Classes core do framework
│   ├── Controller.php      # Classe base dos controladores
│   ├── Model.php          # Classe base dos modelos
│   └── View.php           # Classe para renderização
├── public/                 # Arquivos públicos
│   ├── css/
│   └── index.php          # Ponto de entrada
└── tabelas/               # Scripts SQL específicos
```

## Configuração do Sistema

### Configurações Principais (config/config.php)
```php
define('APP_NAME', 'Sistema de Licitações');
define('BASE_URL', 'http://10.1.41.251:8080/sistema_licitacoes/public/');
```

### Configurações do Banco (config/database.php)
```php
$host = 'localhost';
$db   = 'sistema_licitacao';
$user = 'user_cglic';
$pass = 'Numse!2020';
```

## Estrutura do Banco de Dados

### Tabelas Principais

#### 1. `usuarios`
Gerencia usuários do sistema com diferentes níveis de acesso:
- **Campos**: id, nome, email, senha, tipo_usuario, nivel_acesso, departamento, ativo
- **Tipos**: admin, operador, visitante
- **Níveis**: 1 (admin), 4 (operador/visitante)

#### 2. `licitacoes`
Tabela principal para processos licitatórios:
- **Campos principais**: nup, data_entrada_dipli, resp_instrucao, area_demandante, pregoeiro
- **Valores**: valor_estimado, valor_homologado, economia (calculada automaticamente)
- **Status**: EM_ANDAMENTO, HOMOLOGADO, FRACASSADO, REVOGADO, CANCELADO, PREPARACAO
- **Modalidades**: PREGAO, INEXIBILIDADE, DISPENSA
- **Tipos**: TRADICIONAL, SRP, COTACAO

#### 3. `pca_dados`
Dados importados do PCA (Plano de Contratações Anuais):
- **Campos**: numero_contratacao, titulo_contratacao, area_requisitante, valor_total_contratacao
- **Integração**: Vinculação com licitações via pca_dados_id

#### 4. `pca_importacoes`
Controle de importações de arquivos PCA:
- **Status**: processando, concluido, erro
- **Métricas**: total_linhas, linhas_processadas, registros_novos

#### 5. `pca_riscos`
Gestão de riscos associados aos processos:
- **Níveis**: baixo, medio, alto, extremo
- **Status**: pendente, em_andamento, concluida, cancelada

### Triggers do Banco
- **tr_licitacoes_calcular_economia**: Calcula automaticamente a economia (valor_estimado - valor_homologado)
- **tr_licitacoes_log_mudancas**: Registra mudanças de situação no log do sistema

## Funcionalidades do Sistema

### 1. Gestão de Licitações
- **CRUD completo**: Criar, visualizar, editar, excluir licitações
- **Integração PCA**: Busca automática de dados por número de contratação
- **Validações**: Verificação de duplicidade, campos obrigatórios
- **Economia**: Cálculo automático da economia obtida

### 2. Controle de Usuários
- **Autenticação**: Login com email e senha
- **Autorização**: Níveis de acesso diferenciados
- **Perfis**: Admin, operador, visitante
- **Departamentos**: CGLIC, DIPLI, CODEP, etc.

### 3. Relatórios e Dashboards
- **Resumos**: Por situação, modalidade, pregoeiro
- **Métricas**: Economia total, licitações próximas
- **Filtros**: Por período, status, modalidade

### 4. Importação PCA
- **Upload**: Arquivos CSV/Excel do PCA
- **Processamento**: Análise e validação automática
- **Logs**: Controle de erros e sucessos

## Arquitetura MVC

### Controllers
- **HomeController**: Dashboard principal
- **LicitacoesController**: Gestão de licitações
- **UsuariosController**: Gestão de usuários
- **PlanejamentoController**: Módulo PCA

### Models
- **LicitacaoModel**: Operações com licitações
- **UsuarioModel**: Operações com usuários
- **PlanejamentoModel**: Operações com PCA

### Views
- **Layouts**: Templates base com Bootstrap
- **Componentes**: Formulários, tabelas, modais
- **Responsividade**: Interface adaptável

### Roteamento
Sistema de roteamento simples via URL:
- **Padrão**: `/controller/action/parameter`
- **Exemplo**: `/licitacoes/edit/123`
- **Sanitização**: Filtros de segurança aplicados

## Sistema de Autenticação

### Funções de Segurança (helpers/auth.php)
- **requireLogin()**: Exige login para acesso
- **requireLicitacaoOuCoordenador()**: Exige permissões específicas
- **Sessões**: Controle via $_SESSION

### Níveis de Acesso
1. **Admin (nível 1)**: Acesso total ao sistema
2. **Operador (nível 4)**: Acesso limitado às operações
3. **Visitante (nível 4)**: Acesso apenas visualização

## APIs e Integração

### Endpoints AJAX
- **buscarDadosPca**: Busca dados do PCA por número
- **pesquisarContratacoes**: Pesquisa contratações por termo
- **Formato**: JSON responses com tratamento de erros

### Integração Externa
- **SharePoint**: Links para documentos oficiais
- **PCA**: Importação de dados de contratações

## Recursos Técnicos

### Segurança
- **Prepared Statements**: Proteção contra SQL injection
- **Sanitização**: Filtros de entrada
- **Hashing**: Senhas com password_hash()
- **Validação**: Tokens CSRF implícitos

### Performance
- **Paginação**: Listagens otimizadas
- **Índices**: Otimização de consultas
- **Cache**: Sessões otimizadas

### Responsividade
- **Bootstrap 5**: Framework CSS moderno
- **Mobile-first**: Design adaptável
- **Acessibilidade**: Padrões WCAG

## Dependências e Requisitos

### Servidor
- **PHP**: 7.4+ (recomendado 8.0+)
- **MySQL/MariaDB**: 5.7+
- **Apache/Nginx**: Servidor web
- **Extensions**: PDO, mbstring, json

### Frontend
- **Bootstrap**: 5.x
- **JavaScript**: Vanilla JS
- **CSS**: Customizações mínimas

## Interface do Usuário

### Tela de Seleção de Módulos
O sistema possui uma tela inicial que permite navegar entre os módulos principais:

#### Módulos Disponíveis
1. **Gestão de Licitações**: CRUD de licitações, integração PCA, relatórios
2. **Planejamento (PCA)**: Importação de dados, gestão de contratações, riscos
3. **Relatórios e Analytics**: Dashboards executivos, métricas, exportações

#### Características
- **Design Responsivo**: Baseado no sistema SCGLIC
- **Estatísticas em Tempo Real**: Cards com métricas atualizadas
- **Ações Rápidas**: Acesso direto às funcionalidades principais
- **Animações**: Efeitos visuais na navegação

### Paleta de Cores
- **Primária**: #3498db (azul)
- **Secundária**: #2c3e50 (azul escuro)
- **Sucesso**: #27ae60 (verde)
- **Perigo**: #e74c3c (vermelho)
- **Aviso**: #f39c12 (laranja)
- **Info**: #17a2b8 (azul claro)

### Componentes de Interface
- **Cards Estatísticos**: Métricas com ícones e cores temáticas
- **Tabelas Responsivas**: Ordenação, busca e paginação
- **Formulários Validados**: Validação em tempo real
- **Sidebar Adaptável**: Menu lateral colapsável
- **Toasts e Modais**: Notificações e diálogos

## Comandos Úteis

### Instalação XAMPP
```bash
# Configurar banco no phpMyAdmin
# Acessar: http://localhost/phpmyadmin
# Criar banco: sistema_licitacao
# Importar: banco/sistema_licitacao.sql

# Configurar URL base
# Editar config/config.php:
define('BASE_URL', 'http://localhost/sistema_licitacao/public/');

# Configurar banco
# Editar config/database.php:
$user = 'root';    # Usuário XAMPP
$pass = '';        # Senha vazia (padrão)
```

### Manutenção
```bash
# Backup do banco
mysqldump -u root -p sistema_licitacao > backup.sql

# Logs do Apache XAMPP
# Windows: C:\xampp\apache\logs\error.log
# Linux: /opt/lampp/logs/error_log
```

## Observações de Desenvolvimento

### Padrões de Código
- **PSR-4**: Autoload de classes
- **Camelcase**: Métodos e variáveis
- **Snake_case**: Campos de banco
- **Documentação**: PHPDoc em métodos principais

### Estrutura de Dados
- **UTF-8**: Encoding padrão
- **Timestamps**: Controle de criação/atualização
- **Soft Delete**: Possível implementação futura

### Extensibilidade
- **Modular**: Fácil adição de novos módulos
- **API-Ready**: Estrutura preparada para APIs REST
- **Integração**: Preparado para integrações externas

---

**Sistema desenvolvido para o Ministério da Saúde**
**Versão**: 1.0 (Junho 2025)
**Ambiente**: Desenvolvimento/Produção