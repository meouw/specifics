<?php

namespace Meouw\Type;

use Meouw\Type\Subject\Suit;

class EnumTest extends \PHPUnit_Framework_TestCase
{
	private $list = array(
		'CLUBS' => 'clubs',
		'DIAMONDS' => 'diamonds',
		'HEARTS' => 'hearts',
		'SPADES' => 'spades'
	);

    /**
     * Tests that an Enum can be constructed
     */
	public function testConstruct()
	{
		$enum = new Suit(Suit::CLUBS);
		$this->assertInstanceOf('Meouw\Type\Enum', $enum);
		$this->assertInstanceOf('Meouw\Type\Subject\Suit', $enum);
	}

    /**
     * Tests that an Enum can only be constructed with one of its allowed values
     *
     * @expectedException \UnexpectedValueException
     */
	public function testConstructWithInvalidValueThrowsException()
	{
		new Suit('bogus');
	}

    /**
     * Tests that the value of an Enum can be retrieved
     */
	public function testGetValue()
	{
		$enum = new Suit(Suit::CLUBS);
		$this->assertEquals('clubs', $enum->getValue());
	}

    /**
     * Tests that the name of the constant on the Enum class can be retrieved
     */
	public function testGetKey()
	{
		$enum = new Suit(Suit::CLUBS);
		$this->assertEquals('CLUBS', $enum->getKey());
	}

    /**
     * Tests that an array of all allowable values can be retrieved as strings
     */
	public function testGetListAsStrings()
	{
		$list = Suit::getList();
		$this->assertEquals(
			$this->list,
			$list
		);
	}

    /**
     * Tests that an array of all allowable values can be retrieved as Enum objects
     */
	public function testGetListAsObjects()
	{
		$list = Suit::getList(true);

		foreach ($this->list as $key => $value) {
			$this->assertArrayHasKey($key, $list);
			$this->assertInstanceOf('Meouw\Type\Subject\Suit', $list[$key]);
			$this->assertEquals($value, $list[$key]->getValue());
		}
	}

    /**
     * Tests that it is possible to check whether a value is allowable for an enum
     */
	public function testHas()
	{
		foreach ($this->list as $value) {
			$this->assertTrue(Suit::has($value));
		}
		$this->assertFalse(Suit::has('bogus'));
	}
}
 