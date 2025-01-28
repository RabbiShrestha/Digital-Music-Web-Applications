<?php

if($action == 'add')
{
   

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $errors = [];

    // Data validation
    if (empty($_POST['username'])) {
        $errors['username'] = "A username is required";
    } else if (!preg_match("/^[a-zA-Z]+$/", $_POST['username'])) {
        $errors['username'] = "A username can only have letters with no spaces";
    }

    if (empty($_POST['email'])) {
        $errors['email'] = "An email is required";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email not valid";
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "A password is required";
    } else if ($_POST['password'] != $_POST['retype_password']) {
        $errors['password'] = "Passwords do not match";
    }

    if (empty($_POST['role'])) {
        $errors['role'] = "Role is required";
    }

    if (empty($errors)) {
       
        $values = [];
        $values['username'] = trim($_POST['username']);
        $values['email'] = trim($_POST['email']);
        $values['role'] = trim($_POST['role']);
        $values['password'] = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        $values['date'] = date('Y-m-d H:i:s');
        
        $query = "INSERT INTO users (username, email, password, role, date) VALUES (:username, :email, :password, :role, :date)";
        db_query($query, $values);
        

        message("User created successfully");
        redirect('admin/users');
    }
}
}else
if ($action == 'edit') {
    $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $row = db_query_one($query, ['id' => $id]);

    if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
        $errors = [];

        // Data validation
        if (empty($_POST['username'])) {
            $errors['username'] = "A username is required";
        } else if (!preg_match("/^[a-zA-Z]+$/", $_POST['username'])) {
            $errors['username'] = "A username can only have letters with no spaces";
        }

        if (empty($_POST['email'])) {
            $errors['email'] = "An email is required";
        } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email not valid";
        }

        if (!empty($_POST['password']) && $_POST['password'] != $_POST['retype_password']) {
            $errors['password'] = "Passwords do not match";
        } else if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
            $errors['password'] = "Passwords must be 8 characters or more";
        }

        if (empty($_POST['role'])) {
            $errors['role'] = "Role is required";
        }

        if (empty($errors)) {
            $values = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'id' => $id,
            ];

            $query = "UPDATE users SET email = :email, username = :username, role = :role WHERE id = :id LIMIT 1";
            
            if (!empty($_POST['password'])) {
                $query = "UPDATE users SET email = :email, password = :password, username = :username, role = :role WHERE id = :id LIMIT 1";
                $values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            db_query($query, $values);

            message("User edited successfully");
            redirect('admin/users');
        }
    }
}

else
if ($action == 'delete') {
    $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $row = db_query_one($query, ['id' => $id]);

    if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
        $errors = [];

        if ($row['id'] == 1) {
            $errors['username'] = "the main admin cannot be deleted";
        }

        if (empty($errors)) {
            $values = [];
            $values['id'] = $id;

            $query = "DELETE FROM users WHERE id = :id LIMIT 1";
            db_query($query, $values);

            message("User deleted successfully");
            redirect('admin/users');
        }
    }
}

?>

<?php require page('/includes/admin-header') ?>

<section class="admin-content" style="min-height: 200px;">

    <?php if ($action == 'add'): ?>
       
        <div style="max-width: 500px; margin: auto;">
            <form method="post">
                <h3>Add New User</h3>
                
                <input class="form-control my-1" value="<?= set_value('username') ?>" type="text" name="username" placeholder="Username">  
                <?php if (!empty($errors['username'])): ?>
                    <small class="error"><?= $errors['username'] ?></small>
                <?php endif; ?>

                <input class="form-control my-1" value="<?= set_value('email') ?>" type="email" name="email" placeholder="Email">  
                <?php if (!empty($errors['email'])): ?>
                    <small class="error"><?= $errors['email'] ?></small>
                <?php endif; ?>

                <select name="role" class="form-control my-1">
                    <option value="">--Select Role--</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <?php if (!empty($errors['role'])): ?>
                    <small class="error"><?= $errors['role'] ?></small>
                <?php endif; ?>

                <input class="form-control my-1" value="<?= set_value('password') ?>" type="password" name="password" placeholder="Password"> 
                <?php if (!empty($errors['password'])): ?>
                    <small class="error"><?= $errors['password'] ?></small>
                <?php endif; ?>

                <input class="form-control my-1" value="<?= set_value('retype_password') ?>" type="password" name="retype_password" placeholder="Retype Password"> 
                
                <button class="btn bg-orange">Save</button>
                <a href="<?= ROOT ?>/admin/users">
                    <button type="button" class="float-end btn">Back</button>
                </a>
            </form>
        </div>
    <?php elseif ($action == 'edit'): ?>



        <div style="max-width: 500px; margin: auto;">
            <form method="post">
                <h3>Edit User</h3>
                
                <?php if(!empty($row)):?>

                <input class="form-control my-1" value="<?= set_value('username',$row['username']) ?>" type="text" name="username" placeholder="Username">  
                <?php if (!empty($errors['username'])): ?>
                    <small class="error"><?= $errors['username'] ?></small>
                <?php endif; ?>

                <input class="form-control my-1" value="<?= set_value('email',$row['email']) ?>" type="email" name="email" placeholder="Email">  
                <?php if (!empty($errors['email'])): ?>
                    <small class="error"><?= $errors['email'] ?></small>
                <?php endif; ?>

                <select name="role" class="form-control my-1">
    <option value="">--Select Role--</option>
    <option value="user" <?= ($row['role'] == 'user') ? 'selected' : '' ?>>User</option>
    <option value="admin" <?= ($row['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
</select>
<?php if (!empty($errors['role'])): ?>
    <small class="error"><?= $errors['role'] ?></small>
<?php endif; ?>

                <input class="form-control my-1" value="<?= set_value('password') ?>" type="password" name="password" placeholder="Password (leave empty to keep old one"> 
                <?php if (!empty($errors['password'])): ?>
                    <small class="error"><?= $errors['password'] ?></small>
                <?php endif; ?>

                <input class="form-control my-1" value="<?= set_value('retype_password') ?>" type="password" name="retype_password" placeholder="Retype Password"> 
                
                <button class="btn bg-orange">Save</button>
                <a href="<?= ROOT ?>/admin/users">
                    <button type="button" class="float-end btn">Back</button>
                </a>

                <?php else:?>
                <div class="alert">That record was not found</div>
                <a href="<?=ROOT?>/admin/users">
                    <button type="button" class="float-end btn">Back</button>
                <?php endif;?>
            </form>
        </div>


    <?php elseif ($action == 'delete'): ?>
        <div style="max-width: 500px; margin: auto;">
            <form method="post">
                <h3>Delete User</h3>
                
                <?php if(!empty($row)):?>

                <div class="form-control my-1"><?= set_value('username',$row['username'])?></div>
                <?php if (!empty($errors['username'])): ?>
                    <small class="error"><?= $errors['username'] ?></small>
                <?php endif; ?>

                <div class="form-control my-1"><?= set_value('email',$row['email'])?></div>
                <div class="form-control my-1"><?= set_value('role',$row['role'])?></div>
 
                <button class="btn bg-orange">delete</button>
                <a href="<?= ROOT ?>/admin/users">
                    <button type="button" class="float-end btn">Back</button>
                </a>

                <?php else:?>
                <div class="alert">That record was not found</div>
                <a href="<?=ROOT?>/admin/users">
                    <button type="button" class="float-end btn">Back</button>
                <?php endif;?>
            </form>
        </div>
    <?php else: ?>

        <?php
             $query = "select * from users order by id desc limit 20";
             $rows = db_query($query);  
             
             if(is_array($rows) && !empty($rows)): 
        ?>

        <h3>Users 
            <a href="<?= ROOT ?>/admin/users/add">
                <button class="float-end btn bg-purple">Add New</button>
            </a>
        </h3>

        <table class="table">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date</th>
                <th>Action</th>
            </tr>

            <?php foreach($rows as $row): ?>
                <tr>
                    <td><?=$row['id']?></td>
                    <td><?=$row['username']?></td>
                    <td><?=$row['email']?></td>
                    <td><?=$row['role']?></td>
                    <td><?=get_date($row['date'])?></td>
                    <td>
                    <a href="<?=ROOT?>/admin/users/edit/<?=$row['id']?>">
                    <img class= "bi" src="http://localhost/DMDS/public/assets/icons/pencil-square.svg"></a> 
                    </a> 
                    <a href="<?=ROOT?>/admin/users/delete/<?=$row['id']?>">
                    <img class= "bi" src="http://localhost/DMDS/public/assets/icons/trash.svg"></a>
                    </a>
                    

                    </td>
                </tr>
            <?php endforeach; ?>
            
        </table>

        <?php endif; ?>
     
    <?php endif; ?>
     
</section>

