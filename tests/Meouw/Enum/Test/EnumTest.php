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
		$enum = new CardSuits(CardSuits::CLUBS);
		$this->assertInstanceOf('Meouw\Enum', $enum);
		$this->assertInstanceOf('Meouw\Enum\Test\CardSuits', $enum);
	}

	public function testConstruct_InvalidValue_ThrowsException()
	{
		$this->setExpectedException('UnexpectedValueException');
		new CardSuits('bogus');
	}

	public function testGetValue()
	{
		$enum = new CardSuits(CardSuits::CLUBS);
		$this->assertEquals('clubs', $enum->getValue());
	}

	public function testGetKey()
	{
		$enum = new CardSuits(CardSuits::CLUBS);
		$this->assertEquals('CLUBS', $enum->getKey());
	}

	public function testGetList_AsStrings()
	{
		$list = CardSuits::getList();
		$this->assertEquals(
			$this->list,
			$list
		);
	}

	public function testGetList_AsObjects()
	{
		$list = CardSuits::getList(true);

		foreach ($this->list as $key => $value) {
			$this->assertArrayHasKey($key, $list);
			$this->assertInstanceOf('Meouw\Enum\Test\CardSuits', $list[$key]);
			$this->assertEquals($value, $list[$key]->getValue());
		}
	}

	public function testHas()
	{
		foreach ($this->list as $value) {
			$this->assertTrue(CardSuits::has($value));
		}
		$this->assertFalse(CardSuits::has('bogus'));
	}
}
 