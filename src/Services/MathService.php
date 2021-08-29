<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Сервис для математических операций
 *
 * @package App\Services
 */
class MathService {

    /**
     * Деление одного аргумента на другой
     *
     * @param float $divisible
     * @param float $divisor
     * @return float|int
     */
    public function divide(float $divisible, float $divisor)
    {
        return $divisible / $divisor;
    }

}