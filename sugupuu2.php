<?php
$xml=simplexml_load_file("sugupuu.xml");
// väljastab massivist getChildrens
function getPeoples($xml){
    $array=getChildrens($xml);
    return $array;
}
// väljastab  laste andmed https://pastebin.com/cgYcM6DJ
function getChildrens($people){
    $result=array($people);
    $childs=$people -> lapsed -> inimene;

    if(empty($childs))
        return $result;

    foreach ($childs as $child){
        $array=getChildrens($child);
        $result=array_merge($result, $array);

    }
    return $result;
}
function getParent($peoples, $people){
    if($people== null) return null;
    foreach ($peoples as $parent){
        if(!hasChilds($parent)) continue;

        foreach ($parent->lapsed->inimene as $child){
            if($child->nimi == $people->nimi){
                return $parent;
            }
        }
    }
    return null;
}
function hasChilds($people){
    return !empty($people -> lapsed -> inimene);
}
// Otsing vanema nimi järgi

function searchByParentName($searchWord){
    global $peoples;
    $result=array();
    foreach($peoples as $people){
        $parent=getParent($peoples, $people);
        if (empty($parent)) continue;
        if(substr(strtolower($parent->nimi), 0,
                strlen($searchWord))==strtolower($searchWord)){
            array_push($result, $people);
        }
    }
    return $result;
}
// otsing lapsi nimi järgi

function searchByChildName($searchWord){
    global $peoples;
    $result=array();
    foreach($peoples as $people){
        //$parent=getParent($peoples, $people);
        //if (empty($parent)) continue;
        if(substr(strtolower($people->nimi), 0,
                strlen($searchWord))==strtolower($searchWord)){
            array_push($result, $people);
        }
    }
    return $result;
}

$peoples=getPeoples($xml);

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Sugupuu ülesandeid</title>
</head>
<body>
<h1>Elizabeth II sugupuu ülesandeid</h1>
<h2>Trüki välja kõikide inimeste sünniaastad / Вывести все года рождения людей /</h2>
<?php
foreach ($peoples as $people){
    echo $people->attributes()->synd.', ';
}
?>
<hr></hr>
<h2>Väljastatakse nimed, kel on vähemalt kaks last / Вывести все имена, у кого мин 2 ребенка /</h2>
<?php
foreach ($peoples as $people){
    $lapsed=$people->lapsed->inimene;
    if (empty($lapsed)) continue;
    if (count($lapsed)>1){
        echo $people->nimi.' - '.count($lapsed).' last<br>';
    }
}
?>
<hr></hr>
<h2> Väljasta sugupuus leiduvad andmed tabelina / вывести родословеную в виде таблицы /</h2>
<form action="?" method="post">
    <input type="radio" name="searchBy" value="parentName" id="parentName">
    <label for="parentName">Vanema nimi</label>
    <br>
    <input type="radio" name="searchBy" value="childName" id="childName" checked>
    <label for="childName">Lapse nimi</label>
    <br>
    <input type="text" name="search" placeholder="nimi">
    <button>OK</button>
</form>


<table border="1">
    <tr>
        <th>Vanema vanem</th>
        <th>Vanem</th>
        <th>Laps</th>
        <th>Sünniaasta</th>
        <th>Vanus</th>
    </tr>
    <?php
    if(!empty($_POST["search"])){
        $radiobutton=$_POST["searchBy"];
        if($radiobutton== "parentName"){
            $result=searchByParentName($_POST["search"]);
        } else if($radiobutton== "childName"){
            $result=searchByChildName($_POST["search"]);
        }
        // sama tabel
        foreach ($result as $people) {
            $parent = getParent($peoples, $people);
            if (empty($parent)) continue;

            $parentOfParent = getParent($peoples, $parent);

            echo '<tr>';
            if (empty($parentOfParent)) {
                echo '<td bgcolor="yellow">puudub</td>';
            } else
                echo '<td>' . $parentOfParent->nimi . '</td>';
            echo '<td>' . $parent->nimi . '</td>';
            echo '<td>' . $people->nimi . '</td>';
            echo '<td>' . $people->attributes()->synd . '</td>';

            $yearNow = (int)date("Y"); //2022
            $childrenYear = (int)$people->attributes()->synd;
            echo '<td>' . (int)($yearNow - $childrenYear) . '</td>';
            echo '</tr>';

        }

    } else {

        // inimese tabelis mis on olemas - KÕIK inimesed
        foreach ($peoples as $people) {
            $parent = getParent($peoples, $people);
            if (empty($parent)) continue;

            $parentOfParent = getParent($peoples, $parent);

            echo '<tr>';
            if (empty($parentOfParent)) {
                echo '<td bgcolor="yellow">puudub</td>';
            } else
                echo '<td>' . $parentOfParent->nimi . '</td>';
            echo '<td>' . $parent->nimi . '</td>';
            echo '<td>' . $people->nimi . '</td>';
            echo '<td>' . $people->attributes()->synd . '</td>';

            $yearNow = (int)date("Y"); //2022
            $childrenYear = (int)$people->attributes()->synd;
            echo '<td>' . (int)($yearNow - $childrenYear) . '</td>';


            echo '</tr>';

        }
    }
    ?>


</table>

</body>
</html>
