<?php
$xml=simplexml_load_file("sugupuu.xml");
//выводить из массива getChildrens
function getPeopels($xml){
    $array=getChildrens($xml);
    return $array;
}
//выводить данные детей
function getChildrens($people){
    $result=array($people);
    $children=$people->lapsed->inimene;
    if (empty($children)) {
        return $result;
    }
    foreach ($children as $child){
        $array=getChildrens($child);
        $result=array_merge($result,$array);
    }
    return $result;
}
function getParent($peoples,$people){
if ($people==null) return null;
    foreach ($peoples as $parent){
        if (!hasChildren($parent))  continue;
    foreach ($parent->lapsed->inimene as $child){
        if ($child->nimi==$people->nimi){
            return $parent;
        }
    }
    return null;
    }
}
$peoples=getPeopels($xml);
function hasChildren($people){
    return !empty($people->lapsed->inimene);
}
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
<table border="1">
    <tr>
        <th>Vanema vanem</th>
        <th>Vanem</th>
        <th>laps</th>
        <th>Sünniaasta</th>
        <th>Vanus</th>
    </tr>
    <?php
    foreach ($peoples as $people){
        $parent=getParent($peoples,$people);
        if (empty($parent)) continue;
        $parentOfparent=getParent($peoples,$parent);
        echo '<tr>';
        if (empty($parentOfparent)){
            echo '<td bgcolor="yellow">puudub</td>';
        }
        else

            echo '<td>'.$parentOfparent->nimi.'</td>';
            echo '<td>'.$parent->nimi.'</td>';
            echo '<td>'.$people->nimi.'</td>';
            echo '<td>'.$people->attributes()->synd.'</td>';
        $yearNow=date("Y");
        $childrenYear=$people->attributes()->synd;
            echo '<td>'.($yearNow - $childrenYear).'</td>';
        echo '</tr>';
    }
    ?>
</table>
</body>
</html>

