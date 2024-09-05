<nav class="p-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="<?= $subDir ?>/" class="nav-link px-2 <?= setActiveClass("<?= $subDir ?>/") ?>" <?= setAriaCurent("$subDir/") ?>>Home</a></li>
                <li><a href="<?= $subDir ?>/dashboard" class="nav-link px-2 <?= setActiveClass("$subDir/dashboard") ?>" <?= setAriaCurent("$subDir/dashboard") ?>>Dashboard</a></li>
                <li><a href="<?= $subDir ?>/rentals" class="nav-link px-2 <?= setActiveClass("$subDir/rentals") ?>" <?= setAriaCurent("$subDir/rentals") ?>>Posudbe</a></li>
                <li><a href="<?= $subDir ?>/members" class="nav-link px-2 <?= setActiveClass("$subDir/members") ?>" <?= setAriaCurent("$subDir/members") ?>>Clanovi</a></li>
                <li><a href="<?= $subDir ?>/genres" class="nav-link px-2 <?= setActiveClass("$subDir/genres") ?>" <?= setAriaCurent("$subDir/genres") ?>>Zanrovi</a></li>
                <li><a href="<?= $subDir ?>/movies" class="nav-link px-2 <?= setActiveClass("$subDir/movies") ?>" <?= setAriaCurent("$subDir/movies") ?>>Filmovi</a></li>
                <li><a href="<?= $subDir ?>/prices" class="nav-link px-2 <?= setActiveClass("$subDir/prices") ?>" <?= setAriaCurent("$subDir/prices") ?>>Cjenik</a></li>
                <li><a href="<?= $subDir ?>/formats" class="nav-link px-2 <?= setActiveClass("$subDir/formats") ?>" <?= setAriaCurent("$subDir/formats") ?>>Mediji</a></li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                <input type="search" class="form-control form-control-dark text-bg-dark" placeholder="Search..." aria-label="Search">
            </form>

            <div class="text-end">
                <?php 
                echo "Pozdrav " . $_SESSION['user']['ime']; ?>
                <a href="<?= $subDir ?>/logout" type="button" class="btn btn-primary me-2">Logout</a>                
            </div>
        </div>
    </div>
</nav>