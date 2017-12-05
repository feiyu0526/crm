<?php
/**
 * iCalcreator, a PHP rfc2445/rfc5545 solution.
 *
 * This file is a part of iCalcreator.
 *
 * Copyright (c) 2007-2017 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * Link      http://kigkonsult.se/iCalcreator/index.php
 * Package   iCalcreator
 * Version   2.24
 * License   Subject matter of licence is the software iCalcreator.
 *           The above copyright, link, package and version notices,
 *           this licence notice and the [rfc5545] PRODID as implemented and
 *           invoked in iCalcreator shall be included in all copies or
 *           substantial portions of the iCalcreator.
 *           iCalcreator can be used either under the terms of
 *           a proprietary license, available at <https://kigkonsult.se/>
 *           or the GNU Affero General Public License, version 3:
 *           iCalcreator is free software: you can redistribute it and/or
 *           modify it under the terms of the GNU Affero General Public License
 *           as published by the Free Software Foundation, either version 3 of
 *           the License, or (at your option) any later version.
 *           iCalcreator is distributed in the hope that it will be useful,
 *           but WITHOUT ANY WARRANTY; without even the implied warranty of
 *           MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *           GNU Affero General Public License for more details.
 *           You should have received a copy of the GNU Affero General Public
 *           License along with this program.
 *           If not, see <http://www.gnu.org/licenses/>.
 */
namespace kigkonsult\iCalcreator\traits;
use kigkonsult\iCalcreator\util\util;
use kigkonsult\iCalcreator\util\utilRecur;
/**
 * RRULE property functions
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 2.22.23 - 2017-04-03
 */
trait RRULEtrait {
/**
 * @var array component property RRULE value
 * @access protected
 */
  protected $rrule = null;
/**
 * Return formatted output for calendar component property rrule
 *
 * @return string
 */
  public function createRrule() {
    return utilRecur::formatRecur( util::$RRULE,
                                   $this->rrule,
                                   $this->getConfig( util::$ALLOWEMPTY ));
  }
/**
 * Set calendar component property rrule
 *
 * @param array    $rruleset
 * @param array    $params
 * @param integer  $index
 */
  public function setRrule( $rruleset, $params=null, $index=null ) {
    if( empty( $rruleset )) {
      if( $this->getConfig( util::$ALLOWEMPTY ))
        $rruleset = util::$EMPTYPROPERTY;
      else
        return false;
    }
    util::setMval( $this->rrule,
                   utilRecur::setRexrule( $rruleset ),
                   $params,
                   false,
                   $index );
    return true;
  }
}
