<?php

abstract class BaseService
{
    protected function repondreErreur(Throwable $error): array
    {
        http_response_code($error instanceof InvalidArgumentException ? 422 : 500);
        return ['erreur' => $error->getMessage()];
    }
}