<?php
declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\Collection;

interface ApiRequestInterface {

    public function rules(): Collection;

}