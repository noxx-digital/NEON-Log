<?php

namespace Neon\Util;

enum LogType: string
{
    case FATAL  = 'FATAL';
    case ERROR  = 'ERROR';
    case WARN   = 'WARN';
    case INFO   = 'INFO';
    case DEBUG  = 'DEBUG';
    case TRACE  = 'TRACE';
}