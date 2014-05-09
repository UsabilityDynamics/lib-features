<?php
/**
 * Inits Custom Post Types, Taxonimies, Meta
 *
 * @author Usability Dynamics, Inc. <info@usabilitydynamics.com>
 * @author peshkov@UD
 */
namespace UsabilityDynamics\Feature {

  if( !class_exists( 'UsabilityDynamics\Feature\Flag' ) ) {

    class Flag {

      static public $_initialized = false;

      static public $_flags = array();

      static public function setup() {

        $_flags = array();

        // Trigger right away to avoid infinite loop.
        self::$_initialized = true;

        $_flags[] = $_SERVER[ 'HTTP_X_FEATURE_FLAGS' ] ? $_SERVER[ 'HTTP_X_FEATURE_FLAGS' ] : '';
        $_flags[] = getenv( 'WP_FEATURE_FLAGS' ) ? getenv( 'WP_FEATURE_FLAGS' ) : '';
        $_flags[] = defined( 'WP_FEATURE_FLAGS' ) ? WP_FEATURE_FLAGS : '';

        return self::set( implode( ',', array_filter( array_unique( $_flags ))) );

      }


      /**
       * Set Feature Flag
       *
       * @param      $flag
       * @param bool $type
       */
      static public function set( $flag, $type = false ) {

        if( !self::$_initialized ) {
          self::setup();
        }

        foreach( (array) $flag as $_single ) {
          self::$_flags = array_merge( self::$_flags, explode( ',', $type ? $type . '::' . $_single : $_single ) );
        }

        self::$_flags = array_filter( array_unique( self::$_flags ) );

      }

      /**
       * Get Feature Flag
       *
       * @param $flag
       * @param $type
       *
       * @return bool
       */
      static public function get( $flag, $type = false ) {

        if( !self::$_initialized ) {
          self::setup();
        }

        return in_array( $type ? $type . '::' . $flag : $flag, self::$_flags );
      }

    }

  }

}



