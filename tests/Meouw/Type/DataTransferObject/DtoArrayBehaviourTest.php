<?php

namespace Meouw\Type\DataTransferObject;

use Meouw\Type\Subject\SimpleA;
use Meouw\Type\Subject\ThreeProps;


class DtoArrayBehaviourTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAccess()
    {
        $dto = new SimpleA(array('prop' => 1));
        $this->assertEquals(1, $dto['prop']);
    }

    public function testTraverse()
    {
        $props = array(
           'p1' => 'One',
           'p2' => 'Two',
           'p3' => 'Three',
        );

        $dto = new ThreeProps($props);

        foreach ($props as $prop => $value) {
            $this->assertEquals($value, $dto[$prop]);
        }
    }

    public function testIsset()
    {
        $dto = new SimpleA(array('prop' => 1));
        $this->assertTrue(isset($dto['prop']));
        $this->assertFalse(isset($dto['bogus']));
    }

    /**
     * Unfortunately, a DataTransferObject cannot pass the is_array test or array type hint
     * It is therefore a risky replacement as a return value that is currently an array
     */
    public function testIsArray()
    {
        $dto = new SimpleA(array('prop' => 2));
        $this->assertNotInternalType('array', $dto);
    }

}
 