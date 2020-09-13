<?php
class ActionSurRuche {
    private $idRuche;
    private $idAction;
    private $idTarget;
    private $heureAction;
    private $note;

    public function __construct ($idRuche, $idAction, $idTarget, $heureAction, $note) {
        $this->idRuche = $idRuche;
        $this->idAction = $idAction;
        $this->idTarget = $idTarget;
        $this->heureAction = $heureAction;
        $this->note = $note;
    }

    public static function getActionFromRuche($idRuche) {
        $pdo = myPDO::getInstance();

        $stmt = $pdo->prepare(<<<SQL
            SELECT *
            FROM actionsurruche
            WHERE IDruche = {$idRuche}
        SQL
        );

        try {
            $stmt->execute();
        } catch (Exception $e) {
            //au pire y a pas d'entrée pour l'id...
        }

        $result = array();

        while (($ligne = $stmt->fetch()) !== false) {
            array_push($result,$ligne);
        }
        return $result;
    }

}
