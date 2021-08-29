<?php
declare(strict_types=1);

namespace App\Resolver;

use App\Exception\InvalidPayloadException;
use App\Request\ApiRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class RequestObjectResolver implements ArgumentValueResolverInterface, ApiRequestInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var Request
     */
    private $request;

    /**
     * Конструктор
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Проверяем подходит ли запрос
     *
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return is_subclass_of($argument->getType(), RequestObjectResolver::class);
    }

    /**
     * Валидирует запрос и в случае успеха возвращает в контроллер
     * в случае неудачи генерирует исключение
     *
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $className        = $argument->getType();
        $validationObject = new $className($this->validator);

        $this->setRequest($request);

        $payload = $request->query->all();

        if ($request->getMethod() == Request::METHOD_POST) {
            $payload = $request->request->all();
        }

        $this->validate($payload, $validationObject->rules());

        yield $this;
    }

    /**
     * Валидация переданого payload в соответствии со списком constraints
     *
     * @param $payload
     * @param $collection
     */
    private function validate($payload, $collection)
    {
        $errors = $this->validator->validate($payload, $collection);

        if (0 !== count($errors)) {

            $errorsArr = array_map(
                function (ConstraintViolation $violation) {
                    return [
                        'path' => $violation->getPropertyPath(),
                        'message' => $violation->getMessage(),
                    ];
                },
                iterator_to_array($errors)
            );

            throw new InvalidPayloadException(
                [
                    'message' => 'Bad request',
                    'details' => $errorsArr
                ],
                400
            );
        }
    }

    /**
     * Сохраняем оригинальный запрос, пригодится в контроллере
     *
     * @param Request $request
     */
    protected function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Геттер для получения оригинального запроса
     *
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }
}