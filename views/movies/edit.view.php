<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Uredi <i><?= $movie['naslov'] ?></i></h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/movies" method="POST">
    <input type="hidden" name="_method" value="PATCH">
    <input type="hidden" name="id" value="<?= $movie['id'] ?>">
        <div class="col-md-6">
            <label for="naslov" class="form-label <?= validationClass($errors, 'naslov') ?>">Naslov</label>
            <input type="text" class="form-control" id="naslov" name="naslov" value="<?= $movie['naslov'] ?>" required>
            <?= validationFeedback($errors, 'naslov') ?>
        </div>
        <div class="col-md-6">
            <label for="godina" class="form-label <?= validationClass($errors, 'godina') ?>">Godina</label>
            <input type="text" class="form-control" id="godina" name="godina" value="<?= $movie['godina'] ?>" required>
            <?= validationFeedback($errors, 'godina') ?>
        </div>
        <div class="col">
            <label for="movie_year" class="form-label <?= validationClass($errors, 'zanr_id') ?> ps-1">Žanr</label>
            <select class="form-select" aria-label="Default select example" name="zanr_id">
                <option>Odaberite zanr</option>
                <?php foreach ($genres as $genre): 
                    if ($movie['zanr']===$genre['ime']):?>
                    <option value="<?= $genre['id'] ?>" selected><?= $genre['ime'] ?></option>
                    <?php else: ?>
                    <option value="<?= $genre['id'] ?>"><?= $genre['ime'] ?></option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'zanr_id') ?>
        </div>
        <div class="col">
            <label for="movie_year" class="form-label <?= validationClass($errors, 'cjenik_id') ?> ps-1">Cjenik</label>
            <select class="form-select" aria-label="Default select example" name="cjenik_id">
                <option>Odaberite cijenu</option>
                <?php foreach ($prices as $priceItem): 
                    if ($movie['tip_filma']===$priceItem['tip_filma']):?>
                    <option value="<?= $priceItem['id'] ?>" selected><?= $priceItem['tip_filma'] . " - Cijena " .  $priceItem['cijena'] . " EUR - Zakasnina " . $priceItem['zakasnina_po_danu'] . " EUR"?></option>
                    <?php else: ?>
                        <option value="<?= $priceItem['id'] ?>"><?= $priceItem['tip_filma'] . " - Cijena " .  $priceItem['cijena'] . " EUR - Zakasnina " . $priceItem['zakasnina_po_danu'] . " EUR"?></option>
                        <?php endif ?>               
                        <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'cjenik_id') ?>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>