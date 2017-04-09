<?php

namespace Foo\Cryptography\Hashing;

use Opulence\Cryptography\Hashing\Hasher;

/**
 * Defines a cryptographic hasher which uses the default algorithm available in PHP
 *
 * More info: http://php.net/manual/en/password.constants.php
 */
class DefaultHasher extends Hasher
{
    /** The default cost used by this hasher */
    const DEFAULT_COST = 10;

    /**
     * @inheritdoc
     */
    public function hash(string $unhashedValue, array $options = [], string $pepper = ''): string
    {
        if (!isset($options['cost'])) {
            $options['cost'] = self::DEFAULT_COST;
        }

        return parent::hash($unhashedValue, $options, $pepper);
    }

    /**
     * @inheritdoc
     */
    public function needsRehash(string $hashedValue, array $options = []): bool
    {
        if (!isset($options['cost'])) {
            $options['cost'] = self::DEFAULT_COST;
        }

        return parent::needsRehash($hashedValue, $options);
    }

    /**
     * @inheritdoc
     */
    protected function setHashAlgorithm()
    {
        $this->hashAlgorithm = PASSWORD_DEFAULT;
    }
}
