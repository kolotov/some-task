<?php

declare(strict_types=1);

namespace App\Task3;

use App\Task3\Entity\Route;
use App\Task3\Http\JsonResponse;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Exception\NotFoundException;
use App\Task3\Http\ServerRequest;
use App\Task3\Http\Response;
use App\Task3\Interfaces\ResponseInterface;
use JsonException;
use Throwable;

/**
 * Kernel framework
 * TODO:: God object. Need should be divided it
 * TODO:: Implement container
 */
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
     * @return ResponseInterface
     */
    private function handle(ServerRequest $request): ResponseInterface
    {
        try {
            $handler = $this->getController($request);
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->processException($e, $request);
        }
    }

    /**
     * Dispatcher route
     *
     * @param ServerRequest $request
     * @return ControllerInterface
     * @throws NotFoundException
     */
    private function getController(ServerRequest $request): ControllerInterface
    {
        $routeName = Route::buildName($request->getUri());
        $method = $request->getMethod();

        if (!isset($this->routes[$routeName][$method])) {
            throw new NotFoundException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
        $className = $this->routes[$routeName][$method]->getController();
        return new $className();
    }

    /**
     * Render page for client
     *
     * @param ResponseInterface $response
     */
    private function view(ResponseInterface $response): void
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
     * @throws JsonException
     */
    private function processException(Throwable $e, ServerRequest $request): ResponseInterface
    {
        $contentType = $request->getHeaders()['Content-Type'] ?? '';
        $mime = explode(';', $contentType)[0];

        $data = ['status' => 'error', 'message' => $e->getMessage()];

        $code = match (get_class($e)) {
            NotFoundException::class => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_INTERNAL_SERVER_ERROR
        };

        return match ($mime) {
            'application/json' => new JsonResponse($data, $code),
            default => new Response($e->getMessage(), $code)
        };
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
