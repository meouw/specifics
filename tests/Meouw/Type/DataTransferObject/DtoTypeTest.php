<?php

namespace Meouw\Type\DataTransferObject;

use Meouw\Type\Subject\BooleanTypes;
use Meouw\Type\Subject\EachType;
use Meouw\Type\Subject\FloatTypes;
use Meouw\Type\Subject\IntegerTypes;
use Meouw\Type\Subject\MixedType;

class DtoTypeTest extends \PHPUnit_Framework_TestCase
{

    public function testAcceptNull()
    {
        $dto = new EachType($this->getArrayOfTypes());
        $this->assertNull($dto->null);
    }

    /**
     * @param mixed $notNull
     * @dataProvider notNullProvider
     * @expectedException \InvalidArgumentException
     */
    public function testRejectNotNull($notNull)
    {
        new EachType($this->getArrayOfTypes(array('null' => $notNull)));
    }

    /**
     * Tests that properties
     */
    public function testAcceptInteger()
    {
        $dto = new IntegerTypes(
            array(
                'zero' => 0,
                'zeroString' => '0',
                'trueInteger' => 1,
                'stringInteger' => '2',
                'shortNameInteger' => 3
            )
        );

        $this->assertEquals(0, $dto->zero);
        $this->assertEquals(0, $dto->zeroString);
        $this->assertEquals(1, $dto->trueInteger);
        $this->assertEquals(2, $dto->stringInteger);
        $this->assertEquals(3, $dto->shortNameInteger);

    }

    /**
     * @param $nonInteger
     *
     * @dataProvider nonIntegerProvider
     * @expectedException \InvalidArgumentException
     */
    public function testRejectNonInteger($nonInteger)
    {
        new EachType($this->getArrayOfTypes(array('integer' => $nonInteger)));
    }

    public function testAcceptFloat()
    {
        $dto = new FloatTypes(
            array(
                'trueFloat' => 1.1,
                'stringFloat' => '2.2',
                'integer' => 4,
                'stringInteger' => 5
            )
        );

        $this->assertEquals(1.1, $dto->trueFloat);
        $this->assertEquals(2.2, $dto->stringFloat);
        $this->assertEquals(4, $dto->integer);
        $this->assertEquals(5, $dto->stringInteger);
    }

    /**
     * @param $nonFloat
     *
     * @dataProvider nonFloatProvider
     * @expectedException \InvalidArgumentException
     */
    public function testRejectNonFloat($nonFloat)
    {
        new EachType($this->getArrayOfTypes(array('float' => $nonFloat)));
    }

    public function testAcceptBoolean()
    {
        $dto = new BooleanTypes(
            array(
                'true' => true,
                'false' => false,
                'shortName' => true
            )
        );

        $this->assertTrue($dto->true);
        $this->assertFalse($dto->false);
        $this->assertTrue($dto->shortName);
    }

    /**
     * @param $nonBoolean
     *
     * @dataProvider nonBooleanProvider
     * @expectedException \InvalidArgumentException
     */
    public function testRejectNonBoolean($nonBoolean)
    {
        new EachType($this->getArrayOfTypes(array('boolean' => $nonBoolean)));
    }

    /**
     * @param $nonArray
     *
     * @dataProvider nonArrayProvider
     * @expectedException \InvalidArgumentException
     */
    public function testRejectNonArray($nonArray)
    {
        new EachType($this->getArrayOfTypes(array('array' => $nonArray)));
    }

    /**
     * @param $nonObject
     *
     * @dataProvider nonObjectProvider
     * @expectedException \InvalidArgumentException
     */
    public function testRejectNonObject($nonObject)
    {
        new EachType($this->getArrayOfTypes(array('object' => $nonObject)));
    }

    /**
     * @param $nonDateTime
     *
     * @dataProvider nonDateTimeProvider
     * @expectedException \InvalidArgumentException
     */
    public function testRejectNonInstance($nonDateTime)
    {
        new EachType($this->getArrayOfTypes(array('datetime' => $nonDateTime)));
    }

    /**
     * @param $rightType
     *
     * @dataProvider mixedRightProvider
     */
    public function testAcceptConstrainedMixed($rightType)
    {
        $dto = new MixedType(array('constrainedMixed' => $rightType, 'mixed' => 1));
        $this->assertEquals($rightType, $dto->constrainedMixed);
    }

    /**
     * @param $wrongType
     *
     * @dataProvider mixedWrongProvider
     * @expectedException \InvalidArgumentException
     */
    public function testRejectConstrainedMixed($wrongType)
    {
        new MixedType(array('constrainedMixed' => $wrongType, 'mixed' => 1));
    }

    /**
     * @param $anyValue
     * @dataProvider notNullProvider
     */
    public function testAcceptMixed($anyValue)
    {
        $dto = new MixedType(array('constrainedMixed' => null, 'mixed' => $anyValue));
        $this->assertSame($anyValue, $dto->mixed);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRejectMixed()
    {
        new MixedType(array('constrainedMixed' => null, 'mixed' => null));
    }

    protected function getArrayOfTypes(array $wrongTypes = array())
    {
        $types = array(
            'null'     =>null,
            'integer'  => 1,
            'float'    => 1.1,
            'string'   => 'string',
            'boolean'  => true,
            'array'    => array(),
            'object'   => (object) array(),
            'datetime' => new \DateTime()
        );

        return array_merge($types, $wrongTypes);
    }

    public function getArrayOfTypesInArray(array $withoutTypes = array())
    {
        $types = $this->getArrayOfTypes();
        $types = array_map(
            function($v) {
                return array($v);
            },
            $types
        );
        foreach ($withoutTypes as $doomed) {
            unset($types[$doomed]);
        }

        return $types;
    }

    ##################
    # Data Providers #
    ##################
    public function notNullProvider()
    {
        return $this->getArrayOfTypesInArray(array('null'));
    }

    public function nonIntegerProvider()
    {
        return $this->getArrayOfTypesInArray(array('integer'));
    }

    public function nonFloatProvider()
    {
        return $this->getArrayOfTypesInArray(array('integer', 'float'));
    }

    public function nonBooleanProvider()
    {
        return $this->getArrayOfTypesInArray(array('boolean'));
    }

    public function nonArrayProvider()
    {
        return $this->getArrayOfTypesInArray(array('array'));
    }

    public function nonObjectProvider()
    {
        return $this->getArrayOfTypesInArray(array('object', 'datetime'));
    }

    public function nonDateTimeProvider()
    {
        return $this->getArrayOfTypesInArray(array('datetime'));
    }

    public function mixedRightProvider()
    {
        return array(
            'integer' => array(1),
            'string'  => array('string'),
            'null'    => array(null),
        );
    }

    public function mixedWrongProvider()
    {
        return $this->getArrayOfTypesInArray(array('string', 'integer', 'null'));
    }
}
 