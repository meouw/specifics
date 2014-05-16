Enum
====

Base class for creating Enums in PHP.
Enums are useful because they are objects with a type which can be type-hinted upon and can only be one of a finite set of values


Example
-------

Say we have a card game and need to set the suit which will be trumps

'''
<?php

/**
 * Create a class with constants for each allowable value
 */
class Suit extends Enum
{
	const CLUBS    = 'clubs';
	const DIAMONDS = 'diamonds';
	const HEARTS   = 'hearts';
	const SPADES   = 'spades';
}

// create a Suit with the value of clubs
$clubs = new Suit(Suit::CLUBS);

class CardGame
{
    /**
     * We would like to guarantee that the value of $trumps is allowable
     * i.e one of clubs, diamonds, hearts or spades
     * We can create a `Suit` Enum and type-hint on it
     *
     * @param Suit $trumps - which suit to set as trumps
     */
    public function __construct(Suit $trumps)
    {
        $trumps = $trumps->getValue();
    }
}

// Create a game with clubs as trumps
$game = new CardGame($clubs);

'''

