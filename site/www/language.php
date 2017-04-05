<?php
declare(strict_types = 1);

class Language {

	/** @var string */
	private $language;

	/** @var string[][] */
	private $messages = [
		'en' => [
			'cs' => 'en',
			'Komentáře zákazníků' => 'Customer comments',
			'Provozuje' => 'Built by',
			'Tento web slouží pouze ke studijním účelům, nenabádám k trestné činnosti.' => "Just for educational purposes, don't break the law.",

			// Admin
			'Admin Pokl.cz' => 'Pokl.cz admin',
			'Administrace Pokl.cz' => 'Pokl.cz admin',
			'Smazat' => 'Remove',

			// Homepage
			'Pokladny Pokl.cz' => 'Cash registers Pokl.cz',
			'Pokladny pro podnikatele' => 'Cash registers for entrepreneurs',
			'Kupte si naši spolehlivou pokladnu, sníží administrativní zátěž a zrychlí prodej. Kupte dvě a dostanete mapu pokladu zcela zdarma! Objednávejte ještě dnes.' => "Buy our cash register, it's reducing the administrative burden and accelerating sales. Buy one and get a treasure map for free! Get one today.",
			'Děkujeme za platbu!' => 'Thank you for the payment',
			'Koupit další' => 'Buy another one',
			'Číslo karty:' => 'Card number:',
			'Expirace:' => 'Exp. date:',
			'MM' => 'MM',
			'RR' => 'YY',
			'Kontrolní kód:' => 'CVC:',
			'Zaplatit' => 'Pay',
			'Přidejte komentář' => 'Add a comment',
			'Jméno:' => 'Name:',
			'Pozice:' => 'Job:',
			'Komentář:' => 'Comment:',
			'Děkujeme!' => 'Thank you!',
			'Přidat komentář' => 'Add a comment',
			'Pro přidání komentáře se prosím přihlaste' => 'Sign in to add a comment',
		]
	];


	public function __construct(string $language)
	{
		$this->language = $language;
	}


	public function __invoke(string $message): string
	{
		return $this->messages[$this->language][$message] ?? $message;
	}


	public function __toString(): string
	{
		return $this->language;
	}

}

$l = new Language('cs');
