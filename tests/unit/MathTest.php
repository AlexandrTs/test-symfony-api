<?php

class MathTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \App\Services\MathService
     */
    protected $math;

    protected function _before()
    {
        $this->math = new \App\Services\MathService();

    }

    /**
     * Проверка деления одного числа на другое
     */
    public function testDivide()
    {
        $tests = [
            [
                'comment' => 'Целые числа',
                'args'    => ['divisible' => 2, 'divisor' => 2],
                'result'  => 1,
            ],
            [
                'comment' => 'С нулем',
                'args'    => ['divisible' => 0, 'divisor' => 2],
                'result'  => 0,
            ],
            [
                'comment' => 'С отрицательным числом',
                'args'    => ['divisible' => -2, 'divisor' => 1],
                'result'  => -2,
            ],
            [
                'comment' => 'Делимое дробное, делитель целое',
                'args'    => ['divisible' => 3.6, 'divisor' => 2],
                'result'  => 1.8,
            ],
            [
                'comment' => 'Делимое и делитель дробное',
                'args'    => ['divisible' => 3.6, 'divisor' => 1.2],
                'result'  => 3,
            ],
        ];

        foreach ($tests as $data) {
            $this->tester->assertEquals(
                $data['result'],
                $this->math->divide($data['args']['divisible'], $data['args']['divisor']),
                $data['comment']
            );
        }

        $math = $this->math;
        $this->tester->expectThrowable(
            \PHPUnit\Framework\Exception::class,
            function() use ($math){
                $math->divide(1, 0);
            }
        );

    }
}