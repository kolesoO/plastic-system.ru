<?
namespace kDevelop\Ajax;

class Result
{
    private $data;

    private $arErrors = [];

    private $dataType;

    /**
     * Result constructor.
     * @param $type
     */
    public function __construct($type)
    {
        $this->dataType = $type;
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param $arErrors
     */
    public function setError($arErrors)
    {
        if (is_array($arErrors)) {
            $this->arErrors = $arErrors;
        }
    }

    /**
     *
     */
    public function output()
    {
        header("Content-type:application/json");
        echo json_encode([
            "answer" => $this->data,
            "error" => $this->arErrors
        ]);
    }
}