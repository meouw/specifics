<?php

namespace Meouw\Type\DataTransferObject;

use Meouw\Type\Subject\SimpleA;
use Meouw\Type\Subject\SimpleACollection;
use Meouw\Type\Subject\SimpleBCollection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $collection = new SimpleACollection(
            array(
                array('prop' => 0),
                array('prop' => 1),
                array('prop' => 2),
            )
        );
        $this->assertCount(3, $collection);
    }

    /**
     * @return SimpleACollection
     */
    public function testAddObject()
    {
        $collection = new SimpleACollection();
        $collection->add(new SimpleA(array('prop' => 1)));
        $this->assertCount(1, $collection);
        return $collection;
    }

    /**
     * @param SimpleACollection $collection
     * @depends testAddObject
     * @return SimpleACollection
     */
    public function testAddArray(SimpleACollection $collection)
    {
        $collection->add(array('prop' => 2));
        $this->assertCount(2, $collection);

        return $collection;
    }

    /**
     * @param SimpleACollection $collection
     * @depends testAddArray
     * @return SimpleACollection
     */
    public function testAccess(SimpleACollection $collection)
    {
        $zeroth = $collection->aList[0];
        $first  = $collection->aList[1];

        $this->assertInstanceOf('Meouw\Type\Subject\SimpleA', $zeroth);
        $this->assertEquals(1, $zeroth->prop);
        $this->assertInstanceOf('Meouw\Type\Subject\SimpleA', $first);
        $this->assertEquals(2, $first->prop);

        return $collection;
    }

    /**
     * @param SimpleACollection $collection
     * @depends testAccess
     */
    public function testTraverse(SimpleACollection $collection)
    {
        foreach ($collection as $item) {
            $this->assertInstanceOf('Meouw\Type\Subject\SimpleA', $item);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddWrongTypeObject()
    {
        $collection = new SimpleACollection();
        $collection->add(new \stdClass());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddIncompatibleArray()
    {
        $collection = new SimpleACollection();
        $collection->add(array());
    }

    /**
     * @expectedException \LogicException
     */
    public function testAccessNonExistentProperty()
    {
        $collection = new SimpleACollection();
        $collection->bogus;
    }

    /**
     * @expectedException \LogicException
     */
    public function testForbiddenMemberSet()
    {
        $collection = new SimpleACollection();
        $collection->aList = array();
    }

    public function testMetaDataDoesntClash()
    {
        $aCollection = new SimpleACollection(array(array('prop' => 1)));
        $bCollection = new SimpleBCollection(array(array('prop' => 'string')));

        $this->assertEquals(1, $aCollection->aList[0]->prop);
        $this->assertEquals('string', $bCollection->bList[0]->prop);

    }

}
 