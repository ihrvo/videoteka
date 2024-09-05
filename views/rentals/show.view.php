<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1><h2>Posudba ID: <?= $posudbe['id'] ?></h2></h1>
        <?php  $status = 'Posuđen';
        $class = "danger";
                if ($posudbe['datum_povrata']) {$status = 'Vraćen'; $class = "success";} 
                ?>
        <div class="action-buttons">
            <h2>
                <span class="badge text-bg-<?= $class ?>"><?= $status ?></span>
            </h2>
        </div>
    </div>
    <hr>
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
            <?= $message['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <table class="table text-center">
        <tbody><tr>
            <th>Dani posudbe</th>
            <th>Cijena za prvi dan</th>
            <th>Dani kašnjenja</th>
            <th>Zakasnina po danu</th>
            <th>Zakasnina ukupno</th>
            <th>Ukupno dugovanje</th>
        </tr>
        <tr>
            <td><?= $dani_posudbe ?></td>
            <td><?= $posudbe['cijena_tip_filma'] ?></td>
            <td><?= $dani_kasnjenja ?></td>
            <td><?= $posudbe['zakasnina_po_danu'] ?> €</td>
            <td><?= $zakasnina ?> €</td>
            <td><?= $dugovanje ?> €</td>
        </tr>
    </tbody></table>
    <form class="row g-3 mt-3">
        <div class="col-md-6">
            <label for="naslov" class="form-label">Film</label>
            <input type="text" class="form-control" id="naslov" name="naslov" value="<?= $posudbe['naslov'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="tip" class="form-label">Medij</label>
            <input type="text" class="form-control" id="tip" name="tip" value="<?= $posudbe['tip'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="clan" class="form-label">Član</label>
            <input type="text" class="form-control" id="clan" name="clan" value="<?= $posudbe['clan'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="datum_posudbe" class="form-label">Datum posudbe</label>
            <input type="text" class="form-control" id="datum_posudbe" name="datum_posudbe" value="<?= $posudbe['datum_posudbe'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="datum_povrata" class="form-label">Datum povrata</label>
            <input type="text" class="form-control" id="datum_povrata" name="datum_povrata" value="<?= $posudbe['datum_povrata'] ?>" disabled>
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="<?= $subDir ?>/rentals" class="btn btn-primary mb-3">Povratak</a>
            <a href="<?= $subDir ?>/rentals/edit?pid=<?= $posudbe['id'] ?>&kid=<?= $posudbe['kid'] ?>" class="btn btn-info mb-3">Uredi</a>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>