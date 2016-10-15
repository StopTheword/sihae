<?php

namespace Sihae\Controllers;

use RKA\Session;
use Sihae\Renderer;
use Sihae\Entities\User;
use Slim\Flash\Messages;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LoginController
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
     * @var Messages
     */
    private $flash;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param Renderer $renderer
     * @param EntityManager $entityManager
     * @param Messages $flash
     * @param Session $session
     */
    public function __construct(
        Renderer $renderer,
        EntityManager $entityManager,
        Messages $flash,
        Session $session
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
        $this->flash = $flash;
        $this->session = $session;
    }

    /**
     * Log a user in
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function login(Request $request, Response $response) : Response
    {
        $userDetails = $request->getParsedBody();

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['username' => $userDetails['username']]);

        if (!$user || !password_verify($userDetails['password'], $user->getPassword())) {
            return $this->renderer->render($response, 'login', [
                'errors' => ['No user was found with these credentials, please try again'],
                'username' => $userDetails['username'],
            ]);
        }

        $this->session->set('username', $user->getUsername());

        $this->flash->addMessage('success', 'Welcome back ' . $user->getUsername());

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Log the current user out
     *
     * This can't be tested due to the static Session::destroy call
     * @codeCoverageIgnore
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function logout(Request $request, Response $response) : Response
    {
        Session::destroy();

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Show the login form
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function showForm(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'login');
    }
}