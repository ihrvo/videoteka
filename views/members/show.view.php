<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1 vh-100">
    <div class="title flex-between">
        <h1>Član <?= $member['ime'] ?> <?= $member['prezime'] ?></h1>
        <div class="action-buttons">
        </div>
    </div>
    <hr>
    <form class="row g-3 mt-3">
        <div class="col-md-6">
            <label for="ime" class="form-label">Ime</label>
            <input type="text" class="form-control" id="ime" name="ime" value="<?= $member['ime'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="prezime" class="form-label">Prezime</label>
            <input type="text" class="form-control" id="prezime" name="prezime" value="<?= $member['prezime'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="adresa" class="form-label">Adresa</label>
            <input type="text" class="form-control" id="adresa" name="adresa" value="<?= $member['adresa'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="telefon" class="form-label">Telefon</label>
            <input type="text" class="form-control" id="telefon" name="telefon" value="<?= $member['telefon'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $member['email'] ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="clanski_broj" class="form-label">Clanski broj</label>
            <input type="text" class="form-control" id="clanski_broj" name="clanski_broj" value="<?= $member['clanski_broj'] ?>" disabled>
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="<?= $subDir ?>/members" class="btn btn-primary mb-3">Povratak</a>
            <a href="<?= $subDir ?>/members/edit?id=<?= $member['id'] ?>" class="btn btn-info mb-3">Uredi</a>
        </div>
    </form>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>