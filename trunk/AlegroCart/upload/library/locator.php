<?php //AlegroCart
define('E_COULD_NOT_CREATE','Error: Could not create %s!');
define('E_COULD_NOT_WRITE','Error: Could not write to %s directory!');
include_once(DIR_LIBRARY . 'autoloader.php');
class Locator {	
	private $data = array();
	
	function &get($key) {
		if (!isset($this->data[$key])) $this->data[$key] = $this->create($key);
		return $this->data[$key];
	}
	
	function set($key, &$value) {
		$this->data[$key] =& $value;
	}
		
	function create($key) {
		$method = 'create'.$key;
		if (method_exists($this, $method)) { return $this->$method(); }
		if (class_exists($key)) return new $key($this);
		exit(sprintf(E_COULD_NOT_CREATE,$key));
	}

	function createAddress() {
		require_once(DIR_LIBRARY.'cart/address.php');
		return new Address($this);
	}
	
	function createCache() {
    	if (!is_writable(DIR_CACHE)) exit(sprintf(E_COULD_NOT_WRITE,'cache'));
		require_once(DIR_LIBRARY.'cache/cache.php');
		return new Cache();
	}

	function createCalculate() {
		require_once(DIR_LIBRARY.'cart/calculate.php');
		return new Calculate($this);
	}
	
	function createConfig() {
		require_once(DIR_LIBRARY.'application/config.php');
		return new Config();
	}

	function createCoupon() {
		require_once(DIR_LIBRARY.'cart/coupon.php');
		return new Coupon($this);
	}
			
	function createCurrency() {
		require_once(DIR_LIBRARY.'cart/currency.php');
		return new Currency($this);
	}
	
	function createCart() {
		require_once(DIR_LIBRARY.'cart/cart.php');
		return new Cart($this);
	}
	
	function createController() {
		require_once(DIR_LIBRARY.'application/controller.php');
		return new Controller($this);
	}
		
	function createHeaderDefinition() {       // New CSS
		require_once(DIR_LIBRARY . 'template/headerdefinition.php');
		return new HeaderDefinition($this);
	}
	function createCustomer() {
		require_once(DIR_LIBRARY.'cart/customer.php');
		return new Customer($this);
	}
			
	function createDatabase() {
		require_once(DIR_LIBRARY.'database/database.php');
		$database = new Database($this);
		$database->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		return $database;
	}
	
	function createDownload() {
		require_once(DIR_LIBRARY.'filesystem/download.php');
		return new Download();
	}
	
	function createErrorHandler(){
		require_once(DIR_LIBRARY.'session/errorhandler.php');
		return new ErrorHandler($this);
	}
	
	function createImage() {
		if (!is_writable(DIR_IMAGE)) exit(sprintf(E_COULD_NOT_WRITE,'image'));
		require_once(DIR_LIBRARY.'image/image.php');
		return new Image($this);
	}
	
	function createLanguage() {
		require_once(DIR_LIBRARY.'language/language.php');
		return new Language($this);
	}

	function createMail() {
		require_once(DIR_LIBRARY.'mail/mail.php');
		return new Mail();
	}
	
	function createModel() {
		require_once(DIR_LIBRARY.'application/model.php');
		$model = new Model($this);
		return $model;
	}
			
	function createModule() {
		require_once(DIR_LIBRARY.'cart/module.php');
		return new Module($this);
	}

	function createOrder() {
		require_once(DIR_LIBRARY.'cart/order.php');
		return new Order($this);
	}

	function createPayment() {
		require_once(DIR_LIBRARY.'cart/payment.php');
		return new Payment($this);
	}
		
	function createRandomNumber(){                     // New Random number generator
		require_once(DIR_LIBRARY . 'application/randomnumber.php');
		return new RandomNumber($this);
	}
	function createRequest() {
		require_once(DIR_LIBRARY.'environment/request.php');
		return new Request($this);
	}

	function createResponse() {
		require_once(DIR_LIBRARY.'environment/response.php');
		return new Response($this);
	}
	
	function createRouter() {
		require_once(DIR_LIBRARY.'application/router.php');		
		return new Router($this);
	}
		
	function createSession() {
		require_once(DIR_LIBRARY.'session/session.php');
		return new Session($this);
	}
	
	function createShipping() {
		require_once(DIR_LIBRARY.'cart/shipping.php');
		return new Shipping($this);
	}
	
	function createTax() {
		require_once(DIR_LIBRARY.'cart/tax.php');
		return new Tax($this);
	}
			
	function createUpload() {
		require_once(DIR_LIBRARY.'filesystem/upload.php');
		return new Upload();
	}
	
	function createUrl() {
		require_once(DIR_LIBRARY.'environment/url.php');
		return new Url($this);
	}			
	
	function createUser() {
		require_once(DIR_LIBRARY.'user/user.php');
		return new User($this);
	}
		
	function createTemplate() {
		require_once(DIR_LIBRARY.'template/template.php');
		$config = $this->get('config');		
		$template = new Template($config->get('config_template'),$config->get('config_styles'),$config->get('config_colors'),$config->get('config_columns'));
		$template->set('locator',$this);
		$template->set('URL_TPL',$this->get('url'));
		return $template;
	}
	
	function createWeight() {
		require_once(DIR_LIBRARY.'cart/weight.php');
		return new Weight($this);
	}

	function createValidate() {
		require_once(DIR_LIBRARY.'validate/validate.php');
		return new Validate($this);
	}
}
?>