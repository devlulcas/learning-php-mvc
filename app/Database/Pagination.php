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
  private int $pagesQuantity;

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
    $resultsPerPage = ceil($this->countResults / $this->limit);
    $defaultResultsPerPage = 1;
    $this->pagesQuantity = $this->countResults > 0 ? $resultsPerPage : $defaultResultsPerPage;
    $this->currentPage = $this->calculateCurrentPage();
  }

  /**
   * Garante que a página atual não seja maior que o total de páginas
   */
  private function calculateCurrentPage()
  {
    if ($this->currentPage <= $this->pagesQuantity) {
      return $this->currentPage;
    }
    return $this->pagesQuantity;
  }

  public function getLimit()
  {
    $previousPageCount = $this->currentPage - 1;
    $offSet = $this->limit * $previousPageCount;
    return "$offSet, $this->limit";
  }
}
