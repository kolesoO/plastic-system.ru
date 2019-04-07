<?
namespace kDevelop\Ajax;

class General
{
    use MsgHandBook;

    private $className;

    private $methodName;

    private $arParams;

    private $arErrors = [];

    /**
     * General constructor.
     * @param $className
     * @param $methodName
     * @param $arParams
     */
    public function __construct($className, $methodName, $arParams)
    {
        $this->className = "\\".__NAMESPACE__."\\".$className;
        $this->methodName = $methodName;
        $this->arParams = self::prepareParams($arParams);
    }

    /**
     * @param $arParams
     * @return mixed
     */
    private static function prepareParams($arParams)
    {
        return $arParams;
    }

    /**
     * @return mixed
     */
    public function callMethod()
    {
        if (is_callable([$this->className, $this->methodName], true, $callableName)) {
            return $callableName($this->arParams);
        } else {
            $this->setError("CALL_METHOD_ERROR");
        }
    }

    /**
     * @param $code
     */
    public function setError($code)
    {
        $this->arErrors[] = self::getMsg($code);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->arErrors;
    }
}