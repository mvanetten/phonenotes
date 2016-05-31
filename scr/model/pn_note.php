<?php

//Model of the Phone Note

class Note
{
	private $mId;
	private $mName;
	private $mNumber;
	private $mDateTime;
	private $mNotes;
	private $mErrors;

	public function __construct($id = null,$name,$number,$datetime,$notes)
	{
		$this->mId = $id;
		$this->mName = $name;
		$this->mNumber = $number;
		$this->mDateTime = $datetime;
		$this->mNotes = $notes;
	}
	
	public function getId()
	{
		return $this->mId;
	}
	
	public function setId($id)
	{
		$this->mId = $id;
	}
	
	public function getName()
	{
		return $this->mName;
	}
	
	public function setName($name)
	{
		$this->mName = $name;
	}
	
	public function getNumber()
	{
		return $this->mNumber;
	}
	
	public function setNumber($number)
	{
		$this->mName = $number;
	}
	
	public function getDateTime()
	{
		return $this->mDateTime;
	}
	
	public function setDateTime($datetime)
	{
		$this->mDateTime = $date;
	}
	
	public function getNotes()
	{
		return $this->mNotes;
	}
	
	public function setNotes($notes)
	{
		$this->mNotes = $notes;
	}
	
	/**
	* IsVallid
	* Vallidates the model
	*
	* @param void
	* 
	* @return bool
	*/ 
	function IsVallid()
	{
		if (strlen($this->mNotes) > 1024)
		{
			$this->mErrors .= "Length of Notes cannot be greater than 1024 characters.<br>";
		}
		if (strlen($this->mName) > 60)
		{
			$this->mErrors .= "Length of Name cannot be greater than 60 characters.<br>";
		}
		if (strlen($this->mNumber) > 20)
		{
			$this->mErrors .= "Length of Number cannot be greater than 20 characters.<br>";
		}
		
		return strlen($this->mErrors = 0);
	}
	
	
	/**
	* getErrors
	* If there are Vallidation errors. This returns a string of errors.
	*
	* @param void
	* 
	* @return string
	*/ 
	function getErrors()
	{
		return $this->mErrors;
	}
	
}

?>