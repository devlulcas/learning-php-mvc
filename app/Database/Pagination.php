<?php

namespace App\Database;

class Pagination
{
  // Devido a atualização para o PHP 7.4 estaremos implementando a tipagem nativa

  /**
   * Limite de registros por página
   */
  private int $limit;

  /**
   * Quantidade total de resultados encontrados no banco
   */
  private int $countResults;

  /**
   * Quantidade de páginas 
   */
  private int $pages;

  /**
   * Página atual
   */
  private int $currentPage;


  public function __construct(int $countResults, int $currentPage = 1, int $limit = 10)
  {
    $this->countResults = $countResults;
    $this->limit = $limit;
    $this->currentPage = $this->setCurrentPage($currentPage);
    $this->calculatePages();
  }


  private function setCurrentPage(int $page = 1): int
  {
    if ($page <= 0) return 1;
    return $page;
  }

  private function calculatePages()
  {
    /**
     * Se houver algum resultado vindo do banco, então determinaremos
     * quantas páginas teremos para disponibilizar dado um determinado
     * limite de resultados por página
     */
    if ($this->countResults) {
      $this->pages = $this->countResults / $this->limit;
      return;
    }
    $this->pages = 1;
  }
}
