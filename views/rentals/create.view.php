<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Dodaj novu posudbu</h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
            <?= $message['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/rentals" method="POST">
        <div class="col-md-4">
            <label for="movies" class="form-label ps-1">Filmovi</label>
            <select class="form-select <?= validationClass($errors, 'film_id') ?>" aria-label="Default select example" name="film_id">
                <option value="">Odaberite film</option>
                <?php foreach ($movies as $movie): ?>
                    <option value="<?= $movie['id'] ?>"><?= $movie['godina'] . ' - ' . $movie['naslov'] ?></option>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'film_id') ?>
        </div>
        <div class="col-md-4">
            <label for="mediji" class="form-label ps-1">Medij</label>
            <select class="form-select <?= validationClass($errors, 'medij_id') ?>" aria-label="Default select example" name="medij_id">
                <option value="">Odaberite medij</option>
                <?php foreach ($mediji as $medij): ?>
                    <option value="<?= $medij['id'] ?>"><?= $medij['tip'] ?></option>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'medij_id') ?>
        </div>
        <div class="col-md-4">
            <label for="clanovi" class="form-label ps-1">Član</label>
            <select class="form-select <?= validationClass($errors, 'clan_id') ?>" aria-label="Default select example" name="clan_id">
                <option value="">Odaberite člana</option>
                <?php foreach ($clanovi as $clan): ?>
                    <option value="<?= $clan['id'] ?>"><?= $clan['clanski_broj'] . ' - ' . $clan['ime'] . ' ' . $clan['prezime'] ?></option>
                <?php endforeach ?>
            </select>
            <?= validationFeedback($errors, 'clan_id') ?>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-success mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>