<?php
message("testing one two");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = [];

    $values = [];
    $values['email'] = trim($_POST['email']);
    $query = "select * from users where email = :email limit 1";
    $row = db_query_one($query, $values);

    // Check if 'password' key exists in $_POST before accessing it
    if (!empty($row) && isset($_POST['password']) && password_verify($_POST['password'], $row['password'])) {
        authenticate($row);
        redirect('admin');
    } else {
        echo "<script>alert('Incorrect email or password');</script>";
    }
}
?>

<link rel="stylesheet" type="text/css" href="<?=ROOT?>/assets/css/style.css?5234">

<section class="content">
    <div class="login-holder">
        <form method="post">
            <h2>Admin Login</h2>
            <input class="my-1 form-control" type="email" name="email" placeholder="Email" required>
            <input class="my-1 form-control" type="password" name="password" placeholder="Password" required>
            <button class="my-1 btn bg-blue">Login</button>
        </form>
    </div>
</section>

<?php require page('includes/footer') ?>

    