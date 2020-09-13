<?php
require_once 'myPDO.include.php';

class LocalisationRuches {
    private $idLocalisation;
    private $localisation;

    public function __construct ($idLocalisation, $localisation) {
        $this->idLocalisation = $idLocalisation;
        $this->localisation = $localisation;
    }

    public static function getAllLocalisation() {
        $pdo = myPDO::getInstance();

        $stmt = $pdo->prepare(<<<SQL
            SELECT *
            FROM localisationruches
        SQL
        ) ;

        $stmt->execute();

        $result = array();

        while (($ligne = $stmt->fetch()) !== false) {
            array_push($result,$ligne);
        }
        return $result;
    }
}
