<?php
/**
 * 2019-2024 Team Ever
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 *  @author    Team Ever <https://www.team-ever.com/>
 *  @copyright 2019-2024 Team Ever
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class EverBlockClass extends ObjectModel
{
    public $id_everblock;
    public $name;
    public $content;
    public $only_home;
    public $only_category;
    public $only_category_product;
    public $only_manufacturer;
    public $only_supplier;
    public $only_cms_category;
    public $obfuscate_link;
    public $add_container;
    public $lazyload;
    public $id_hook;
    public $device;
    public $groups;
    public $background;
    public $css_class;
    public $bootstrap_class;
    public $id_shop;
    public $categories;
    public $manufacturers;
    public $suppliers;
    public $cms_categories;
    public $date_start;
    public $date_end;
    public $active;

    public static $definition = [
        'table' => 'everblock',
        'primary' => 'id_everblock',
        'multilang' => true,
        'fields' => [
            'name' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isString',
                'required' => true,
            ],
            'id_hook' => [
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt',
                'required' => true,
            ],
            'only_home' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'only_category' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'only_category_product' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'only_manufacturer' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'only_supplier' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'only_cms_category' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'obfuscate_link' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'add_container' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'lazyload' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            'device' => [
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt',
                'required' => false,
            ],
            'categories' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isJson',
                'required' => false,
            ],
            'manufacturers' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isJson',
                'required' => false,
            ],
            'suppliers' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isJson',
                'required' => false,
            ],
            'cms_categories' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isJson',
                'required' => false,
            ],
            'groups' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isJson',
                'required' => false,
            ],
            'background' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isColor',
                'required' => false,
            ],
            'css_class' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isString',
                'required' => false,
            ],
            'bootstrap_class' => [
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isString',
                'required' => false,
            ],
            'id_shop' => [
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt',
                'required' => true,
            ],
            'position' => [
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt',
                'required' => true,
            ],
            'date_start' => [
                'type' => self::TYPE_DATE,
                'lang' => false,
                'validate' => 'isDateFormat',
                'required' => false,
            ],
            'date_end' => [
                'type' => self::TYPE_DATE,
                'lang' => false,
                'validate' => 'isDateFormat',
                'required' => false,
            ],
            'active' => [
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool',
            ],
            // lang fields
            'content' => [
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isAnything',
                'required' => false,
            ],
        ],
    ];

    public static function getAllBlocks($idLang, $idShop)
    {
        $cacheId = 'EverBlockClass::getAllBlocks_'
        . (int) $idLang
        . '_'
        . (int) $idShop;
        if (!Cache::isStored($cacheId)) {
            $sql = new DbQuery();
            $sql->select('*');
            $sql->from('everblock', 'eb');
            $sql->leftJoin('everblock_lang', 'ebl', 'eb.id_everblock = ebl.id_everblock');
            $sql->where('ebl.id_lang = ' . (int) $idLang);
            $sql->where('eb.id_shop = ' . (int) $idShop);
            $sql->orderBy('eb.position ASC');

            $allBlocks = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            Cache::store($cacheId, $allBlocks);
            return $allBlocks;
        }

        return Cache::retrieve($cacheId);
    }

    public static function getBlocks(int $idHook, int $idLang, int $idShop): array
    {
        $cacheId = sprintf('EverBlockClass::getBlocks_%d_%d_%d', $idHook, $idLang, $idShop);
        if (!Cache::isStored($cacheId)) {
            $return = [];
            $now = new DateTime();
            $now = $now->format('Y-m-d H:i:s');

            $controllerTypes = ['admin', 'moduleadmin'];
            if (!in_array(Context::getContext()->controller->controller_type, $controllerTypes)) {
                $customerGroups = Customer::getGroupsStatic((int) Context::getContext()->customer->id);
            }
            $sql = new DbQuery;
            $sql->select('*');
            $sql->from('everblock', 'eb');
            $sql->leftJoin('everblock_lang', 'ebl', 'eb.id_everblock = ebl.id_everblock');
            $sql->where('eb.id_hook = ' . (int) $idHook);
            $sql->where('ebl.id_lang = ' . (int) $idLang);
            $sql->where('eb.id_shop = ' . (int) $idShop);
            $sql->where('eb.active = 1');
            $sql->orderBy('eb.position ASC');
            $allBlocks = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            foreach ($allBlocks as $block) {
                if (!empty($block['date_start']) && $block['date_start'] !== '0000-00-00 00:00:00' && $block['date_start'] > $now) {
                    continue;
                }
                if (!empty($block['date_end']) && $block['date_end'] !== '0000-00-00 00:00:00' && $block['date_end'] < $now) {
                    continue;
                }
                $allowedGroups = json_decode($block['groups'], true);
                if (isset($customerGroups)
                    && !empty($allowedGroups)
                    && !array_intersect($allowedGroups, $customerGroups)
                ) {
                    continue;
                }
                $block['bootstrap_class'] = self::getBootstrapColClass(
                    $block['bootstrap_class']
                );
                Hook::exec('actionGetEverBlockBefore', [
                    'id_hook' => $idHook,
                    'id_lang' => $idLang,
                    'id_shop' => $idShop,
                    'block' => &$block,
                ]);
                $return[] = $block;
            }
            Cache::store($cacheId, $return);
            return $return;
        }
        return Cache::retrieve($cacheId);
    }

    public static function getBootstrapColClass($colNumber)
    {
        $cacheId = 'EverBlockClass::getBootstrapColClass_'
        . (int) $colNumber;

        if (!Cache::isStored($cacheId)) {
            $class = 'col-';
            switch ($colNumber) {
                case 0:
                    break;
                case 1:
                    $class .= '12';
                    break;
                case 2:
                    $class .= '6';
                    break;
                case 3:
                    $class .= '4';
                    break;
                case 4:
                    $class .= '3';
                    break;
                case 6:
                    $class .= '2';
                    break;
                case 7:
                    $class .= 'auto';
                    break;
                default:
                    $class .= '12';
                    break;
            }
            $class .= ' col-md-';
            switch ($colNumber) {
                case 0:
                    break;
                case 1:
                    $class .= '12';
                    break;
                case 2:
                    $class .= '6';
                    break;
                case 3:
                    $class .= '4';
                    break;
                case 4:
                    $class .= '3';
                    break;
                case 6:
                    $class .= '2';
                    break;
                case 7:
                    $class .= 'auto';
                    break;
                default:
                    $class .= '12';
                    break;
            }
            Cache::store($cacheId, $class);
            return $class;
        }
        return Cache::retrieve($cacheId);
    }
}
