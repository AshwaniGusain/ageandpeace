<?php 

namespace Snap\Ui\Components\Bootstrap;

use Snap\Ui\Traits\CssClassesTrait;

class ButtonLink extends Button {
	
	use CssClassesTrait;

	protected $view = 'component::bootstrap.button-link';

	public function setHref($href)
	{
		$this->setAttr('href', $href);
		
		return $this;
	}

	public function getHref()
	{
		if (isset($this->data['attrs']['href']))
		{
			return $this->getAttr('href');
		}
	}
	

	// public function setIcon($class)
	// {
	// 	$this->data['icon'] = ui('icon', ['class' => $class, 'icon' => $class]);
	// 	return $this;
	// }

}