<?php

declare(strict_types=1);

namespace App\Task3\Kernel;

use App\Task3\Entity\Route;
use App\Task3\Kernel\Core\ControllerInterface;
use App\Task3\Kernel\Core\Exception\NotFoundException;
use App\Task3\Kernel\Core\ServerRequest;
use App\Task3\Kernel\Core\Response;
use Throwable;

class Kernel
{
    /** @var Route[] */
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
    public function run(?ServerRequest $request = null): void
    {
        if (null === $request) {
            $request = new ServerRequest();
        }

        $response = $this->handle($request);
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
        $this->routes[Route::buildName($path)] = new Route($path, $className);
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
        try {
            $handler = $this->getController($request);
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->processException($e, $request);
        }
    }

    /**
     * @param ServerRequest $request
     * @return ControllerInterface
     * @throws NotFoundException
     */
    private function getController(ServerRequest $request): ControllerInterface
    {
        $routeName = Route::buildName($request->getUri());

        if (!key_exists($routeName, $this->routes)) {
            throw new NotFoundException();
        }
        $className = $this->routes[$routeName]->getController();
        return new $className();
    }

    /**
     * Render page
     *
     * @param Response $response
     */
    private function view(Response $response): void
    {
        print_r($response->getContent());
    }

    /**
     * Handle error
     *
     * @param Throwable $e
     * @param ServerRequest $request
     * @return Response
     */
    private function processException(Throwable $e, ServerRequest $request): Response
    {
        if ($e instanceof NotFoundException) {
            return new Response(
                Response::$statusTexts[Response::HTTP_NOT_FOUND],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
