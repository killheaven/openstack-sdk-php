<?php
/* ============================================================================
(c) Copyright 2012 Hewlett-Packard Development Company, L.P.
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights to
use, copy, modify, merge,publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE  LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
============================================================================ */
/**
 * @file
 *
 * This file contains the HPCloud::DBaaS::InstanceDetails class.
 */

namespace HPCloud\Services\DBaaS;

class InstanceDetails {

  protected $name;
  protected $id;
  protected $links;
  protected $created;
  protected $status;
  protected $hostname;
  protected $port;

  protected $username;
  protected $password;

  public function newFromJSON($json) {

    fwrite(STDOUT, json_encode($json));

    $o = new InstanceDetails($json['name'], $json['id']);
    $o->links = $json['links'];
    $o->created = $json['created'];
    $o->status = $json['status'];
    if (!empty($json['hostname'])) {
      $o->hostname = $json['hostname'];
      $o->port= !empty($json['port']) ? $json['port'] : '3306';
    }

    if (!empty($json['credential']['username'])) {
      $o->username = $json['credential']['username'];
    }
    if (!empty($json['credential']['password'])) {
      $o->password = $json['credential']['password'];
    }

    return $o;
  }

  public function __construct($name, $id) {
    $this->name = $name;
    $this->id = $id;
  }

  /**
   * Get the name of this instance.
   *
   * @retval string
   *   The name of the instance.
   */
  public function name() {
    return $this->name;
  }

  /**
   * Get the ID of the instance.
   *
   * @retval string
   *   The ID.
   */
  public function id() {
    return $this->id;
  }

  /**
   * Get a string expressing the creation time.
   *
   * @retval string
   *   A string indicating the creation time.
   *   Format is in ISO date format.
   */
  public function createdOn() {
    return $this->created;
  }

  /**
   * Get the status of this instance.
   *
   * This indicates whether or not the service is available, along with other
   * details.
   *
   * Known status messages:
   *- running: Instance is fully operational.
   *- building: Instance is being created.
   *
   * @retval string
   *   A short status message.
   */
  public function status() {
    return $this->status;
  }

  /**
   * Get the hostname.
   *
   * @attention
   * In version 1.0 of the DBaaS protocol, this is ONLY available immediately
   * after creation.
   *
   * This returns the DNS name of the host (or possibly an IP address).
   *
   * @retval string
   *   The FQDN or IP address of the MySQL server.
   */
  public function hostname() {
    return $this->hostname;
  }

  /**
   * The port number of the MySQL server.
   *
   * @retval integer
   *   The port number. If this is empty, the
   *   default (3306) should be assumed.
   */
  public function port() {
    return $this->port;
  }

  /**
   * The username field, if available.
   *
   * @attention
   * Typically this is only available at creation time!
   *
   * @retval string
   *   The username for the MySQL instance.
   */
  public function username() {
    return $this->username;
  }
  /**
   * The password field, if available.
   *
   * This is the password for this instance's MySQL database.
   *
   * @attention
   *   This is only returned when a database is first created.
   *
   * @retval string
   *   A password string.
   */
  public function password() {
    return $this->password;
  }
  /**
   * An array of links about this database.
   *
   * Format:
   * @code
   * <?php
   * array(
   *   0 => array(
   *     "rel" => "self",
   *     "url" => "https://some.long/url",
   *   ),
   * );
   * ?>
   * @endcode
   *
   * At the time of this writing, there is no definition of what URLs may
   * appear here. However, the `self` URL us a URL to the present instance's
   * definition.
   *
   * @retval array
   *   An array of related links to DBaaS URLs.
   */
  public function links() {
    return $this->links;
  }

  /**
   * Get the DSN to connect to the database instance.
   *
   * A convenience function for PDO.
   *
   * @attention
   *   This may only be available immediately after the creation of
   *   the database. The current version of DBaaS does not return
   *   hostname and port in subsequent queries.
   *
   * @see http://us3.php.net/manual/en/ref.pdo-mysql.connection.php
   *
   * @param string $dbName
   *   The name of the database to connect to. If none is specified,
   *   this will be left off of the DSN.
   * @param string $charset
   *   This will attempt to set the character set. Not all versions
   *   of PHP use this.
   *
   * @retval string
   *   The DSN, including driver, host, port, and database name.
   * @todo
   *   At this time, 'mysql' is hard-coded as the driver name. Does this
   *   need to change?
   */
  public function dsn($dbName = NULL, $charset = NULL) {
    $dsn = sprintf('mysql:host=%s;port=%s', $this->hostname(), $this->port());
    if (!empty($dbName)) {
      $dsn .= ';dbname=' . $dbName;
    }
    if (!empty($charset)) {
      $dsn .= ';charset=' . $charset;
    }

    return $dsn;

  }


}
