<?php
declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * Обработка исключения
     *
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response  = new JsonResponse($this->buildResponse($exception));

        if ($exception->getCode()) {
            $response->setStatusCode($exception->getCode());
        }

        $event->setResponse($response);
    }

    /**
     * Получаем список событий на которые подписываемся
     *
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    /**
     * Подготавливаем респонс
     *
     * @param $exception
     * @return string[]
     */
    private function buildResponse($exception): array
    {
        $out     = ['status' => 'error'];
        $message = json_decode($exception->getMessage(), true);

        if (is_array($message)) {

            if (!isset($message['message'])) {
                $out['message'] = 'Bad request';
            }

            $out = array_merge($out, $message);
        } else {

            $out['message'] = $exception->getMessage();
        }

        return $out;
    }
}