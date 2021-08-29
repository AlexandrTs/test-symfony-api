<?php
declare(strict_types=1);

namespace App\Request;

use App\Resolver\RequestObjectResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Класс для валидации запроса апи на деление одного числа на другое
 *
 * @package App\Request
 */
class MathDivideRequest extends RequestObjectResolver
{
    /**
     * Возвращает список constraint для валидации параметров запроса
     *
     * @return Assert\Collection
     */
    public function rules(): Assert\Collection
    {
        $constraints = [
            new Assert\Type([
                'type' => 'numeric',
                'message' => 'Value must be valid integer of float',
            ]),
            new Assert\Callback(function($value, ExecutionContextInterface $context){
                $check = $value + 0;

                if ((is_float($check) || is_int($check)) && is_infinite($check)) {
                    $context->buildViolation('Value is too big but must be valid integer of float')
                        ->addViolation();
                }
            }),
        ];

        return new Assert\Collection([
            'divisible' => new Assert\Sequentially($constraints),
            'divisor'   => new Assert\Sequentially(array_merge(
                $constraints,
                [
                    new Assert\NotEqualTo([
                        'value' => 0,
                        'message' => 'Divisor cannot be zero',
                    ])
                ]
            ))
        ]);
    }
}