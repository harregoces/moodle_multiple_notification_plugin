<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Multiple notifications.
 *
 * Notes:
 * - it is required to use the web server account when executing PHP CLI scripts
 * - you need to change the "www-data" to match the apache user account
 * - use "su" if "sudo" not available
 *
 * @package    local_multiple_notifications
 * @copyright 2020 Hernan Arregoces - Arrby
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require(__DIR__.'/../../../config.php');
require_once("$CFG->libdir/clilib.php");
require_once(__DIR__."/../lib.php");

// Now get cli options.
list($options, $unrecognized) = cli_get_params(array('verbose'=>false, 'help'=>false), array('v'=>'verbose', 'h'=>'help'));

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) {
    $help =
        "Execute manual enrolments expiration sync and send notifications.

Options:
-v, --verbose         Print verbose progress information
-h, --help            Print out this help

Example:
\$ sudo -u www-data /usr/bin/php local/multiple_notifications/cli/sync.php
";

    echo $help;
    die;
}

if (empty($options['verbose'])) {
    $trace = new null_progress_trace();
} else {
    $trace = new text_progress_trace();
}

$result = send_multiple_expiry_notifications($trace);

exit($result);