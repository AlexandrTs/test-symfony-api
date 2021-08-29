<?php

class MathCest
{
    public function _before(ApiTester $I)
    {
    }

    /**
     * Проверка деления одного аргумента на другой
     *
     * @param ApiTester $I
     */
    public function divide(\ApiTester $I)
    {
        $I->wantTo('Проверить деление одного аргумента на другой');

        $checks = [
            400 => [
                [],
                ['divisor' => 1, 'divisible' => 1, 'unknown' => 1],
                ['divisor' => 1],
                ['divisible' => 1],
                ['divisible' => 1, 'divisor' => 0],
                ['divisor' => 'NAN'],
                ['divisibled' => 'NAN'],
                ['divisor' => 1, 'divisible' => 'NAN'],
                [
                    'divisor' => str_repeat('99999999999999999999999999999999999999', 100),
                    'divisible' => 1,
                ],
            ],
            200 => [
                [
                    ['divisible' => 10, 'divisor' => 5],
                    ['result' => 2],
                ],
            ]
        ];

        $path = '/math/divide';

        $I->sendPost($path);
        $I->canSeeResponseCodeIs(405);

        foreach ($checks as $code => $variants) {

            foreach($variants as $args) {

                if ($code === 200) {
                    $I->sendGet($path, $args[0]);
                    $I->canSeeResponseContainsJson($args[1]);
                } else {
                    $I->sendGet($path, $args);
                    $I->canSeeResponseCodeIs(400);
                }
            }
        }
    }
}
