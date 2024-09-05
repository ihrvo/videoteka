<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1><h2>Uredi posudbu ID: <?= $posudbe['pid'] ?></h2></h1>
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
    <form class="row g-3 mt-3" action="<?= $subDir ?>/rentals" method="POST">
        <input type="hidden" name="pid" value="<?= $posudbe['pid'] ?>">
        <input type="hidden" name="_method" value="PATCH">
        <div class="col-md-6">
        <label for="movies" class="form-label ps-1">Film</label>
            <select class="form-select <?= validationClass($errors, 'film_id') ?>" aria-label="Default select example" name="film_id">
                <option>Odaberite film</option>
                <?php foreach ($movies as $movie): 
                    if ($movie['id']===$posudbe['film_id']):?>
                    <option value="<?= $movie['id'] ?>" selected><?= $movie['godina'] . ' - ' . $movie['naslov'] ?></option>
                    <?php else: ?>
                        <option value="<?= $movie['id'] ?>"><?= $movie['godina'] . ' - ' . $movie['naslov'] ?></option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'film_id') ?>
        </div>
        <div class="col-md-6">
            <label for="tip" class="form-label">Medij</label>
            <select class="form-select <?= validationClass($errors, 'medij_id') ?>" aria-label="Default select example" name="medij_id">
                <option>Odaberite tip</option>
                <?php foreach ($mediji as $medij): 
                    if ($medij['tip']===$posudbe['tip']):?>
                    <option value="<?= $medij['id'] ?>" selected><?= $medij['tip'] ?></option>
                    <?php else: ?>
                        <option value="<?= $medij['id'] ?>"><?= $medij['tip']  ?></option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'medij_id') ?>
        </div>
        <div class="col-md-6">
            <label for="clan" class="form-label">Član</label>
            <select class="form-select <?= validationClass($errors, 'clan_id') ?>" aria-label="Default select example" name="clan_id">
                <option>Odaberite člana</option>
                <?php foreach ($clanovi as $clan): 
                    if ($clan['clan']===$posudbe['clan']):?>
                    <option value="<?= $clan['id'] ?>" selected><?= $clan['clan'] ?></option>
                    <?php else: ?>
                        <option value="<?= $clan['id'] ?>"><?= $clan['clan']  ?></option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'clan_id') ?>
        </div>
        <div class="col-md-6">
            <label for="datum_posudbe" class="form-label">Datum posudbe</label>
            <input type="text" class="form-control <?= validationClass($errors, 'datum_posudbe') ?>" id="datum_posudbe" name="datum_posudbe" value="<?= $posudbe['datum_posudbe'] ?>" >
            <?= validationFeedback($errors, 'datum_posudbe') ?>
        </div>
        <div class="col-md-6">
            <label for="datum_povrata" class="form-label <?= validationClass($errors, 'datum_povrata') ?>">Datum povrata</label>
            <input type="text" class="form-control" id="datum_povrata" name="datum_povrata" value="<?= $posudbe['datum_povrata'] ?>" >
            <?= validationFeedback($errors, 'datum_povrata') ?>
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="<?= $subDir ?>/rentals" class="btn btn-primary mb-3">Povratak</a>
            <button type="submit" class="btn btn-success mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>