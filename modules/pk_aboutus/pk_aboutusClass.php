<?php

class pk_aboutusClass extends ObjectModel
{
	/** @var integer pk_aboutus id */
	public $id;

	/** @var integer pk_aboutus id shop */
	public $id_shop;

	/** @var string body_title */
	public $body_home_logo_link;

	/** @var string body_title */
	public $body_title;

	/** @var string body_title */
	public $body_subheading;

	/** @var string body_title */
	public $body_paragraph;

	/** @var string body_title */
	public $body_logo_subheading;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'pk_aboutus',
		'primary' => 'id_pk_aboutus',
		'multilang' => true,
		'fields' => array(
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'body_home_logo_link' => array('type' => self::TYPE_STRING, 'validate' => 'isUrl'),
			'body_title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
			'body_subheading' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
			'body_paragraph' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
			'body_logo_subheading' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
		)
	);

	public static function getByIdShop($id_shop)
	{
		$id = Db::getInstance()->getValue('SELECT `id_pk_aboutus` FROM `'._DB_PREFIX_.'pk_aboutus` WHERE `id_shop` ='.(int)$id_shop);

		return new pk_aboutusClass($id);
	}

	public function copyFromPost()
	{
		/* Classical fields */
		foreach ($_POST as $key => $value)
		{
			if (key_exists($key, $this) && $key != 'id_'.$this->table)
				$this->{$key} = $value;
		}

		/* Multilingual fields */
		if (count($this->fieldsValidateLang))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				foreach ($this->fieldsValidateLang as $field => $validation)
				{
					if (Tools::getIsset($field.'_'.(int)$language['id_lang']))
						$this->{$field}[(int)$language['id_lang']] = $_POST[$field.'_'.(int)$language['id_lang']];
				}
			}
		}
	}
}