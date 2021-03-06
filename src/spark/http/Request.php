<?php

namespace spark\http;

use spark\common\Optional;
use spark\http\utils\CookieUtils;
use spark\http\utils\RequestUtils;
use spark\utils\UrlUtils;
use spark\upload\FileObject;
use spark\upload\FileObjectFactory;
use spark\utils\Collections;
use spark\utils\Objects;

class Request {

    private $methodName;
    private $controllerClassName;
    private $moduleName;
    private $namespace;

    private $controllerPrefix;
    private $controllerName;
    private $urlParams = array();

    private $hostPath;

    //headers
    private $headers;

    //url + Get params
    private $cachedGetParams;

    function __construct() {
        $this->headers = new HeadersWrapper(RequestUtils::getHeaders());
    }

    public function getMethodName() {
        return $this->methodName;
    }

    public function getControllerClassName() {
        return $this->controllerClassName;
    }

    public function getModuleName() {
        return $this->moduleName;
    }

    public function getControllerName() {
        return $this->controllerName;
    }

    public function setMethodName($methodName) {
        $this->methodName = $methodName;
    }

    public function setControllerClassName($controllerClassName) {
        $this->controllerClassName = $controllerClassName;
    }

    /**
     * For /spark/core/test/controller/xxx/EngineController the module name is
     * core/test/xxx  (controller is removed)
     *
     * @param $moduleName
     */
    public function setModuleName($moduleName) {
        $this->moduleName = $moduleName;
    }

    /**
     *  Only prefix of Controller class e.g: for IndexController will be "Index".
     *
     * @param $controllerName
     */
    public function setControllerName($controllerName) {
        $this->controllerName = $controllerName;
    }

    public function isPost() {
        return RequestUtils::isPost();
    }

    /**
     * @return array
     */
    public function getPostData() {
        return Collections::builder(RequestUtils::getPostParams())
            ->addAll(RequestUtils::getAllFilesParams())
            ->get();
    }

    public function getParam($name, $default = null) {
        $param = $this->getParamOrNull($name);
        return Objects::isNotNull($param) ? $param : $default;
    }

    public function setUrlParams($urlParams) {
        $this->urlParams = $urlParams;
    }

    public function getSession() {
        return RequestUtils::getOrCreateSession();
    }


    /**
     * @param $name
     * @return FileObject
     */
    public function getFileObject($name) {
        $fileData = $this->getFile($name);
        if (Objects::isNotNull($fileData)) {
            return FileObjectFactory::create($fileData);
        }
        return null;
    }

    public function getFile($name) {
        return RequestUtils::getFileParams($name);
    }

    public function isFileUploaded() {
        return RequestUtils::isFile();
    }


    /**
     * instant Redirect
     * @param $path
     */
    public function instantRedirect($path) {
        RequestUtils::redirect(UrlUtils::getPath($path));
    }

    public function setHostPath($hostPath) {
        $this->hostPath = UrlUtils::wrapHttpIfNeeded($hostPath);
    }

    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    /**
     * @return \spark\http\HeadersWrapper
     */
    public function getHeaders() {
        return $this->headers;
    }


    /**
     * @return mixed
     */
    public function getNamespace() {
        return $this->namespace;
    }

    public function getLang() {
        return Collections::getValueOrDefault($_COOKIE, "lang", "pl");
    }

    private function getParamOrNull($name) {
        if (isset($this->urlParams[$name])) {
            return $this->urlParams[$name];
        } else {
            return RequestUtils::getParam($name);
        }
    }

    public function getUrlParams() {
        if (Objects::isNull($this->cachedGetParams)) {
            $this->cachedGetParams = Collections::builder()
                ->addAll(RequestUtils::getGetParams())
                ->addAll($this->urlParams)
                ->get();
        }
        return $this->cachedGetParams;

    }

    public function getAllParams() {
        return Collections::builder()
            ->addAll($this->getUrlParams())
            ->addAll($this->getPostData())
            ->get();
    }

    public function getCookie($key, $def = null) {
        return Optional::ofNullable(CookieUtils::getCookieValue($key))->orElse($def);
    }

    public function getBody() {
        return RequestUtils::getBody();
    }
}
