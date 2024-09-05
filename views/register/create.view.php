<?php include_once base_path('views/partials/fheader.php'); ?>

        <main>
            <div class="container py-4">
                <div class="p-5 mb-4 bg-body-tertiary rounded-3">
                    <div class="container-fluid">
                        <form class="row g-3 mt-3" action="<?= $subDir ?>/register" method="POST">
                            <div class="col-md-6">
                                <label for="ime" class="form-label">Ime</label>
                                <input type="text" class="form-control <?= validationClass($errors, 'ime') ?>" id="ime" name="ime" placeholder="Ime" required>
                                <span class="text-danger small"><?= $errors['ime'] ?? '' ?></span>
                            </div>
                            <div class="col-md-6">
                                <label for="prezime" class="form-label">Prezime</label>
                                <input type="text" class="form-control <?= validationClass($errors, 'prezime') ?>" id="prezime" name="prezime" placeholder="Prezime" required>
                                <span class="text-danger small"><?= $errors['prezime'] ?? '' ?></span>
                            </div>
                            <div class="col-md-6">
                                <label for="adresa" class="form-label">Adresa</label>
                                <input type="text" class="form-control <?= validationClass($errors, 'adresa') ?>" id="adresa" name="adresa" placeholder="Adresa">
                                <span class="text-danger small"><?= $errors['adresa'] ?? '' ?></span>
                            </div>
                            <div class="col-md-6">
                                <label for="telefon" class="form-label">Telefon</label>
                                <input type="text" class="form-control <?= validationClass($errors, 'telefon') ?>" id="telefon" name="telefon" placeholder="Telefon">
                                <span class="text-danger small"><?= $errors['telefon'] ?? '' ?></span>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?= validationClass($errors, 'email') ?>" id="email" name="email" placeholder="Email" required>
                                <span class="text-danger small"><?= $errors['email'] ?? '' ?></span>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Lozinka</label>
                                <input type="password" class="form-control <?= validationClass($errors, 'password') ?>" id="password" name="password" placeholder="Lozinka" required>
                                <span class="text-danger small"><?= $errors['password'] ?? '' ?></span>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <a href="/" class="btn btn-primary mb-3">Povratak</a>
                                <button type="submit" class="btn btn-success mb-3">Registriraj se</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
                
<?php include_once base_path('views/partials/ffooter.php'); ?>