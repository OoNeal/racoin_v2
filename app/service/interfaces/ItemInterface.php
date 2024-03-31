<?php

namespace controller\app\service\interfaces;

interface ItemInterface
{
    public function formatArray(array $array): array;

    public function isEmptyField(array $array): array;

    public function isValidEmail(string $email): bool;
}