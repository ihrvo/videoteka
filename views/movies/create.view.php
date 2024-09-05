<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Dodaj novi film</h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/movies" method="POST">
        <div class="col-md-6">
            <label for="title" class="form-label <?= validationClass($errors, 'naslov') ?>">Naslov</label>
            <input type="text" class="form-control" id="title" name="naslov" placeholder="Naslov" required>
            <?= validationFeedback($errors, 'naslov') ?>
        </div>
        <div class="col-md-6">
            <label for="year" class="form-label <?= validationClass($errors, 'godina') ?>">Godina</label>
            <input type="text" class="form-control" id="year" name="godina" placeholder="Godina" required>
            <?= validationFeedback($errors, 'godina') ?>
        </div>
        <div class="col-md-6">
            <label for="movie_year" class="form-label <?= validationClass($errors, 'zanr_id') ?> ps-1">Žanr</label>
            <select class="form-select" aria-label="Default select example" name="zanr_id">
                <option selected>Odaberite zanr</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id'] ?>"><?= $genre['ime'] ?></option>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'zanr_id') ?>
        </div>
        <div class="col-md-6">
            <label for="movie_year" class="form-label <?= validationClass($errors, 'cjenik_id') ?> ps-1">Cjenik</label>
            <select class="form-select" aria-label="Default select example" name="cjenik_id">
                <option selected>Odaberite cijenu</option>
                <?php foreach ($prices as $priceItem): ?>
                    <option value="<?= $priceItem['id'] ?>"><?= $priceItem['tip_filma'] . " - Cijena " .  $priceItem['cijena'] . " EUR - Zakasnina " . $priceItem['zakasnina_po_danu'] . " EUR"?></option>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'cjenik_id') ?>
        </div>
    <h3>Unesi broj kopija filma po mediju</h3>
        <?php
        foreach ($mediji as $medij): ?>
        <div class="col-md-<?= $columns ?>">
            <label for="<?= $medij['tip'] ?>" class="form-label <?= validationClass($errors, 'naslov') ?>"><?= $medij['tip'] ?> kolicina</label>
            <input type="number" class="form-control " id="<?= $medij['tip'] ?>" name="<?= $medij['tip'] ?>" placeholder="<?= $medij['tip'] ?> kolicina" value="0">
        </div>
        <?php endforeach ?>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>