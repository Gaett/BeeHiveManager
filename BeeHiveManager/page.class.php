<?php
require_once 'myPDO.include.php';
require_once 'model/ruche.class.php';
require_once 'model/action.class.php';
require_once 'model/actionsurruche.class.php';
require_once 'model/localisationruches.class.php';
require_once 'model/mesure.class.php';
require_once 'model/statustable.class.php';

//LocalisationRuches::getAllLocalisation();
//Ruche::getRucheFromLocalisationRuche(0);
//Mesure::getMesureFromRuche(2);

class Page {
    function generateOptionFromLocalisation() {
        $locals = LocalisationRuches::getAllLocalisation();
        $options= "";
        foreach ($locals as $local){
            $options .= "<option value=\"{$local['IDlocalisation']}\">{$local['Localisation']}</option>";
        }
        return $options;
    }

    function createChartZone($idRuche) {
        $html = (<<<HTML
            <div class="columns">
                <div class="column col-9">
                    <canvas id="chart{$idRuche}" class="canvas"></canvas>
                </div>
                <div class="column col-3">
                    <table>
                        <tr>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>

    HTML
    );

// ici on va générer les TD
    $actions = ActionSurRuche::getActionFromRuche($idRuche);
    foreach ($actions as $action) {
        $description = Action::getActionNameFromIdAction($action['IDaction'])['description'];
    $html .= (<<<HTML
    <tr>
        <td>{$action['HeureAction']}</td>
        <td>{$description}</td>
    </tr>
HTML
);
    }

        $html .= (<<<HTML
                    </table>
                </div>
            </div>
    HTML
    );
        return $html;
    }

    function createChartDataFromRuche($ruche) {
        $id = $ruche['IDruche'];
        $mesures = Mesure::getMesureFromRuche($id);
        $humidite = array();
        $temperature = array();
        $poids = array();
        $dates = array();
        foreach ($mesures as $mesure) {
            array_push($humidite, $mesure['Humidite1']);
            array_push($temperature, $mesure['Temperature1']);
            array_push($poids, $mesure['Poids']/1000);
            array_push($dates, $mesure['HeureMesure']);
        }
        $humidite = json_encode($humidite);
        $temperature = json_encode($temperature);
        $poids = json_encode($poids);
        $dates = json_encode($dates);

        $html = (<<<HTML
        var ctx{$id} = document.getElementById('chart{$id}').getContext('2d');
        var datas_humidite{$id} = new Datas({$humidite}, "Humidite", "#3e95cd", false);
        var datas_temperature{$id} = new Datas({$temperature}, "Temperature", "#8e5ea2", false);
        var datas_poids{$id} = new Datas({$poids}, "Poids", "#3cba9f", false);
        var dates = {$dates};
/**
        var datah{$id} = new ChartDatas(dates, [datas_humidite{$id}]);
        var datat{$id} = new ChartDatas(dates, [datas_temperature{$id}]);
        var datap{$id} = new ChartDatas(dates, [datas_poids{$id}]);*/

        var data{$id} = new ChartDatas(dates, [datas_poids{$id},datas_temperature{$id},datas_humidite{$id}]);

        newChart(ctx{$id}, data{$id}, description);
HTML
);
        return $html;
    }

    function generateAllChartFromLocal($idLocal) {
        $ruches = Ruche::getAllRucheFromLocalisationRuche($idLocal);
        $chartZone = "";
        foreach ($ruches as $ruche){
            $chartZone .= $this->createChartZone($ruche['IDruche']);
        }
        return $chartZone;
    }

    function generateHead() {
        $html = (<<<HTML
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="utf-8">
            <title>Mes ruchers</title>
            <link rel="stylesheet" href="dist/spectre.min.css">
            <link rel="stylesheet" href="dist/spectre-icons.min.css">
            <link rel="stylesheet" href="dist/spectre-exp.min.css">
            <script type="text/javascript" src="dist/Chart.bundle.js"></script>
            <script type="text/javascript" src="dist/Chart.bundle.min.js"></script>
            <style>
                .btn-link:hover {
                    background-color: #4CAF50;
                    /* Green */
                    color: white;
                }

                .column {
                    padding-top: 3cm;
                }

                .canvas {
                    width: 100% !important;
                    height: 80% !important;
                }

                td {
                    border-bottom: 1px solid black;
                    border-collapse : separate;
 border-spacing : 10px;
                }

                header {
                    display: flex;
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    background-color: #3b4351;
                }

                table {
                    border: 1px solid black;
                }
            </style>
        </head>
HTML
);
    return $html;
    }

    function generateBody($options,$charts) {
        $html = (<<<HTML
        <body>
            <div class="section bg-gray">
                <header class="navbar">
                    <section class="navbar-section">
                        <div class="input-group input-inline">
                            <div class="form-group">
                                <form method="get">
                                    <select class="form-select" action="index.php" name="local">
            {$options}
                                    </select>
                                    <button type="submit" class="btn btn-primary input-group-btn">Valider</button>
                                </form>
                            </div>
                        </div>
                    </section>
                    <section class="navbar-center">
                        <figure class="avatar avatar-xl">
                            <img src="img/logo.jpg" / alt="bee">
                        </figure>
                    </section>
                    <section class="navbar-section">
                        <!-- section vide pour centrer l'image -->
                        <!-- <div class="input-group input-inline">
                            <div class="btn-group btn-group-block">
                                <button class="btn">Poids</button>
                                <button class="btn">Humidité</button>
                                <button class="btn">Température</button>
                            </div>
                        </div> -->
                    </section>
                </header>
            </div>
            <div class="container">
        {$charts}
            </div>
        </body>
HTML
);
    return $html;
    }

    function generateScriptAndEnd($idLocal) {
        $ruches = Ruche::getAllRucheFromLocalisationRuche($idLocal);
        $datas = "";
        foreach ($ruches as $ruche){
            $datas .= $this->createChartDataFromRuche($ruche);
        }
        $html = (<<<HTML
    <script>
        var description = 'Graphique de surveillance des données';
        /** Datas class is used to represent a set of datas
         * data : array of value on Y axis
         * label : name of values set
         * borderColor : color of this set
         * fill : is the area under the line is colored or not
         */
        class Datas {
            constructor(data, label, borderColor, fill) {
                this.data = data;
                this.label = label;
                this.borderColor = borderColor;
                this.fill = fill;
            }
        }


        /** ChartDatas class is used to represent a set of datas according to a defined template by ChartJs
         * labels = arrays of things used to set the number of values on the X axis
         * dataset = object from Datas class
         */
        class ChartDatas {
            constructor(labels, datas) {
                this.labels = labels;
                this.datasets = datas;
            }
        }
        function newChart(context, datas, description) {
            new Chart(context, {
                type: 'line',
                data: datas,
                options: {
                    title: {
                        display: true,
                        text: description
                    }
                }
            });
        }
        {$datas}
    </script>

    </html>
HTML
        );
        return $html;
    }
}
