<?php
declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Класс ошибки валидации переданных параметров
 *
 * @package App\Exception
 */
class InvalidPayloadException extends Exception implements ApiExceptionInterface {

    public function __construct($messages = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct(json_encode($messages), $code, $previous);
    }

}