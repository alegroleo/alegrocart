<?php 
class Address {

	private $data = array();
	
	public function __construct(&$locator){
		$this->customer =& $locator->get('customer');
		$this->database =& $locator->get('database');
		$this->config   =& $locator->get('config');
		
		if ($this->customer->getId()) {
			$results = $this->database->getRows("select *, c.name as country, z.name as zone from address a left join country c on a.country_id = c.country_id left join zone z on a.zone_id = z.zone_id where a.customer_id = '" . (int)$this->customer->getId() . "'");
			
			foreach ($results as $result) {
				$this->data[$result['address_id']] = array(
					'firstname'      => $result['firstname'],
					'lastname'       => $result['lastname'],
					'company'        => $result['company'],
					'address_1'      => $result['address_1'],
					'address_2'      => $result['address_2'],
					'postcode'       => $result['postcode'],
					'city'           => $result['city'],
					'country_id'     => $result['country_id'],
					'zone_id'        => $result['zone_id'],
					'iso_code_2'     => $result['iso_code_2'],
					'iso_code_3'     => $result['iso_code_3'],
					'code'           => $result['code'],
					'zone'           => $result['zone'],
					'country'        => $result['country'],	
					'address_format' => $result['address_format']
				);
			}
		}
	}
	
	public function get($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id] : NULL); 
	}

	public function has($address_id) {
		return isset($this->data[$address_id]); 
	}

	public function format($address, $format = NULL, $new_line = "\n") {
		if (!$format) {
			$format = $this->config->get('config_address_format') ? $this->config->get('config_address_format') : 'firstname lastname' . "\n" . 'company' . "\n" . 'address_1' . "\n" . 'address_2' . "\n" . 'city, zone' . "\n" . 'country, postcode';
		}

		$find = array(
			'firstname',
			'lastname',
			'company',
			'address_1',
			'address_2',
			'city',
			'postcode',
			'zone',
			'country'
		);

		$replace = array(
			'firstname' => $address['firstname'],
			'lastname'  => $address['lastname'],
			'company'   => $address['company'],
	      		'address_1' => $address['address_1'],
	      		'address_2' => $address['address_2'],
	      		'city'      => $address['city'],
	      		'postcode'  => $address['postcode'],
	      		'zone'      => $address['zone'],
	      		'country'   => $address['country']  
		);

		return str_replace(array("\r\n", "\r", "\n"), $new_line, preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), $new_line, trim(str_replace($find, $replace, $format))));
	}

	public function getFirstName($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['firstname'] : NULL);
	}
	
	public function getLastName($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['lastname'] : NULL);
	}

	public function getCompany($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['company'] : NULL);
	}

	public function getAddress1($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['address_1'] : NULL);
	}

	public function getAddress2($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['address_2'] : NULL);
	}

	public function getPostCode($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['postcode'] : NULL);
	}

	public function getCity($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['city'] : NULL);
	}

	public function getCountryId($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['country_id'] : NULL);
	}

	public function getZoneId($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['zone_id'] : NULL);
	}

	public function getIsoCode2($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['iso_code_2'] : NULL);
	}

	public function getIsoCode3($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['iso_code_3'] : NULL);
	}

	public function getZoneCode($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['code'] : NULL);
	}

	public function getZone($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['zone'] : NULL);
	}

	public function getCountry($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['country'] : NULL);
	}

	public function getFormat($address_id) {
		return (isset($this->data[$address_id]) ? $this->data[$address_id]['address_format'] : NULL);
	}

	public function getFormatted($address_id, $new_line = "\n") {
		return (isset($this->data[$address_id]) ? $this->format($this->data[$address_id], $this->data[$address_id]['address_format'], $new_line) : NULL);
	}

}
?>
