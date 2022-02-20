<?php

declare(strict_types=1);

namespace App\Task3;

use App\Task3\Entity\Route;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Exception\NotFoundException;
use App\Task3\Http\ServerRequest;
use App\Task3\Http\Response;
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
        (require $kernel::getAppDir() . 'Config/routes.php')($kernel);
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
     * @param string $method
     * @param string $className
     * @return Kernel
     */
    public function route(string $path, string $method, string $className): Kernel
    {
        $this->routes[Route::buildName($path)][$method] = new Route($path, $className);
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
      //  try {
            $handler = $this->getController($request);
            return $handler->handle($request);
       // } catch (Throwable $e) {
            return $this->processException($e, $request);
      //  }
    }

    /**
     * @param ServerRequest $request
     * @return ControllerInterface
     * @throws NotFoundException
     */
    private function getController(ServerRequest $request): ControllerInterface
    {
        $routeName = Route::buildName($request->getUri());
        $method = $request->getMethod();

        if (!isset($this->routes[$routeName][$method])) {
            throw new NotFoundException();
        }
        $className = $this->routes[$routeName][$method]->getController();
        return new $className();
    }

    /**
     * Render page for client
     *
     * @param Response $response
     */
    private function view(Response $response): void
    {
        $headers = $response->getHeaders();
        array_walk(
            $headers,
            static function (string $header, string $key) {
                if (is_numeric($key)) {
                    header($header);
                } else {
                    header("$key: $header");
                }
            }
        );
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

        return new Response(
            Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Project root path
     * @return string
     */
    public static function getAppDir(): string
    {
        return __DIR__ . '/';
    }
}
