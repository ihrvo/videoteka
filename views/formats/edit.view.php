<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Uredi <?= $medij['tip'] ?></h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/formats" method="POST">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="id" value="<?= $medij['id'] ?>">
        <div class="col-md-6">
            <label for="tip" class="form-label">tip</label>
            <input type="text" class="form-control <?= validationClass($errors, 'tip') ?>" id="tip" name="tip" value="<?= $medij['tip'] ?>" required>
            <?= validationFeedback($errors, 'tip') ?>
        </div>
        <div class="col-md-6">
            <label for="koeficijent" class="form-label">koeficijent</label>
            <input type="text" class="form-control <?= validationClass($errors, 'koeficijent') ?>" id="koeficijent" name="koeficijent" value="<?= $medij['koeficijent'] ?>" required>
            <?= validationFeedback($errors, 'koeficijent') ?>
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="<?= $subDir ?>/formats" class="btn btn-primary mb-3">Povratak</a>
            <button type="submit" class="btn btn-success mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>