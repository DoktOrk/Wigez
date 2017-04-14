<?php

namespace Foo\Filesystem\Uploader;

class UploadInfo
{
    const RESULT_UNPROCESSED       = 'File "%s" is not yet processed.';
    const RESULT_UPLOAD_MISSING    = 'File "%s" is not uploaded.';
    const RESULT_UPLOAD_FORBIDDEN  = 'Uploaded file "%s" is not whitelisted.';
    const RESULT_FILE_NOT_READABLE = 'Uploaded file "%s"is not readable.';
    const RESULT_VALIDATED         = 'Upload "%s" is validated';
    const RESULT_FILE_NOT_WRITABLE = 'Persisting file "%s" failed.';
    const RESULT_SUCCESS           = 'File "%s" was uploaded successfully.';

    const FILE_NAME     = 'name';
    const FILE_TMP_NAME = 'tmp_name';

    const ERROR_NOT_VALIDATED = 'File is not processed yet.';
    const ERROR_NOT_PERSISTED = 'File is validated, but not processed yet.';

    /** @var array  HTTP File Upload info */
    protected $rawData = [];

    /** @var string */
    protected $dirName = '';

    /** @var string */
    protected $fileName = '';

    /** @var string */
    protected $path = '';

    /** @var string */
    protected $name = '';

    /**
     * UploadInfo constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->result = static::RESULT_UNPROCESSED;

        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * @return string
     */
    public function getRawName(): string
    {
        if (!array_key_exists(static::FILE_NAME, $this->rawData)) {
            return '';
        }

        return $this->rawData[static::FILE_NAME];
    }

    /**
     * @return string
     */
    public function getTmpName(): string
    {
        if (!array_key_exists(static::FILE_TMP_NAME, $this->rawData)) {
            return '';
        }

        return $this->rawData[static::FILE_TMP_NAME];
    }

    /**
     * @return string
     */
    public function getDirName(): string
    {
        return $this->dirName;
    }

    /**
     * @param string $dirName
     */
    public function setDirName(string $dirName)
    {
        $this->dirName = $dirName;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return sprintf(
            $this->result,
            $this->name
        );
    }

    /**
     * @param string $result
     */
    public function setResult(string $result)
    {
        $this->result = $result;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        switch ($this->result) {
            case static::RESULT_UNPROCESSED:
                throw new Exception(sprintf(static::ERROR_NOT_VALIDATED, $this->name));
            case static::RESULT_VALIDATED:
                throw new Exception(sprintf(static::ERROR_NOT_PERSISTED, $this->name));
            case static::RESULT_SUCCESS:
                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        switch ($this->result) {
            case static::RESULT_UNPROCESSED:
                throw new Exception(sprintf(static::ERROR_NOT_VALIDATED, $this->name));
            case static::RESULT_VALIDATED:
            case static::RESULT_SUCCESS:
                return true;
        }

        return false;
    }
}
