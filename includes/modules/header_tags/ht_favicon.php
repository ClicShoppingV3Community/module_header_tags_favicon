<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTTP;
  use ClicShopping\OM\CLICSHOPPING;

  class ht_favicon
  {
    public string $code;
    public $group;
    public $title;
    public $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_header_tags_favicon_title');
      $this->description = CLICSHOPPING::getDef('module_header_tags_favicon_description');

      if (\defined('MODULE_HEADER_TAGS_FAVICON_STATUS')) {
        $this->sort_order = MODULE_HEADER_TAGS_FAVICON_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_FAVICON_STATUS == 'True');
      }
    }

    public function execute()
    {

      $CLICSHOPPING_Template = Registry::get('Template');

      $extension_favicon = MODULE_HEADER_TAGS_FAVICON_EXTENSION_FAVICON;
      $CLICSHOPPING_Template->addBlock('<link rel="shortcut icon" type="image/' . $extension_favicon . '" href="' . HTTP::getShopUrlDomain() . 'sources/images/icons/favicon.' . $extension_favicon . '">', $this->group);
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function check()
    {
      return \defined('MODULE_HEADER_TAGS_FAVICON_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want enable this module ?',
          'configuration_key' => 'MODULE_HEADER_TAGS_FAVICON_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want enable this module ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez choisir l\'extension du favicon ?',
          'configuration_key' => 'MODULE_HEADER_TAGS_FAVICON_EXTENSION_FAVICON',
          'configuration_value' => 'png',
          'configuration_description' => 'Vous pouvez choisir entre un extension en png, gif ou ico : <br /><br /><strong>Note :</strong><br /><br />- le favicon est à mettre dans le répertoire /image/icons',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'png\', \'gif\', \'ico\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_HEADER_TAGS_FAVICON_SORT_ORDER',
          'configuration_value' => '75',
          'configuration_description' => 'Sort order. Lowest is displayed in first',
          'configuration_group_id' => '6',
          'sort_order' => '55',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return ['MODULE_HEADER_TAGS_FAVICON_STATUS',
        'MODULE_HEADER_TAGS_FAVICON_EXTENSION_FAVICON',
        'MODULE_HEADER_TAGS_FAVICON_SORT_ORDER'
	];
    }
  }
