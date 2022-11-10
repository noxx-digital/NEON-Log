<?php
/*
 *
 * Logger class that supports custom dates string representation, custom log types and enableable log printing
 *
 * Logger ( [, string $log_path , string $date_format , string $head_format , string $print ] )
 *
 *      $log_path       default: "./"                               specifies the path to current log file
 *      $date_format    default: "Y-m-d H:i:s (T)"                  specifies the date string format
 *      $head_format    default: "%%date%% - %%type%% - %%msg%%"    specifies how log message will be build
 *      $print          default: false                              specifies if printing is enabled by default
 *
 */

namespace Neon\Util;

class Log
{
    private string $log_path;

    private const DATE_NEEDLE = "%%date%%";
    private const TYPE_NEEDLE = "%%type%%";
    private const MSG_NEEDLE = "%%msg%%";

    private string $date_format = 'Y-m-d H:i:s (T)';
    private string $log_format = '%%date%% [%%type%%] - %%msg%%';

    /**
     * @param string $date_format
     */
    public function set_date_format( string $date_format ): void
    {
        $this->date_format = $date_format;
    }

    /**
     * @param string $log_format
     */
    public function set_log_format( string $log_format ): void
    {
        $this->log_format = $log_format;
    }

    /**
     * @param string $log_path
     */
    public function __construct(
        string $log_path='/var/log/neon/neon.log',
    )
    {
        $this->log_path = $log_path;

        if ( !is_dir( dirname( $this->log_path )))
            mkdir( dirname( $this->log_path ), 0700, TRUE );
    }

    /**
     * @param string $log_type
     * @param string $msg
     *
     * @return string
     */
    private function construct_log( string $log_type, string $msg ): string
    {
        $log = str_replace( self::DATE_NEEDLE, date( $this->date_format ), $this->log_format );
        $log .= str_replace( self::TYPE_NEEDLE, $log_type, $log );
        $log .= str_replace( self::MSG_NEEDLE, $msg, $log );
        return $log;
    }

    /**
     * @param string $log_type
     * @param string $msg
     *
     * @return string
     */
    private function log_internal( string $log_type, string $msg ): string
    {
        $msg = str_replace( "\n", "", $msg );
        $log_msg = $this->construct_log( $log_type, $msg );

        file_put_contents( $this->log_path, $log_msg . "\n", FILE_APPEND );
        return $log_msg;
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function fatal( string $msg ): string
    {
        return $this->log_internal( (string)LogType::FATAL, $msg );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function error( string $msg ): string
    {
        return $this->log_internal( (string)LogType::ERROR, $msg );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function warn( string $msg ): string
    {
        return $this->log_internal( (string)LogType::WARN, $msg );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function info( string $msg ): string
    {
        return $this->log_internal( (string)LogType::INFO, $msg );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function debug( string $msg ): string
    {
        return $this->log_internal( (string)LogType::DEBUG, $msg );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function trace( string $msg ): string
    {
        return $this->log_internal( (string)LogType::TRACE, $msg );
    }
}
