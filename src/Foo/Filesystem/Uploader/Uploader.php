<?php

namespace Foo\Filesystem\Uploader;

use League\Flysystem\Filesystem;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Errors\ErrorCollection;
use Opulence\Validation\Rules\Factories\RulesFactory;
use Opulence\Validation\Validator;

class Uploader extends Validator implements IValidator
{
    const NOT_VALIDATED = 'File uploads are not validated';

    const NOT_UPLOADED = 'File "%s" was not uploaded';

    /** @var Filesystem */
    protected $reader;

    /** @var Filesystem */
    protected $persister;

    /** @var array HTTP File Upload variables */
    protected $files = [];

    /** @var UploadInfo[] */
    protected $uploadInfo = [];

    /** @var bool */
    protected $isValid = false;

    /** @var bool */
    protected $isValidated = false;

    /**
     * Uploader constructor.
     *
     * @param Filesystem   $reader
     * @param Filesystem   $persister
     * @param RulesFactory $rulesFactory
     */
    public function __construct(Filesystem $reader, Filesystem $persister, RulesFactory $rulesFactory)
    {
        $this->reader    = $reader;
        $this->persister = $persister;

        parent::__construct($rulesFactory);
    }

    /**
     * @param array $allValues
     * @param bool  $haltFieldValidationOnFailure
     *
     * @return bool
     */
    public function isValid(array $allValues, bool $haltFieldValidationOnFailure = false): bool
    {
        global $_FILES;

        if ($this->isValidated) {
            return $this->isValid;
        }

        $this->isValidated = true;

        $this->files = empty($allValues) ? $_FILES : $allValues;

        $this->isValid = true;
        foreach (array_keys($this->files) as $key) {
            if (!$this->validate($key)) {
                $this->isValid = false;

                if ($haltFieldValidationOnFailure) {
                    break;
                }
            }
        }

        return $this->isValid;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function validate(string $key): bool
    {
        $uploadInfo = new UploadInfo($key);

        $this->uploadInfo[$key] = $uploadInfo;

        if (!array_key_exists($key, $this->files)) {
            $uploadInfo->setResult(UploadInfo::RESULT_UPLOAD_MISSING);

            return false;
        }

        if (!array_key_exists($key, $this->rulesByField)) {
            $uploadInfo->setResult(UploadInfo::RESULT_UPLOAD_FORBIDDEN);

            return false;
        }

        $uploadInfo->setRawData($this->files[$key]);

        if (!$this->reader->has($uploadInfo->getTmpName())) {
            $uploadInfo->setResult(UploadInfo::RESULT_FILE_NOT_READABLE);

            return false;
        }

        $this->prepareUpload($uploadInfo);
        $uploadInfo->setResult(UploadInfo::RESULT_VALIDATED);

        return true;
    }

    /**
     * @param UploadInfo
     */
    protected function prepareUpload(UploadInfo $uploadInfo)
    {
        $dirName  = $this->getDirName($uploadInfo);
        $fileName = $this->getFileName($uploadInfo);
        $path     = $dirName . $fileName;

        $uploadInfo->setDirName($dirName);
        $uploadInfo->setFileName($fileName);
        $uploadInfo->setPath($path);
    }

    /**
     * @param array|null $keys
     *
     * @return bool
     */
    public function persistAll(array $keys = null): bool
    {
        $keys = $keys === null ? array_keys($this->files) : $keys;

        foreach ($keys as $key) {
            $uploadInfo = $this->persist($key);

            if (!$uploadInfo->isSuccess()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $key
     *
     * @return UploadInfo
     */
    public function persist(string $key): UploadInfo
    {
        if (!$this->isValidated) {
            throw new Exception(static::NOT_VALIDATED);
        }

        if (!array_key_exists($key, $this->uploadInfo)) {
            throw new Exception(sprintf(static::NOT_UPLOADED, $key));
        }

        $uploadInfo = $this->uploadInfo[$key];

        $read = $this->reader->get($uploadInfo->getTmpName());

        if ($this->persister->putStream($uploadInfo->getPath(), $read->readStream())) {
            $uploadInfo->setResult(UploadInfo::RESULT_SUCCESS);
        } else {
            $uploadInfo->setResult(UploadInfo::RESULT_FILE_NOT_WRITABLE);
        }

        return $uploadInfo;
    }

    /**
     * @param UploadInfo $uploadInfo
     *
     * @return string
     */
    protected function getDirName(UploadInfo $uploadInfo): string
    {
        return '/';
    }

    /**
     * @param UploadInfo $uploadInfo
     *
     * @return string
     */
    protected function getFileName(UploadInfo $uploadInfo): string
    {
        $filename = time() . rand(10000, 99999);

        return $filename;
    }

    /**
     * @return ErrorCollection
     */
    public function getErrors(): ErrorCollection
    {
        $errors = new ErrorCollection();

        foreach ($this->uploadInfo as $key => $uploadInfo) {
            if ($uploadInfo->isSuccess()) {
                continue;
            }

            $errors[$key] = [$uploadInfo->getResult()];
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function getSuccessMessages(): array
    {
        $messages = [];

        foreach ($this->uploadInfo as $key => $uploadInfo) {
            if (!$uploadInfo->isSuccess()) {
                continue;
            }

            $messages[$key] = $uploadInfo->getResult();
        }

        return $messages;
    }

    /**
     * @param string $key
     *
     * @return UploadInfo
     */
    public function getUploadInfo(string $key): UploadInfo
    {
        if (!array_key_exists($key, $this->uploadInfo)) {
            $this->uploadInfo[$key] = new UploadInfo($key);
        }

        return $this->uploadInfo[$key];
    }

    /**
     * @param string $fileName
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $fileName, string $key)
    {
        $path = $this->getPath($fileName, $key);

        if (!$this->persister->has($path)) {
            return false;
        }

        return $this->persister->delete($path);
    }

    /**
     * @param string $fileName
     * @param string $key
     *
     * @return string
     */
    public function getContent(string $fileName, string $key)
    {
        $path = $this->getPath($fileName, $key);

        if (!$this->persister->has($path)) {
            return '';
        }

        return $this->persister->read($path);
    }

    /**
     * @param string $fileName
     * @param string $key
     *
     * @return bool|false|resource
     */
    public function getStream(string $fileName, string $key)
    {
        $path = $this->getPath($fileName, $key);

        if (!$this->persister->has($path)) {
            return false;
        }

        return $this->persister->readStream($path);
    }

    /**
     * @param string $fileName
     * @param string $key
     *
     * @return string
     */
    protected function getPath(string $fileName, string $key)
    {
        $uploadInfo = $this->getUploadInfo($key);
        $dirName    = $this->getDirName($uploadInfo);
        $path       = $dirName . $fileName;

        return $path;
    }

    /**
     * @param string $fileName
     * @param string $key
     *
     * @return int
     */
    public function getSize(string $fileName, string $key)
    {
        $path = $this->getPath($fileName, $key);

        if (!$this->persister->has($path)) {
            return 0;
        }

        return $this->persister->getSize($path);
    }
}
