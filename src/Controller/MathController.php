<?php
declare(strict_types=1);

namespace App\Controller;

use App\Request\MathDivideRequest;
use App\Services\MathService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MathController extends AbstractController {

    /**
     * @var MathService
     */
    private $math;

    /**
     * Конструктор
     *
     * @param MathService $math
     */
    public function __construct(MathService $math)
    {
        $this->math = $math;
    }

    /**
     * Деление одного числа на другое
     *
     * @param MathDivideRequest $request
     * @return Response
     */
    public function divideAction(MathDivideRequest $request): Response
    {
        $request = $request->getRequest();
        $result  = $this->math->divide(
            (float) $request->get('divisible'),
            (float) $request->get('divisor')
        );

        return $this->json([
            'status' => 'success',
            'result' => $result,
        ]);
    }

}

