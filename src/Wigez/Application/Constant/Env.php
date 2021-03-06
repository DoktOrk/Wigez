<?php

namespace Wigez\Application\Constant;

class Env
{
    const ENV_NAME = 'ENV_NAME';

    const SESSION_HANDLER          = 'SESSION_HANDLER';
    const SESSION_CACHE_BRIDGE     = 'SESSION_CACHE_BRIDGE';
    const SESSION_COOKIE_DOMAIN    = 'SESSION_COOKIE_DOMAIN';
    const SESSION_COOKIE_IS_SECURE = 'SESSION_COOKIE_IS_SECURE';
    const SESSION_COOKIE_PATH      = 'SESSION_COOKIE_PATH';

    const VIEW_CACHE = 'VIEW_CACHE';

    const DB_HOST     = 'DB_HOST';
    const DB_USER     = 'DB_USER';
    const DB_PASSWORD = 'DB_PASSWORD';
    const DB_NAME     = 'DB_NAME';
    const DB_PORT     = 'DB_PORT';

    const MEMCACHED_HOST = 'MEMCACHED_HOST';
    const MEMCACHED_PORT = 'MEMCACHED_PORT';

    const REDIS_HOST     = 'REDIS_HOST';
    const REDIS_PORT     = 'REDIS_PORT';
    const REDIS_DATABASE = 'REDIS_DATABASE';

    const ENCRYPTION_KEY = 'ENCRYPTION_KEY';

    const DEFAULT_LANGUAGE = 'DEFAULT_LANGUAGE';

    const DIR_PRIVATE = 'DIR_PRIVATE';
    const DIR_PUBLIC  = 'DIR_PUBLIC';
}

