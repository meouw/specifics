<?php
namespace Meouw;

/**
 * Class Enum
 * Base class for creating Enums
 *
 * An Enum is a class which will only allow objects to be created which have a value which is one of a set list
 * e.g.
 *
 * class Suits extends Enum
 * {
 *      const CLUBS    = 'clubs';
 *      const DIAMONDS = 'diamonds';
 *      const HEARTS   = 'hearts';
 *      const SPADES   = 'spades';
 * }
 *
 * $spade = new Suits(Suits::SPADES);
 *
 * Now we can use $spade as an argument to a method that type-hints on Suits
 * That method can rely on $spade->getValue() returning one of the four suits (spades in this case)
 */
abstract class Enum
{
	/** @var array  */
	private static $meta = array();
	/** @var string */
	private $key;
	/** @var string */
	private $value;

	/**
	 * Constructs an Enum ensuring that $value is one of that classes constants
	 *
	 * @param $value
	 *
	 * @throws \UnexpectedValueException
	 */
	final public function __construct($value)
	{
		$class = get_class($this);
		$constants = self::getConstants($class);

		if (!in_array($value, $constants)) {
			throw new \UnexpectedValueException("$value is not an allowable value for $class");
		}
		$this->key   = $class.'::'.array_search($value, $constants);
		$this->value = $value;
	}

	/**
	 * Returns the name of the class constant that this object's value corresponds to
	 *
	 * @return string
	 */
	final public function getKey()
	{
		return $this->key;
	}

	/**
	 * Returns the value of this object
	 *
	 * @return string
	 */
	final public function getValue()
	{
		return $this->value;
	}

	/**
	 * Returns a list of all the allowable values for this Enum
	 * keyed by the name of the constant for that value
	 *
	 * @param bool $asObjects
	 *             if true, the values will be Enums with representing each value
	 *
	 * @return array|Enum[]
	 */
	final public static function getList($asObjects = false)
	{
		$class = get_called_class();
		$constants = self::getConstants($class);

		if ($asObjects) {
			$constants = array_map(
				function($e) use($class) {return new $class($e);},
				$constants
			);
		}

		return $constants;
	}

	/**
	 * Returns true if $value is an allowable value for this enum
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	final public static function has($value)
	{
		return in_array($value, static::getList());
	}

	/**
	 * @param string $class The name of the class to get the constant list for
	 *                      This is cached for efficiency
	 *
	 * @return array
	 */
	private static function getConstants($class)
	{
		if (!isset(self::$meta[$class])) {
			$o = new \ReflectionClass($class);
			self::$meta[$class] = $o->getConstants();
		}

		return self::$meta[$class];
	}
}