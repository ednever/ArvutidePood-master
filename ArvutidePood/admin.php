<?php
require_once('connect.php');
global $yhendus;
$sorttulp="nimetus";
$otsisona="";

/** Paari kustutamine */
if(isset($_REQUEST['Kustuta'])) {
    $paring = $yhendus->prepare("DELETE FROM tantsud where id=?");
    $paring->bind_param('i', $_REQUEST['Kustuta']);
    $paring->execute();
}

/** Arvuti lisamine */
if(isset($_REQUEST['lisaArvuti'])) {
    $paring = $yhendus->prepare("INSERT INTO arvuti(arvutiNimi, hind, pilt) VALUES (?,?,?)");
    //$paring->bind_param('i', $_REQUEST['lisaArvuti']);
    $paring->execute();
}

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>tantsudTARpv21</title>
    <script>
        function myFunction() {
            let x = document.getElementById("myNavbar");
            if (x.className === "navbar") {
                x.className += " responsive";
            } else {
                x.className = "navbar";
            }
        }
    </script>
</head>
<body>
<header>
    <h1>Arvutid</h1>
    <h2>Admin leht</h2>
    <div class="navbar" id="myNavbar">
        <a href="arvutid.php">Kasutaja leht</a>
        <a href="admin.php">Admin leht</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
    </div>
</header>
<table>
    <tr>
        <th> </th>
        <th>Nimetus</th>
        <th>Hind</th>
        <th>Pilt</th>
    </tr>
    <?php

    $kask = $yhendus->prepare("SELECT arvutiID, arvutiNimi, hind, pilt, avalik FROM arvuti");
    $kask->bind_result($arvutiID, $arvutiNimi, $hind, $pilt, $avalik);
    $kask->execute();
    while ($kask->fetch()) {
        echo "<tr>";
        //echo "<td><a href='?Kustuta=$id'>Kustuta</a></td>";
        $lause = 'Kas ikka soovid kustutada?';
        echo "<td><a href='?Kustuta=$arvutiID' onclick='return confirm(".$lause.")'>Kustuta</a></td>";
        echo "<td>".$arvutiNimi."</td>";
        echo "<td>".$hind."</td>";
        echo "<td><img src='$pilt' alt='pilt' style='height: 300px; width: 300px'></td>";
        echo "</tr>";
    }
    ?>
</table>
<h2>Uue arvuti lisamine</h2>
<form action = '?'>
    <input type="text" placeholder="Nimetus">
    <input type="text" placeholder="Hind">
    <input type="text" placeholder="Pilt">
    <input type="submit" name="lisaArvuti" value="OK">
</form>
</body>
</html>
<style>
    table, tr, td, th {
        text-align: center;
        border: 2pt solid black;
        overflow-x: auto;
    }
    th {
        background-color: aquamarine;
    }

    .navbar {
        background-color: #333;
        overflow: hidden;
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    /* Style the links inside the navigation bar */
    .navbar a {
        float: left;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    /* Change the color of links on hover */
    .navbar a:hover {
        background-color: #4CAF50;
        color: black;
    }

    /* Add a green background color to the active link */
    .navbar a.active {
        background-color: #ddd;
        color: white;
    }

    /* Hide the link that should open and close the navbar on small screens */
    .navbar .icon {
        display: none;
    }

    @media screen and (max-width: 600px) {
        .navbar a:not(:first-child) {display: none;}
        .navbar a.icon {
            float: right;
            display: block;
        }
    }

    /* The "responsive" class is added to the navbar with JavaScript when the user clicks on the icon. This class makes the navbar look good on small screens (display the links vertically instead of horizontally) */
    @media screen and (max-width: 600px) {
        .navbar.responsive a.icon {
            position: absolute;
            right: 0;
            bottom: 0;
        }
        .navbar.responsive a {
            float: none;
            display: block;
            text-align: left;
        }
    }
</style>