<?php include_once base_path('views/partials/header.php'); ?>
<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <h1><?= $genre['ime'] ?></h1>
    <hr>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/genre-create.php" method="POST">
        <div class="row">
            <div class="col-2">
                <label for="zanr" class="mt-1">Id Zanra:</label>
            </div>
            <div class="col-6">
                <input type="text" class="form-control" id="zanr" name="zanr" value="<?= $genre['id'] ?>" disabled>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-2">
                <label for="zanr" class="mt-1">Naziv Zanra:</label>
            </div>
            <div class="col-6">
                <input type="text" class="form-control" id="zanr" name="zanr" value="<?= $genre['ime'] ?>" disabled>
            </div>
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="<?= $subDir ?>/genres" class="btn btn-primary mb-3">Povratak</a>
            <a href="<?= $subDir ?>/genres/edit?id=<?= $genre['id'] ?>" class="btn btn-info mb-3">Uredi</a>
        </div>
    </form>
    <h3>Filmovi u žanru <?= $genre['ime'] ?></h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Naslov</th>
                <th>Godina</th>
                <th>Tip filma</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movies as $film): ?>
                    <td><?= $film['id'] ?></td>
                    <td><?= $film['naslov'] ?></td>
                    <td><?= $film['godina'] ?></td>
                    <td><?= $film['tip_filma'] ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>
