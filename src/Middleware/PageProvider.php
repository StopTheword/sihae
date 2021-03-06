<?php declare(strict_types=1);

namespace Sihae\Middleware;

use Sihae\Renderer;
use Sihae\Entities\Post;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Provides the list of all pages to templates
 */
class PageProvider implements MiddlewareInterface
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Renderer $renderer
     * @param EntityManager $entityManager
     */
    public function __construct(Renderer $renderer, EntityManager $entityManager)
    {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $pages = $this->entityManager->getRepository(Post::class)->findBy(['is_page' => true]);

        $this->renderer->addData(['pages' => $pages]);

        return $handler->handle($request);
    }
}
