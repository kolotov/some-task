<?php

declare(strict_types=1);

namespace App\Task3\Kernel;

use App\Task3\Entity\Route;
use App\Task3\Kernel\Core\ServerRequest;
use App\Task3\Kernel\Core\Response;

class Kernel
{
    /** @var Route[]  */
    private array $routes;

    private function __construct()
    {
    }

    /** Build app */
    public static function create(): Kernel
    {
        $kernel = new self();
        (require __DIR__ . '/../routes.php')($kernel);
        return $kernel;
    }

    /** Run app */
    public function run(): void
    {
        $response = $this->handle(new ServerRequest());
        $this->view($response);
    }

    /**
     * Add route
     *
     * @param string $path
     * @param string $className
     * @return Kernel
     */
    public function route(string $path, string $className): Kernel
    {
        $this->routes[] = new Route($path, $className);
        return $this;
    }

    /**
     * Handle request
     *
     * @param ServerRequest $request
     * @return Response
     */
    private function handle(ServerRequest $request): Response
    {
        // TODO: handle request
        return new Response();
    }

    /**
     * Render page
     *
     * @param Response $response
     */
    private function view(Response $response): void
    {
        // TODO: render from template
        print_r($response);
    }
}
