<?php

declare(strict_types=1);

const DEBUG = true;

// Profiling
define('M_START_MEMORY', memory_get_usage());
define('M_START_TIME', microtime(true));

// System wide constants
const DS = DIRECTORY_SEPARATOR;

const M_PATH_ROOT = __DIR__ . DS;
const M_PATH_SYSTEM = M_PATH_ROOT;

const M_PATH_CONFIG = M_PATH_ROOT;
const M_PATH_DATA = M_PATH_ROOT;
const M_PATH_CACHE = M_PATH_ROOT;
const M_PATH_LOG = M_PATH_ROOT;
const M_PATH_PUBLIC = M_PATH_ROOT;

// System files
const M_FILE_CONFIG_CACHE = M_PATH_CACHE . 'system-config.cache';
const M_FILE_CONFIG_ROUTES = M_PATH_CONFIG . 'routes.php';
