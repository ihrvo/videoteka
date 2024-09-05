<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Dodaj novu cijenu</h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/prices" method="POST">
    <div class="col-md-6">
            <label for="tip_filma" class="form-label <?= validationClass($errors, 'tip_filma') ?>">Tip filma</label>
            <input type="text" class="form-control" id="tip_filma" name="tip_filma" placeholder="Tip filma" required>
            <?= validationFeedback($errors, 'tip_filma') ?>
        </div>
        <div class="col-md-6">
            <label for="cijena" class="form-label <?= validationClass($errors, 'cijena') ?>">Cijena</label>
            <input type="text" class="form-control" id="cijena" name="cijena" placeholder="Cijena (€)" required>
            <?= validationFeedback($errors, 'cijena') ?>
        </div>
        <div class="col-md-6">
            <label for="zakasnina_po_danu" class="form-label <?= validationClass($errors, 'zakasnina_po_danu') ?>">Zakasnina po danu</label>
            <input type="text" class="form-control" id="zakasnina_po_danu" name="zakasnina_po_danu" placeholder="Zakasnina po danu (€)" required>
            <?= validationFeedback($errors, 'zakasnina_po_danu') ?>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-success mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>