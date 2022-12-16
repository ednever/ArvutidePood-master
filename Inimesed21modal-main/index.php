<?php
require("conf.php");
require("functions.php");
require("login.php");
session_start();
if (!isset($_SESSION["error"])){
    $_SESSION["error"] = "";
}
if (!isset($_SESSION["admin"])){
    $_SESSION["admin"] = false;
}
if (isset($_REQUEST['knimi']) && isset($_REQUEST['psw'])){
    login($_POST['knimi'], $_POST['psw']);
}
$sort = "eesnimi";
$search_term = "";
if(isset($_REQUEST["sort"])) {
    $sort = $_REQUEST["sort"];
}
if(isset($_REQUEST["inimese_lisamine"]) && isset($_SESSION["unimi"])) {
    // ei saa lisada tühja või tühikuga eesnimi ja perenimi
    if(!empty(trim($_REQUEST["eesnimi"])) && !empty(trim($_REQUEST["perekonnanimi"])) && !empty(trim($_REQUEST["hind"]))){
        addPerson($_REQUEST["eesnimi"], $_REQUEST["perekonnanimi"], $_REQUEST["hind"]);
        header("Location: index.php");
        exit();
    }
}
if(isset($_REQUEST["delete"]) && isAdmin()) {
    deletePerson($_REQUEST["delete"]);
}
if(isset($_REQUEST["save"])) {
    savePerson($_REQUEST["changed_id"], $_REQUEST["eesnimi"], $_REQUEST["perekonnanimi"], $_REQUEST["hind"]);
}
$people = countryData($sort);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="modal.css">
    <title>Arvutite pood</title>
</head>
<body>
<header class="header">
    <div id="menuArea" style="position:absolute; top:0px ;left:25px;">
        <?php
        if (isset($_SESSION["unimi"])){
            ?>
            <h2> <?="$_SESSION[unimi]"?> on sisse logitud</h2>
            <a href="logout2.php">Logi välja</a>
            <?php
        }
        ?>
    </div>
    <?php
    if (!isset($_SESSION["unimi"])){
        ?>
        <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;position:absolute; top:25px ;left:25px;">Logi Sisse</button>
        <?php
    }
    ?>
<!--    Sisse logimine-->
    <div id="id01" class="modal">
        <form class="modal-content animate" action="" method="post">
            <div class="imgcontainer">
                <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Login">&times;</span>
            </div>
            <div class="container">
                <label for="knimi"><b>Kasutajanimi</b></label>
                <input type="text" placeholder="Kasutajanimi" name="knimi" id="knimi" required>
                <label for="psw"><b>Parool</b></label>
                <input type="password" placeholder="Parool" name="psw" id="psw" required>
                <input class="modal-submit" type="submit" value="Logi Sisse"><button type="button" class="modal-submit" onclick="document.getElementById('id01').style.display='none'">Tühista</button>
            </div>
            <div class="container" style="background-color:#f1f1f1">
            </div>
        </form>
    </div>
</header>
<main class="main">
    <?php if(isset($_REQUEST["edit"]) && isAdmin()): ?>
        <?php foreach($people as $person): ?>
            <?php if($person->id == intval($_REQUEST["edit"])): ?>
                <div class="container">
                    <form action="index.php">
                        <input type="hidden" name="changed_id" value="<?=$person->id ?>" />
                        <input type="text" name="eesnimi" value="<?=$person->eesnimi?>">
                        <input type="text" name="perekonnanimi" value="<?=$person->perekonnanimi?>">
                        <input type="text" name="hind" value="<?=$person->hind?>">
                        <a title="Katkesta muutmine" class="cancelBtn" href="index.php" name="cancel">X</a>
                        <input type="submit" name="save" value="&#10004;">
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="container">
        <table>
            <thead>
            <tr>
                <th>Nimetus</th>
                <th>Pilt</th>
                <th>Hind</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($people as $person): ?>
                <tr>
                    <td><?=$person->eesnimi ?></td>
                    <td><img src="<?=$person->perekonnanimi ?>" alt="pilt" style="width: 150px; height: 150px"></td>
                    <td><?=$person->hind ?></td>
                    <?php if(isAdmin()){ ?>
                    <td>
                        <a title="Kustuta inimene" class="deleteBtn" href="index.php?delete=<?=$person->id?>"
                           onclick="return confirm('Oled kindel, et soovid kustutada?');">Kustuta</a>
                        <a title="Muuda inimest" class="editBtn" href="index.php?edit=<?=$person->id?>">Muuda</a>
                    </td>
                    <?php }?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        if (isset($_SESSION["unimi"])){

            ?>
        <form action="index.php">
            <h2>Arvuti lisamine:</h2>
            <dl>
                <dt>Nimetus:</dt>
                <dd><input type="text" name="eesnimi" placeholder="Sisesta nimetus..."></dd>
                <dt>Pilt:</dt>
                <dd><input type="text" name="perekonnanimi" placeholder="Sisesta pildi aadress..."></dd>
                <dt>Hind:</dt>
                <dd><input type="text" name="hind" placeholder="Sisesta hind..."></dd>
                <input type="submit" name="inimese_lisamine" value="Lisa">
            </dl>
        </form>
        <?php echo ($error??"")."<br>"; }?>
    </div>
</main>
</body>
</html>