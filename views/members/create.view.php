<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Dodaj novog člana</h1>
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
    <form class="row g-3 mt-3" action="<?= $subDir ?>/members" method="POST">
    <div class="col-md-6">
            <label for="ime" class="form-label">Ime</label>
            <input type="text" class="form-control <?= validationClass($errors, 'ime') ?>" id="ime" name="ime" placeholder="Ime" required>
            <?= validationFeedback($errors, 'ime') ?>
        </div>
        <div class="col-md-6">
            <label for="prezime" class="form-label">Prezime</label>
            <input type="text" class="form-control <?= validationClass($errors, 'prezime') ?>" id="prezime" name="prezime" placeholder="Prezime" required>
            <?= validationFeedback($errors, 'prezime') ?>
        </div>
        <div class="col-md-6">
            <label for="adresa" class="form-label">Adresa</label>
            <input type="text" class="form-control <?= validationClass($errors, 'adresa') ?>" id="adresa" name="adresa" placeholder="Adresa" required>
            <?= validationFeedback($errors, 'adresa') ?>
        </div>
        <div class="col-md-6">
            <label for="telefon" class="form-label">Telefon</label>
            <input type="text" class="form-control <?= validationClass($errors, 'telefon') ?>" id="telefon" name="telefon" placeholder="Broj telefona (+XXX XX XXXXXX)" required>
            <?= validationFeedback($errors, 'telefon') ?>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control <?= validationClass($errors, 'email') ?>" id="email" name="email" placeholder="Email" required>
            <?= validationFeedback($errors, 'email') ?>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-success mb-3">Spremi</button>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>