<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Uredi <?= $price['tip_filma'] ?></h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/prices" method="POST">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="id" value="<?= $price['id'] ?>">
        <div class="col-md-6">
            <label for="tip_filma" class="form-label <?= validationClass($errors, 'tip_filma') ?>">Tip filma</label>
            <input type="text" class="form-control" id="tip_filma" name="tip_filma" value="<?= $price['tip_filma'] ?>" required>
            <?= validationFeedback($errors, 'tip_filma') ?>
        </div>
        <div class="col-md-6">
            <label for="cijena" class="form-label <?= validationClass($errors, 'cijena') ?>">Cijena</label>
            <input type="text" class="form-control" id="cijena" name="cijena" value="<?= $price['cijena'] ?>" required>
            <?= validationFeedback($errors, 'cijena') ?>
        </div>
        <div class="col-md-6">
            <label for="zakasnina_po_danu" class="form-label <?= validationClass($errors, 'zakasnina_po_danu') ?>">Zakasnina po danu</label>
            <input type="text" class="form-control" id="zakasnina_po_danu" name="zakasnina_po_danu" value="<?= $price['zakasnina_po_danu'] ?>" required>
            <?= validationFeedback($errors, 'zakasnina_po_danu') ?>
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="<?= $subDir ?>/prices" class="btn btn-primary mb-3">Povratak</a>
            <button type="submit" class="btn btn-success mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>