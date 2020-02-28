<?
namespace kDevelop\Help;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity\DataManager;

class Hload
{
    protected static $entity = false;
    protected static $entityName = false;
    protected static $current = null;

    /**
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getEntityClass()
    {
        $entity = false;
        if (static::$entity == false)
        {
            \Bitrix\Main\Loader::includeModule('highloadblock');
            $hlblock = HL\HighloadBlockTable::getList(['filter' => ["NAME" => static::getEntityName()]])->fetch();
            if ($hlblock)
            {
                $entity = HL\HighloadBlockTable::compileEntity($hlblock); //генерация класса
            }
            if (is_object($entity))
            {
                if (method_exists(get_called_class(), "getMap"))
                {
                    $classEntity = $entity->getDataClass();
                    $classEntityCustom = static::getEntityName() . "CustomTable";
                    $eval = '
                        class ' . $classEntityCustom . ' extends ' . $classEntity . '
                        {
                            
        
                            public static function getMap()
                            {
                                return ' . get_called_class() . '::getMap();
                            }
        
                        }
                    ';
                    eval($eval);
                    static::$entity = $classEntityCustom;
                }
                else
                {
                    static::$entity = $entity->getDataClass();
                }
            }
        }

        return static::$entity;
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function __callStatic($method, $args)
    {
        $entityClass = static::getEntityClass();

        return call_user_func_array([$entityClass, $method], $args);


    }

    /**
     * @return bool
     */
    public function getEntityName()
    {
        return static::$entityName;
    }
}