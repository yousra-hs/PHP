<?php

    class Tranche {
        public $borneMin;
        public $borneMax;
        public $tarif;

        function __construct($bmin, $bmax, $tar){
            $this->borneMin = $bmin;
            $this->borneMax = $bmax;
            $this->tarif = $tar;
        }

        function infos(){
            echo "Borne min: $this->borneMin. Borne max: $this->borneMax. Tarif: $this->tarif";
        }
    }
    $tva = 14;
    $timbre = 0.45;
    $redevance= [
        "small" => 22.65,
        "medium" => 37.05,
        "large" => 46.20
    ];
    
    $tarifs = [
        new Tranche(0, 100, 0.794), 
        new Tranche(101, 150, 0.883),
        new Tranche(151, 210, 0.9451),
        new Tranche(211, 310, 1.0489),
        new Tranche(311, 510, 1.2915),
        new Tranche(511, null, 1.4975)
    ];
    $timber = 0.45;
    $oldIndex;
    $newIndex;
    $consommation;
    $montantsFacture = array(); 
    $montantsHT = array(); 

    if (isset($_POST["submit"])) {
        $oldIndex = $_POST["oldIndex"];
        $newIndex = $_POST["newIndex"];
        $calibre = $_POST["calibre"];
        $consommation = $newIndex - $oldIndex;
        // $consommation <= 150
        if($consommation <= 150) {
            // $consommation <= 100
            if($consommation <= $tarifs[0]->borneMax) {
                $montantsFacture[0] = $consommation;
                $montantsHT[0] = $consommation * $tarifs[0]->tarif;
            }
            // 100 < $consommation <= 150
            else {
                $montantsFacture[0] = 100;
                $montantsFacture[1] = $consommation - $montantsFacture[0];
                $montantsHT[0] = $montantsFacture[0] * $tarifs[0]->tarif;
                $montantsHT[1] = $montantsFacture[1] * $tarifs[1]->tarif;
            }
        }
      
        else {
            if($consommation <= $tarifs[2]->borneMax) {
                $montantsFacture[2] = $consommation;
                $montantsHT[2] = $consommation * $tarifs[2]->tarif;
            }
            else if($consommation <= $tarifs[3]->borneMax) {
                $montantsFacture[3] = $consommation;
                $montantsHT[3] = $consommation * $tarifs[3]->tarif;
            }
            else if($consommation <= $tarifs[4]->borneMax) {
                $montantsFacture[4] = $consommation;
                $montantsHT[4] = $consommation * $tarifs[4]->tarif;
            }
            else{
                $montantsFacture[5] = $consommation;
                $montantsHT[5] = $consommation * $tarifs[5]->tarif;
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>calcul</title>
</head>
<style>
   #calcul{
            background-color: #747cdf;
            text-align: center;
            color: white;
            font-size: 15px;
             border: none;
            border-radius: 5px;
            outline: none;
            cursor: pointer;
           
            font-weight: bold;
        }
       input{
        padding: 7px 3px 7px 20px;
        border: 1px solid #c9c9c9;
        outline-color: #747cdf;
        border-radius: 5px;
       }
       form{
           display: grid;
           grid-template-columns: auto auto auto auto;
           gap: 60px;
           padding: 30px 80px;
       }
       #cal{
    margin-top: 15px;
}

</style>
<body>
<section id="inputs">
<form action="index2.php" method="POST">
    <input type="text" name="oldIndex" placeholder="Old Index">
     <input type="text" name="newIndex" placeholder="New Index" >
     <div>
     <input type="checkbox" id="cal" value="small" name="calibre" >5-15 &nbsp;
        <input type="checkbox" value="medium" name="calibre">15-20 &nbsp;
        <input type="checkbox" value="large" name="calibre">>30 
    </div>
        <input type="submit" id="calcul" value="Calcul" name="submit">
</form>
</section>
   

    <table id="table">
        <?php
        if (isset($_POST["submit"])) {
            foreach($montantsFacture as $key => $value) {

        ?>
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th></th>
                    <th>مفوتر <br><span>Facturé</span></th>
                    <th>س.و<br><span>P.U</span></th>
                    <th>مبلغ د.إ.ر <br><span></span>Montant HT</th>
                    <th>ض.ق.م <br><span></span>Taux TVA</th>
                    <th>مبلغ الرسوم <br><span> </span>Montant Taxes</th>
                    <th><span></span></th>
                </tr>

                <tr>
                    <th>CONSOMMATION ELECTRICITE</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th>ستھلاك الكھرباء</th>
                </tr>
                <tr>
                    <td>TRANCHE</td>
                    <td><?php echo $value ?></td>
                    <td><?php echo $tarifs[$key]->tarif ?></td>
                    <td><?php echo $montantsHT[$key] ?></td>
                    <td><?php echo $tva . "%";?></td>
                    <td><?php echo $montantsHT[$key] * $tva /100 ?></td>
                    <th></th>
                </tr>
                <tr>
                    <th>TAXES POUR LE COMPTE DE L’ETAT</th>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                    <th>   الرسوم المؤداة لفائدة الدولة</th>
                </tr>
                <tr>
                    <th>TOTAL TVA</th>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><?php echo $montantsHT[$key] * $tva /100 ?></td>
                    <th>مجموع ض.ق.م</th>
                </tr>
                <tr>
                    <th>TIMBRE</th>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><?php echo $timber ?></td>
                    <th>الطابع </th>
                </tr>
                <tr>
                    <th>SOUS-TOTAL</th>
                  <td></td>
                  <td></td>
                  <td><?php echo $montantsHT[$key] ?></td>
                  <td></td>
                  <td><?php echo $timber + ($montantsHT[$key] * $tva /100) ?></td>
                    <th>المجموع الجزئي</th>
                </tr>
                <tr>
                    <th>TOTAL ELECTICITE</th>
                  <td></td>
                  <td></td>
                  <th><?php echo $montantsHT[$key] +$timber + ($montantsHT[$key] * $tva /100)  ?></th>
                  <td></td>
                  <td></td>
                    <th>المجموع الجزئي</th>
                </tr>
            </thead>
            </tbody>
        </table>

    </table>

        <?php
            }
        }
        ?>
    </table>
    
</body>
</html>