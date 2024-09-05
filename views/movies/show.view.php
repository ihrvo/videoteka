<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1><?= $movie['naslov'] ?></h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <form class="row g-3 mt-3">
        <div class="col-md-6">
            <label for="naslov" class="form-label">Naslov</label>
            <input type="text" class="form-control" id="naslov" name="naslov" value="<?= $movie['naslov'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="godina" class="form-label">Godina</label>
            <input type="text" class="form-control" id="godina" name="godina" value="<?= $movie['godina'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="zanr_id" class="form-label">Zakasnina po danu</label>
            <input type="text" class="form-control" id="zanr_id" name="zanr_id" value="<?= $movie['zanr'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="cjenik_id" class="form-label">Zakasnina po danu</label>
            <input type="text" class="form-control" id="cjenik_id" name="cjenik_id" value="<?= $movie['tip_filma'] ?>" disabled>
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="<?= $subDir ?>/movies" class="btn btn-primary mb-3">Povratak</a>
            <a href="<?= $subDir ?>/movies/edit?id=<?= $movie['id'] ?>" class="btn btn-info mb-3">Uredi</a>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>