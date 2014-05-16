<?php

namespace Meouw\Enum\Test;


class EnumTest extends \PHPUnit_Framework_TestCase
{
	private $list = array(
		'CLUBS' => 'clubs',
		'DIAMONDS' => 'diamonds',
		'HEARTS' => 'hearts',
		'SPADES' => 'spades'
	);

	public function testConstruct()
	{
		$enum = new Suit(Suit::CLUBS);
		$this->assertInstanceOf('Meouw\Enum', $enum);
		$this->assertInstanceOf('Meouw\Enum\Test\Suit', $enum);
	}

	public function testConstruct_InvalidValue_ThrowsException()
	{
		$this->setExpectedException('UnexpectedValueException');
		new Suit('bogus');
	}

	public function testGetValue()
	{
		$enum = new Suit(Suit::CLUBS);
		$this->assertEquals('clubs', $enum->getValue());
	}

	public function testGetKey()
	{
		$enum = new Suit(Suit::CLUBS);
		$this->assertEquals('CLUBS', $enum->getKey());
	}

	public function testGetList_AsStrings()
	{
		$list = Suit::getList();
		$this->assertEquals(
			$this->list,
			$list
		);
	}

	public function testGetList_AsObjects()
	{
		$list = Suit::getList(true);

		foreach ($this->list as $key => $value) {
			$this->assertArrayHasKey($key, $list);
			$this->assertInstanceOf('Meouw\Enum\Test\Suit', $list[$key]);
			$this->assertEquals($value, $list[$key]->getValue());
		}
	}

	public function testHas()
	{
		foreach ($this->list as $value) {
			$this->assertTrue(Suit::has($value));
		}
		$this->assertFalse(Suit::has('bogus'));
	}
}
 