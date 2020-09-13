<?php
class StatusTable {
    private $idStatus;
    private $description;

    public function __construct ($id, $description) {
        $this->idStatus = $id;
        $this->description = $description;
    }
}
