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

namespace Neon;

class Log
{
    private string $log_path;

    private const DATE_NEEDLE = "%%date%%";
    private const TYPE_NEEDLE = "%%type%%";
    private const MSG_NEEDLE = "%%msg%%";

    private const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s (T)';
    private const DEFAULT_LOG_FORMAT = '%%date%% [%%type%%] - %%msg%%';

    private array $custom_log_types;

    /**
     * @param string $log_path
     */
    public function __construct(
        string $log_path = '/tmp/neon.log',
    )
    {
        $this->log_path             = $log_path;
        $this->custom_log_types     = [];

        if ( !is_dir( dirname( $this->log_path )))
            mkdir( dirname( $this->log_path ), 0700, TRUE );
    }

    /**
     * @param LogType $type
     * @param string $msg
     *
     * @return string
     */
    private function construct_msg( LogType $type, string $msg ): string
    {
        $log_string = str_replace( self::DATE_NEEDLE, date( self::DEFAULT_DATE_FORMAT ), self::DEFAULT_LOG_FORMAT );
        $log_string .= str_replace( self::TYPE_NEEDLE, $type, $log_string );
        $log_string .= str_replace( self::MSG_NEEDLE, $msg, $log_string );
        return $log_string;
    }

    /**
     * @param LogType $log_type
     * @param string $msg
     *
     * @return string
     */
    private function log_internal( LogType $log_type, string $msg ): string
    {
        $msg = str_replace( "\n", "", $msg );
        $log_msg = $this->construct_msg( $log_type, $msg );

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
        return $this->log_internal( LogType::FATAL, $msg );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function error( string $msg ): string
    {
        return $this->log_internal( LogType::ERROR, $msg );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function warn( string $msg ): string
    {
        return $this->log_internal( LogType::WARN, $msg, $print );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function info( string $msg ): string
    {
        return $this->log_internal( LogType::INFO, $msg, $print );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function debug( string $msg ): string
    {
        return $this->log_internal( LogType::DEBUG, $msg, $print );
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function trace( string $msg ): string
    {
        return $this->log_internal( LogType::TRACE, $msg, $print );
    }
}
