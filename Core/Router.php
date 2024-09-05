<?php

namespace Core;

use Core\Session;

class Router
{
    private bool $loggedIn;
    private array $routes = [];
    // Admin rute koje zahtijevaju da je korsinik logiran
    private array $adminRoutes = [
        'dashboard',
        'formats',
        'genres',
        'members',
        'movies',
        'partials',
        'prices',
        'rentals',
    ];

    public function add(string $uri, array $action, string $method)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $action[0],
            'function' => $action[1]
        ];
    }

    public function get(string $uri, array $action)
    {
        $this->add($uri, $action, 'GET');
    }

    public function post(string $uri, array $action)
    {
        $this->add($uri, $action, 'POST');
    }

    public function put(string $uri, array $action)
    {
        $this->add($uri, $action, 'PUT');
    }

    public function patch(string $uri, array $action)
    {
        $this->add($uri, $action, 'PATCH');
    }

    public function delete(string $uri, array $action)
    {
        $this->add($uri, $action, 'DELETE');
    }

    public function route(string $uri, string $method, string $subDir)
    {

         // Check if the requested URI is for a static file
        $pathInfo = pathinfo($uri);

        // If the request is for a file with a valid extension (e.g., .js, .css, .png, etc.), serve the file directly
        if (isset($pathInfo['extension']) && in_array($pathInfo['extension'], ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2'])) {
            // Construct the full path to the asset
            if (!str_contains($uri, "/public")) {$uri = "/public" . $uri; }
            $filePath = __DIR__ . '/..' . $uri;
// dd($uri);
            if (file_exists($filePath)) {
                // Output the file content with correct headers
                $this->serveFile($filePath);
            } else {
                // Return 404 if the file does not exist
                http_response_code(404);
                echo $filePath . " File not found.";
            }
            exit();
        }

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                $this->loggedIn = Session::has('user'); // da li je korisnik logiran
                foreach ($this->adminRoutes as $adminRoute) { // provjera da li je trenutna ruta u admin rutama
                    if (str_contains($uri, $adminRoute)) { // ako je ruta u admin rutama
                        if (!$this->loggedIn): // a korisnik nije logiran
                            redirect(substr_replace($subDir, '', 0, 1) . '/login'); // preusmjeri ga na login
                        exit();
                        endif; 
                        break; 
                    }
                }
                

                $classPath = $route['controller'];
                $function = $route['function'];
                // dd($classPath);
                $controller = new $classPath();
                $controller->$function($subDir);
                exit();
            }
        }
        
       abort();

    }

    private function serveFile(string $filePath)
        {
            // Determine the content type based on the file extension
            $mimeTypes = [
                'js' => 'application/javascript',
                'css' => 'text/css',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2',
            ];

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            if (array_key_exists($extension, $mimeTypes)) {
                header('Content-Type: ' . $mimeTypes[$extension]);
            } else {
                header('Content-Type: application/octet-stream');
            }

            // Serve the file content
            readfile($filePath);
        }
}