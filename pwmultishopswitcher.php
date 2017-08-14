<?php
if (!defined('_PS_VERSION_'))
    exit;

class pwmultishopswitcher extends Module
{
    public function __construct()
    {
        $this->name = strtolower(get_class());
        $this->tab = 'other';
        $this->version = '0.1.0';
        $this->author = 'PrestaWeb.ru';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l("Переключение мультимагазина");
        $this->description = $this->l("switcher");
        
        $this->ps_versions_compliancy = array('min' => '1.5.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {

        if ( !parent::install() 
			OR !$this->registerHook(Array(
				'displayHeader',
				'displayTop',
			))
            
        ) return false;

        return true;
    }

    public function getShopsInfo()
    {
        $sql = "SELECT `s`.`id_shop`, `name`, `s_u`.`domain` FROM `" . _DB_PREFIX_ . "shop` s
        LEFT JOIN `" . _DB_PREFIX_ . "shop_url` s_u ON s.id_shop = s_u.id_shop";
        return Db::getInstance()->ExecuteS($sql);
    }

	public function hookdisplayHeader($params){

		$this->context->controller->addCSS($this->_path.$this->name.'.css', 'all');
		$this->context->controller->addJS($this->_path.$this->name.'.js');
	}

	public function hookdisplayTop($params){
//        var_dump($this->getShopsInfo());
        $this->smarty->assign(array(
            'shops' => $this->getShopsInfo()
        ));
		return $this->display(__FILE__, 'pwmultishopswitcher.tpl');
	}
}


