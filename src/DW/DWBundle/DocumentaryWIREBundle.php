<?php

namespace DW\DWBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DocumentaryWIREBundle extends Bundle
{
    public function getParent()
    {
        return 'HWIOAuthBundle';
    }
}
