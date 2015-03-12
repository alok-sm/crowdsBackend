<?php

namespace spec\App;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DomainSpec extends EloquentModelBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Domain');
    }
}
