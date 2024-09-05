<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Dodaj novi medij</h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/formats" method="POST">
    <div class="col-md-6">
            <label for="tip" class="form-label">Tip</label>
            <input type="text" class="form-control <?= validationClass($errors, 'tip') ?>" id="tip" name="tip" placeholder="Tip">
            <?= validationFeedback($errors, 'tip') ?>
        </div>
        <div class="col-md-6">
            <label for="koeficijent" class="form-label">Koeficijent</label>
            <input type="number" step="0.01" class="form-control <?= validationClass($errors, 'koeficijent') ?>" id="koeficijent" name="koeficijent" placeholder="Koeficijent">
            <?= validationFeedback($errors, 'koeficijent') ?>
        </div>
        <div class="col-12 d-flex mt-4 justify-content-between">
            <a href="/formats" class="btn btn-primary mb-3">Povratak</a>
            <button type="submit" class="btn btn-success mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>