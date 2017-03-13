<?php
namespace v3u3i87\tool;
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 16/4/22
 * Time: 11:40
 * Name: http header加密
 */

class HaeradEncrypt{

    private $_header = null;

    private $_jsonHeader = null;

    private $_base64_encode = null;

    private $param = [];

    private $_useHeader = null;

    private $code = null;

    private $encryptType = null;

    /**
     * HaeradEncrypt constructor.
     * @param array $_param 获取自定义参数
     * @param null $code 设置加密需要的code
     * @param null $encryptType 加密类型md5,sha1  默认为md5
     */
    public function __construct($_param=[],$code=null,$encryptType='md5')
    {
        $this->param = $_param;
        $this->code = $code;
        $this->encryptType = $encryptType;
        $this->init();
    }

    private function init()
    {
        $this->_header = $this->get_header();
        $this->is_use_header();
        $this->setJson();
        $this->setBbase64Encode();
    }


    /**
     * 判断是否为使用的协议
     * @throws Exception
     */
    private function is_use_header()
    {
        if(!empty($this->param) && count($this->param) > 0)
        {
            foreach($this->param as $key)
            {
                if(isset($this->_header[$key]))
                {
                    $this->_useHeader[$key] = $this->_header[$key];
                }
            }

            if(count($this->param) !== count($this->_useHeader))
            {
                throw new Exception('the val use_Header error or this param ');
            }
        }else{
            $this->_useHeader = $this->_header;
        }
    }


    /**
     * 设置json
     * @throws Exception
     */
    private function setJson()
    {
        $this->_jsonHeader = json_encode($this->_useHeader);
        if(is_string($this->_jsonHeader) === false)
        {
            throw new Exception('json encoding failed');
        }
    }

    /**
     * 设置base64_encode
     * @throws Exception
     */
    private function setBbase64Encode()
    {
        $this->_base64_encode = base64_encode($this->_jsonHeader);
        if(is_string($this->_base64_encode) === false)
        {
            throw new Exception('base64_encode encoding failed');
        }
    }

    /**
     * 获取头协议
     * @return mixed
     */
    private function get_header()
    {
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * 设置加密类型
     * @return string
     */
    private function setEncrypt()
    {
        $encryptData  = $this->_base64_encode.$this->code;
        switch($this->encryptType)
        {
            case 'md5':
                return md5($encryptData);
            break;

            case 'sha1':
                return sha1($encryptData);
            break;

            default:
                return md5($encryptData);
            break;
        }
    }

    /**
     * 获取加密串
     * @return array
     * @throws Exception
     */
    public function getEncrypt()
    {
        return [
            'encrypt'=>$this->setEncrypt(),
            'use_header'=>$this->_useHeader,
            'header'=>$this->_header,
            'json'=>$this->_jsonHeader,
            'encryptType'=>$this->encryptType,
            'base64_encode'=>$this->_base64_encode
        ];
    }

    public function show()
    {
        return [
            $this->_header,
            $this->_useHeader,
            $this->_jsonHeader,
            $this->_base64_encode
        ];
    }





}

$header = new HaeradEncrypt(['Accept-Token','User-Agent'],'yueshuan###');

print_r($header->getEncrypt());
