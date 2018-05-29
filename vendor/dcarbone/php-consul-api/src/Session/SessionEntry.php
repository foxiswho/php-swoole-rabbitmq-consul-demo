<?php namespace DCarbone\PHPConsulAPI\Session;

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
 * Class SessionEntry
 * @package DCarbone\PHPConsulAPI\Session
 */
class SessionEntry extends AbstractModel {
    /** @var int */
    public $CreateIndex = 0;
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Node = '';
    /** @var string[] */
    public $Checks = [];
    /** @var int */
    public $LockDelay = 0;
    /** @var string */
    public $Behavior = '';
    /** @var string */
    public $TTL = '';

    /**
     * @return int
     */
    public function getCreateIndex() {
        return $this->CreateIndex;
    }

    /**
     * @return string
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->Name;
    }

    /**
     * @return string
     */
    public function getNode() {
        return $this->Node;
    }

    /**
     * @return string[]
     */
    public function getChecks() {
        return $this->Checks;
    }

    /**
     * @return int
     */
    public function getLockDelay() {
        return $this->LockDelay;
    }

    /**
     * @return string
     */
    public function getBehavior() {
        return $this->Behavior;
    }

    /**
     * @return string
     */
    public function getTTL() {
        return $this->TTL;
    }
}