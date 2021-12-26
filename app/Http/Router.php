<?php

namespace App\Http;

use \Closure;
use \Exception;
use Reflection;
use \ReflectionFunction;

class Router
{
  /**
   * Url completa do projeto
   */
  private string $url = "";

  /**
   * Prefixo de todas as rotas
   */
  private string $prefix = "";

  /**
   * Índice de rotas
   */
  private array $routes = [];

  /** 
   * Instância da classe Request
   */
  private Request $request;

  public function __construct($url)
  {
    $this->request = new Request();
    $this->url = $url;
    $this->setPrefix();
  }

  /**
   * Define o prefixo das rotas, caso exista algum.
   */
  private function setPrefix()
  {
    // Informações da url
    $parseUrl = parse_url($this->url);
    // Define o prefixo ex: /app /mvc /web
    $this->prefix = $parseUrl["path"] ?? "";
  }

  private function addRoute(string $method, string $route, array $params)
  {
    /**
     * Validação dos parâmetros
     */
    foreach ($params as $key => $value) {
      /**
       * Uma função com uma instancia de Request/Response é passada para uma das funções responsáveis por um método
       * http, essa função é uma closure. Aqui verificamos se realmente estamos recebendo uma closure, caso seja verdadeiro
       * nós trocamos o lugar da variável no array que possui um indice numérico por um indice de string 'controller'. 
       */
      if ($value instanceof Closure) {
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
      }
    }
    // Variáveis de rota 
    $params['variables'] = [];
    // Validação da variável de rota
    $patternVariable = '/{(.*?)}/';
    if (preg_match_all($patternVariable, $route, $matches)) {
      /**
       * Substitui uma string por essa expressão regular nas rotas então algo como /pagina/10
       * vira algo tipo /(.*?)/(.*?)
       */
      $route = preg_replace($patternVariable, '(.*?)', $route);
      $params['variables'] = $matches[1];
    }

    // Validação do padrão da URL
    $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';
    /**
     * Adiciona a rota dentro da classe.
     * Basicamente uma matriz onde temos um array para cada rota de acordo com o regex de patternRoute.
     * Dentro do array de cada rota temos um array para cada método que bate nesta rota. 
     * Dentro do array de métodos http temos os parametros passados, inclusive o controller responsável pelo 
     * que cai nessa rota (a closure cujo index se chama 'controller')
     *   */
    $this->routes[$patternRoute][$method] = $params;
  }

  /**
   * Método responsável por retornar a URI desconsiderando o prefixo das rotas.
   */
  private function getUri(): string
  {
    // Uri da request
    $uri = $this->request->getUri();
    // Se houver um prefixo nós removemos o prefixo, caso contrário recebemos a uri diretamente em um array
    // (condição) ? retorno caso verdade : retorno caso falso;
    $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
    // Retorna apenas a URI limpa, nada de prefixo ao pegar a última posição do array (1 se houver prefixo e 0 se não houver)
    return end($xUri);
  }

  /**
   * Método responsável por retornar os dados da rota atual
   */
  private function getRoute(): array
  {
    // URI
    $uri = $this->getUri();
    // METHOD
    $httpMethod = $this->request->getHttpMethod();
    // Válida as rotas
    foreach ($this->routes as $patternRoute => $methods) {
      // Verifica se a rota bate com o padrão regex
      if (preg_match($patternRoute, $uri, $matches)) {
        // Sabendo que a URL bateu, verificamos se temos um método para isso
        if (isset($methods[$httpMethod])) {
          // Removemos a primeira posição que entregar a url completa
          unset($matches[0]);
          // Variáveis processadas
          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;
          return $methods[$httpMethod];
        }
        // Caso não exista o método requisitado 
        throw new Exception("Método não permitido", 405);
      }
    }
    // Caso a URL não seja identificada retornamos um erro 404
    throw new Exception("URL não encontrada", 404);
  }


  /**
   * Método responsável por executar a rota atual
   */
  public function run(): Response
  {
    try {
      // Obtém a rota atual
      $route = $this->getRoute();
      // Verifica a inexistência de um controlador
      if (!isset($route['controller'])) {
        throw new Exception("A URL não pode ser processada", 500);
      }
      /**
       * Caso exista um controlador nós podemos chamá-lo para obter a resposta esperada.
       * Podemos fazer isso usando o método nativo do PHP chamado call_user_func_array.
       * Este método recebe uma função de callback (basicamente uma função que sera chamada por está função, que
       * no nosso caso é algo tipo "Home::getHome()") e um array de argumentos que serão passados para esta função
       * de callback.
       */
      $args = [];

      // Reflection function
      $reflection = new ReflectionFunction($route['controller']);

      foreach ($reflection->getParameters() as $parameter) {
        $name = $parameter->getName();
        $args[$name] = $route['variables'][$name] ?? "";
      }

      return call_user_func_array($route['controller'], $args);
    } catch (Exception $error) {
      // Retorna o erro lançado no try acima
      return new Response($error->getCode(), $error->getMessage());
    }
  }

  // HTTP METHODS

  public function get(string $route, array $params = [])
  {
    return $this->addRoute('GET', $route, $params);
  }

  public function post(string $route, array $params = [])
  {
    return $this->addRoute('POST', $route, $params);
  }


  public function put(string $route, array $params = [])
  {
    return $this->addRoute('PUT', $route, $params);
  }


  public function delete(string $route, array $params = [])
  {
    return $this->addRoute('DELETE', $route, $params);
  }
}
