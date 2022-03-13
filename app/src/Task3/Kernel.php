<?php

declare(strict_types=1);

namespace App\Task3;

use App\Task3\Entity\Route;
use App\Task3\Http\JsonResponse;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Exception\NotFoundException;
use App\Task3\Http\ServerRequest;
use App\Task3\Http\Response;
use App\Task3\Interfaces\ExceptionInterface;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Kernel\Container;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Throwable;

/**
 * Kernel framework
 */
class Kernel
{
    /** @var Route[] */
    private array $routes;

    private function __construct(
        private Container $services
    ) {
        $this->load();
    }

    /** Build app */
    public static function create(): Kernel
    {
        return new self(new Container());
    }

    public function load()
    {
        $this->services->set(Container::class, $this->services);
        $this->services->set(ServerRequest::class, new ServerRequest());
        $this->services->set(Kernel::class, $this);
        (require self::getAppDir() . 'Config/routes.php')($this);
        (require self::getAppDir() . 'Config/services.php')($this);
    }

    /** Run app */
    public function run(?ServerRequest $request = null): void
    {
        if (null === $request) {
            $request = new ServerRequest();
        }

        try {
            $response = $this->handle($request);
        } catch (Throwable $e) {
            $response = $this->processException($e, $request);
        }

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
     * Add service
     *
     * @param string $className
     * @return $this
     * @throws ReflectionException
     */
    public function addService(string $className): Kernel
    {
        $this->services->set($className, $this->newInstance($className));
        return $this;
    }

    /**
     * Handle request
     *
     * @param ServerRequest $request
     * @return ResponseInterface
     * @throws NotFoundException
     * @throws ReflectionException
     */
    private function handle(ServerRequest $request): ResponseInterface
    {
        /** @var ControllerInterface $handler */
        $handler = $this->getController($request);
        return $handler->handle($request);
    }

    /**
     * Dispatcher route
     *
     * @param ServerRequest $request
     * @return object
     * @throws NotFoundException
     * @throws ReflectionException
     */
    private function getController(ServerRequest $request): object
    {
        $routeName = Route::buildName($request->getUri());
        $method = $request->getMethod();

        if (!isset($this->routes[$routeName][$method])) {
            throw new NotFoundException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }

        /** @var Route $route */
        $route = $this->routes[$routeName][$method];
        return $this->newInstance($route->getController());
    }

    /**
     * Render page for client
     *
     * @param ResponseInterface $response
     */
    private function view(ResponseInterface $response): void
    {
        $this->setHeaders($response->getHeaders());
        $this->setHeaders($response->getCookies());
        print_r($response->getContent());
    }

    /**
     * Set client headers
     *
     * @param array $headers
     */
    private function setHeaders(array $headers): void
    {
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

        $code = $e instanceof ExceptionInterface
            ? $e->getCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

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

    /**
     * Create a new Instance
     *
     * @throws ReflectionException
     */
    private function newInstance(string $className): object
    {
        $reflection = new ReflectionClass($className);
        $construct = $reflection->getConstructor();

        if (null === $construct || 0 === $construct->getNumberOfParameters()) {
            return $reflection->newInstance();
        }

        $services = $this->getServices($construct);
        return $reflection->newInstanceArgs($services);
    }

    /**
     * Get a services for the constructor
     *
     * @param ReflectionMethod $construct
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getServices(ReflectionMethod $construct): array
    {
        $params = $construct->getParameters();
        return array_reduce(
            $params,
            function (array $services, ReflectionParameter $param) {
                $serviceClass = $param->getType()?->getName();
                if (null === $serviceClass || null === $this->services->get($serviceClass)) {
                    return $services;
                }
                $services[] = $this->services->get($serviceClass);
                return $services;
            },
            []
        );
    }
}
