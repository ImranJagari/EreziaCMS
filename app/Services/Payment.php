<?php

namespace App\Services;

abstract class Payment
{
    abstract protected function rates();

    abstract protected function palier($id);

    abstract protected function check($palier, $code);
}
