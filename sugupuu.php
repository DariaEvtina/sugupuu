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
    foreach ($peoples as $parent){
    foreach ($parent->lapsed-inimene as $child){
        if ($child->nimi==$people->nimi){
            return $parent;
        }
    }
    return null;
    }
}
$peoples=getPeopels($xml);
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
</body>
</html>

