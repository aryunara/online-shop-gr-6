<?php

namespace Controller;

use Core\ViewRenderer;
use Service\Authentication\AuthenticationServiceInterface;

abstract class MainController
{
    protected AuthenticationServiceInterface $authenticationService;
    protected ViewRenderer $viewRenderer;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $viewRenderer)
    {
        $this->authenticationService = $authenticationService;
        $this->viewRenderer = $viewRenderer;
    }
}