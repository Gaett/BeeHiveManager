<?php
class Ruche {
    private $idRuche;
    private $idParent;
    private $rfidRuche;
    private $nbCadres;
    private $nbHausses;
    private $nbAbeilles;
    private $idStatus;
    private $idLocalisation;

    public function __construct ($idRuche,$idParent,$rfidRuche,$nbCadres,$nbHausses,$nbAbeilles,$idStatus,$idLocalisation) {
        $this->idRuche = $idRuche;
        $this->idParent = $idParent;
        $this->rfidRuche = $rfidRuche;
        $this->nbCadres = $nbCadres;
        $this->nbHausses = $nbHausses;
        $this->nbAbeilles = $nbAbeilles;
        $this->idStatus = $idStatus;
        $this->idLocalisation = $idLocalisation;
    }

    public static function getAllRucheFromLocalisationRuche($idLocal) {
        $pdo = myPDO::getInstance();

        $stmt = $pdo->prepare(<<<SQL
            SELECT *
            FROM ruche
            WHERE IDlocalisation = {$idLocal}
        SQL
        );

        $stmt->execute();

        $result = array();

        while (($ligne = $stmt->fetch()) !== false) {
            array_push($result,$ligne);
        }
        return $result;
    }
}
