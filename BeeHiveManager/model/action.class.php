<?php
class Action {
    private $idAction;
    private $description;

    public function __construct ($id, $description) {
        $this->idAction = $id;
        $this->description = $description;
    }

    public function getIdAction() {
        return $this->idAction;
    }

    public function getDescription() {
        return $this->description;
    }

    public static function getActionNameFromIdAction($idAction) {
        $pdo = myPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
            SELECT description
            FROM action
            WHERE IDaction = {$idAction}
        SQL
        );

        $stmt->execute();

        return $ligne = $stmt->fetch();
    }
}
