<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <h1>Uredi <?= $genre['ime'] ?></h1>
    <hr>

    <form class="row g-3 mt-3" action="<?= $subDir ?>/genres" method="POST">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="id" value="<?= $genre['id'] ?>">
        <div class="col-auto">
            <label for="zanr" class="mt-1">Naziv Zanra</label>
        </div>
        <div class="col-6">
            <input type="text" class="form-control <?= validationClass($errors, 'ime') ?>" id="ime" name="ime" value="<?= $genre['ime'] ?>">
            <?= validationFeedback($errors, 'ime') ?>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Spremi</button>
        </div>
    </form>

</main>

<?php include_once base_path('views/partials/footer.php'); ?>