<?php namespace DCarbone\PHPConsulAPI\Coordinate;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class CoordinateEntry
 * @package DCarbone\PHPConsulAPI\Coordinate
 */
class CoordinateEntry extends AbstractModel {
    /** @var string */
    public $Node = '';
    /** @var \DCarbone\PHPConsulAPI\Coordinate\Coordinate */
    public $Coord = null;

    /**
     * CoordinateEntry constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);

        if (null !== $this->Coord && !($this->Coord instanceof Coordinate)) {
            $this->Coord = new Coordinate((array)$this->Coord);
        }
    }

    /**
     * @return string
     */
    public function getNode() {
        return $this->Node;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Coordinate\Coordinate
     */
    public function getCoord() {
        return $this->Coord;
    }
}