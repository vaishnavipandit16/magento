<?php
class Ccc_Competitor_Model_Resource_Competitor_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
	public function __construct()
	{
		$this->setEntity('competitor');
		parent::__construct();
		
	}
}