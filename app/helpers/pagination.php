<?php

class Pagination
{
    private int $totalItens;
    private int $porPagina;
    private int $paginaAtual;
    private int $totalPaginas;
    private string $baseUrl;

    public function __construct(int $totalItens, int $porPagina = 15, int $paginaAtual = 1, string $baseUrl = '')
    {
        $this->totalItens = $totalItens;
        $this->porPagina = $porPagina;
        $this->paginaAtual = max(1, $paginaAtual);
        $this->totalPaginas = (int) ceil($totalItens / $porPagina);
        $this->baseUrl = $baseUrl;
    }

    public function getTotalPaginas(): int
    {
        return $this->totalPaginas;
    }

    public function getPaginaAtual(): int
    {
        return $this->paginaAtual;
    }

    public function getTotalItens(): int
    {
        return $this->totalItens;
    }

    public function getPorPagina(): int
    {
        return $this->porPagina;
    }

    public function temPaginaAnterior(): bool
    {
        return $this->paginaAtual > 1;
    }

    public function temProximaPagina(): bool
    {
        return $this->paginaAtual < $this->totalPaginas;
    }

    public function getPaginaAnterior(): int
    {
        return max(1, $this->paginaAtual - 1);
    }

    public function getProximaPagina(): int
    {
        return min($this->totalPaginas, $this->paginaAtual + 1);
    }

    public function getItemInicio(): int
    {
        if ($this->totalItens === 0) return 0;
        return (($this->paginaAtual - 1) * $this->porPagina) + 1;
    }

    public function getItemFim(): int
    {
        return min($this->totalItens, $this->paginaAtual * $this->porPagina);
    }

    public function getPaginasVisiveis(int $alcance = 2): array
    {
        $inicio = max(1, $this->paginaAtual - $alcance);
        $fim = min($this->totalPaginas, $this->paginaAtual + $alcance);
        
        return range($inicio, $fim);
    }

    public function getUrl(int $pagina): string
    {
        $separator = strpos($this->baseUrl, '?') !== false ? '&' : '?';
        return $this->baseUrl . $separator . 'pagina=' . $pagina;
    }

    public function renderizar(): string
    {
        if ($this->totalPaginas <= 1) {
            return '';
        }

        $html = '<nav aria-label="Navegação da página">';
        $html .= '<ul class="pagination justify-content-center">';

        // Informações da página
        $html .= '<li class="page-item disabled">';
        $html .= '<span class="page-link">Exibindo ' . $this->getItemInicio() . '-' . $this->getItemFim() . ' de ' . $this->totalItens . '</span>';
        $html .= '</li>';

        // Primeira página
        if ($this->paginaAtual > 3) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getUrl(1) . '">1</a>';
            $html .= '</li>';
            if ($this->paginaAtual > 4) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        // Páginas anteriores
        if ($this->temPaginaAnterior()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getUrl($this->getPaginaAnterior()) . '">';
            $html .= '<i class="bi bi-chevron-left"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }

        // Páginas visíveis
        foreach ($this->getPaginasVisiveis() as $pagina) {
            $ativo = $pagina === $this->paginaAtual ? 'active' : '';
            $html .= '<li class="page-item ' . $ativo . '">';
            $html .= '<a class="page-link" href="' . $this->getUrl($pagina) . '">' . $pagina . '</a>';
            $html .= '</li>';
        }

        // Próximas páginas
        if ($this->temProximaPagina()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getUrl($this->getProximaPagina()) . '">';
            $html .= '<i class="bi bi-chevron-right"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }

        // Última página
        if ($this->paginaAtual < $this->totalPaginas - 2) {
            if ($this->paginaAtual < $this->totalPaginas - 3) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getUrl($this->totalPaginas) . '">' . $this->totalPaginas . '</a>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</nav>';

        return $html;
    }
}