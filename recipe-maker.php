<?php
/**
* Class for creating POS recipe
*/
class RecipeMaker
{
	protected $width;
	protected $left;
	protected $center;
	protected $right;
	protected $sparator;
	protected $recipe;

	function __construct($value)
	{
		$this->width = $value;
	}

	public function left($value)
	{
		$this->left = $value;
		return $this;
	}

	public function right($value)
	{
		$this->right = $value;
		return $this;
	}

	public function center($value)
	{
		$this->center = $value;
		return $this;
	}

	public function sparator()
	{
		$this->sparator = true;
		return $this;
	}

	public function breaks($type = null)
	{
		if ($this->left || $this->center || $this->right) {
			$this->addCenter();
			$this->addLeftOrRightOrPosition();
			$this->addBreak();
		} else {
			$this->addBreak($type);
		}

		$this->emptyInput();
		return $this;
	}

	public function end()
	{
		$this->recipe .= "\n\n\n\n\n\n.";
		return $this;
	}

	public function output()
	{
		return $this->recipe;
	}

	public function outputHTML()
	{
		echo "<pre>";
		echo nl2br($this->recipe);
	}

	private function emptyInput()
	{
		$this->left     = '';
		$this->center   = '';
		$this->right    = '';
		$this->sparator = '';
	}

	private function addCenter()
	{
		if ($this->center) {
			$remains = ceil(($this->width - strlen($this->center)) / 2);
			$this->elemets($remains);
			$this->recipe .= $this->center;
			$this->elemets($remains);
		}
	}

	private function addLeftOrRightOrPosition()
	{
		if ($this->left || $this->right) {
			if ($this->left) {
				$remains = $this->width - strlen($this->left);
				$this->recipe .= $this->left;
			}

			if ($this->sparator) {
				$remains = ($this->left?$remains:$this->width) - 9;
				$this->elemets($remains);
				$this->recipe .= ':';

				if ($this->right) {
					$remains = 8 - strlen($this->right);
					$this->elemets($remains);
					$this->recipe .= $this->right;
				}
			} else {
				$remains = ($this->left?$remains:$this->width) - strlen($this->right);
				$this->elemets($remains);

				if ($this->right) {
					$this->recipe .= $this->right;
				}
			}
		}
	}

	private function addBreak($type = null)
	{
		if ($type) {
			$this->elemets($this->width, $type);
		}

		$this->recipe .= "\n";
	}

	private function elemets($length, $type = null)
	{
		for ($i=0; $i < $length; $i++) {
			$this->recipe .= $type?$type:' ';
		}
	}

}