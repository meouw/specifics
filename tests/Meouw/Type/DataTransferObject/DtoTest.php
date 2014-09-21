<?php

namespace Meouw\Type\DataTransferObject;

use Meouw\Type\Subject\SimpleA;
use Meouw\Type\Subject\SimpleB;

class DtoTest extends \PHPUnit_Framework_TestCase
{
    public function testAllPropertiesRequired()
    {
        $this->setExpectedException('InvalidArgumentException', 'Not all expected keys present');
        new SimpleA(array());
    }
    
    public function testNonExistentPropertyInaccessible()
    {
        $dto = new SimpleA(array('prop' => 1));

        $this->setExpectedException('LogicException', 'Property `bogus` does not exist or not specified');
        $dto->bogus;
    }

    public function testUnspecifiedPropertyInaccessible()
    {
        $p = new SimpleA(
            array(
                'prop'  => 1,
                'bogus' => 2,
            )
        );

        $this->setExpectedException('LogicException', 'Property `bogus` does not exist or not specified');
        $p->bogus;
    }

    /**
     * Both DataTransferObject's share a property name but differ in type
     * This should not be a problem
     */
    public function testPropertiesDontClash()
    {
        $a  = new SimpleA(array('prop' => 1));
        $b  = new SimpleB(array('prop' => 'string'));

        $this->assertEquals(1, $a->prop);
        $this->assertEquals('string', $b->prop);
    }

    /**
     * @expectedException \LogicException
     */
    public function testIsImmutableObject()
    {
        $dto = new SimpleA(array('prop' => 1));
        $dto->prop = 2;
    }

    /**
     * @expectedException \LogicException
     */
    public function testIsImmutableArray()
    {
        $dto = new SimpleA(array('prop' => 1));
        $dto['prop'] = 2;
    }

    /**
     * @expectedException \LogicException
     */
    public function testIsImmutableUnsetObject()
    {
        $dto = new SimpleA(array('prop' => 1));
        unset($dto->prop);
    }

    /**
     * @expectedException \LogicException
     */
    public function testIsImmutableUnsetArray()
    {
        $dto = new SimpleA(array('prop' => 1));
        unset($dto['prop']);
    }
}
 