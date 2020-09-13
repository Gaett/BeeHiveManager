<?php
class Mesure {
    private $idMesure;
    private $idRuche;
    private $heureMesure;
    private $poids;
    private $temperature1;
    private $temperature2;
    private $temperature3;
    private $temperature4;
    private $humidite1;
    private $humidite2;
    private $humidite3;
    private $humidite4;

    public function __construct (
         $idMesure,$idRuche,$heureMesure,$poids,$temperature1,$temperature2,
        $temperature3,$temperature4,$humidite1,$humidite2,$humidite3,$humidite4
    ) {
        $this->idMesure = $idMesure;
        $this->idRuche = $idRuche;
        $this->heureMesure = $heureMesure;
        $this->poids = $poids;
        $this->temperature1 = $temperature1;
        $this->temperature2 = $temperature2;
        $this->temperature3 = $temperature3;
        $this->temperature4 = $temperature4;
        $this->humidite1 = $humidite1;
        $this->humidite2 = $humidite2;
        $this->humidite3 = $humidite3;
        $this->humidite4 = $humidite4;
    }

    public static function getMesureFromRuche($idRuche) {
        $pdo = myPDO::getInstance();

        $stmt = $pdo->prepare(<<<SQL
            SELECT *
            FROM mesure
            WHERE IDruche = {$idRuche}
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
