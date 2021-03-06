<header class="header-blue" style="height: 220px;">
    <nav class="navbar navbar-dark navbar-expand-md navigation-clean-search">
        <div class="container-fluid"><a href="index.php"><img src="assets/img/large_teachfocus.png"></a>
            <!--<div class="dropdown show"><a aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle" href="#">Exercices</a>
                <div class="dropdown-menu "><a class="dropdown-item" href="cours.php">Cours</a></div></div>--> 
           <a class="nav-link" href="cours.php">Cours</a>
            <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav">
                    <?php
                    if (isUserLogged()) {
                        if ($_SESSION["User"]->userType == "enseignant") { ?>
                        <li class="nav-item"><a class="nav-link" href="espaceEnseignant.php">Espace enseignant</a></li>
                    <?php }} ?>
                    <?php
                    if (isUserLogged()) {
                         ?>
                        <li class="nav-item"><a class="nav-link" href="myCourses.php">Mes cours</a></li>
                    <?php } ?>
                </ul>
                <form class="form-inline mr-auto" target="_self">
                    <div class="form-group mb-0"><label for="search-field"></label></div>
                </form><span class="navbar-text"> </span>
                <?php 
        if (!isUserLogged()) { ?>
    <a class="btn btn-light action-button" role="button" href="sign-up.php">S'inscrire</a>
    <a class="btn btn-light action-button" role="button" href="sign-in.php">Connexion</a>
    <?php } else { ?>
    <a class="btn btn-light action-button" role="button" href="index.php?disconnect=true">Déconnexion</a>
    <?php } ?>