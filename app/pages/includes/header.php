<html lang="en">
<head>
    <title>DMDS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?=ROOT?>/assets/css/style.css?5234">
</head>
<body>

<header>

    <div class="header-div">
        <div class="main-title">KAJI MUSIC</div>
        <div class="socials">
            <!-- Social icons here -->
        </div>
        <div class="main-nav">
            <div class="nav-item"><a href="<?=ROOT?>">Home</a></div>
            <div class="nav-item"><a href="<?=ROOT?>/music">Music</a></div>
            <div class="nav-item dropdown">
                
                <a href="#">Category</a>

                     <?php 
                      $query = "select * from categories order by category asc";
                      $categories = db_query($query);
                      ?>

                <div class="dropdown-list">
                     
                <?php if(!empty($categories)):?>
                <?php foreach($categories as $cat):?>
                <div class="nav-item2"><a href="<?=ROOT?>/category/<?=$cat['category']?>"><?=$cat['category']?></a></div>
                <?php endforeach;?>
                <?php endif;?>

                  </div>
                  </div>
                    
            <div class="nav-item"><a href="<?=ROOT?>/artists">Artists</a></div>
            <div class="nav-item"><a href="<?=ROOT?>/about">About</a></div>
            <div class="nav-item"><a href="<?=ROOT?>/user_logout">Logout</a></div>
        </div>
    </div>
</header>

<script>
   document.addEventListener("DOMContentLoaded", function() {
    var dropdowns = document.querySelectorAll(".dropdown");

    dropdowns.forEach(function(dropdown) {
        dropdown.addEventListener("click", function(e) {
            e.stopPropagation();

            // Hide all other dropdowns
            dropdowns.forEach(function(otherDropdown) {
                if (otherDropdown !== dropdown) {
                    otherDropdown.querySelector(".dropdown-list").classList.remove("show");
                }
            });

            // Toggle the clicked dropdown's list
            var dropdownList = dropdown.querySelector(".dropdown-list");
            dropdownList.classList.toggle("show"); // Toggle "show" class
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener("click", function() {
        dropdowns.forEach(function(dropdown) {
            dropdown.querySelector(".dropdown-list").classList.remove("show");
        });
    });
});
</script>
</body>
</html>
