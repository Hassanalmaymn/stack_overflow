
<nav class="navbar navbar-expand-md bg-warning">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="icon.png" width="35px" height="35px" ></img> stack overflow</a>
        <button class="navbar-toggler"
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent"
                aria-expanded="false" 

                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">




                </li>

                <form class="d-flex" method="GET" action="index.php" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>

        </div>
        <?php
        require_once'User.php';
        if (!isLoggedIn()) {
            echo'<a class="btn btn-outline-dark justify-content-end m-2" aria-current="page" href="signin.php">sign in</a>' .
            '<a class="btn btn-outline-dark justify-content-end m-2" aria-current="page" href="signup.php">sign up</a>';
        } else {
            echo'<a class="btn btn-outline-dark justify-content-end m-2" aria-current="page" href="index.php">my questions</a>' .
            '<a class="btn btn-outline-dark justify-content-end m-2" aria-current="page" href="index.php">my answers</a>';
        }
        ?>
    </div>
</nav>
