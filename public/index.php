<?php
/**
 * Front Controller / Roteador Principal
 * Localização: /public/index.php
 * 
 * Este arquivo é o ponto de entrada único de todas as requisições.
 * O .htaccess encaminha requisições não existentes para cá.
 * Aqui parseamos a URL e encaminhamos para o controller/método apropriado.
 */

// 1. Carrega o autoloader
require_once __DIR__ . '/../app/Core/autoload.php';

// 2. Define a base URL da aplicação (ajuste conforme seu ambiente)
$baseUrl = '/homeCare/lab-desenvolvimento-software-Thomaz/public';

// 3. Extrai a rota da URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = str_replace($baseUrl, '', $requestUri);
$route = trim($route, '/');

// Se route vazia, usa rota padrão
if (empty($route)) {
    throw new Exception("Nenhuma rota foi enviada");
}

// 4. Separa controller e método (padrão: /controller/metodo/param1/param2)
$segments = explode('/', $route);
$controllerName = $segments[0] ?? 'home';
$methodName = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

// 5. Converte nome do controller (ex: 'clientes' -> 'ClientesController')
$controllerClassName = ucfirst(strtolower($controllerName)) . 'Controller';

// 6. Tenta encontrar a classe do controller
// O autoload procura em: app/Controllers, app/Models, app/Core
try {
    if (!class_exists($controllerClassName)) {
        throw new Exception("Controller não encontrado: {$controllerClassName}");
    }

    // Instancia o controller
    $controller = new $controllerClassName();

    // Verifica se o método existe
    if (!method_exists($controller, $methodName)) {
        throw new Exception("Método não encontrado: {$methodName}");
    }

    // Verifica se o método não é "mágico" ou protegido
    $reflection = new ReflectionMethod($controller, $methodName);
    if ($reflection->isPrivate() || strpos($methodName, '__') === 0) {
        throw new Exception("Acesso negado ao método: {$methodName}");
    }

    // 7. Chama o método com os parâmetros
    call_user_func_array([$controller, $methodName], $params);

} catch (Exception $e) {
    // Tratamento de erros
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>