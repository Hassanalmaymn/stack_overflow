
<nav class="navbar navbar-expand-md bg-dark">

    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="icon.PNG" width="157.5px" height="52.5px" ></img></a>
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


            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <form class="d-flex justify-content-center" method="GET" action="search.php" role="search">
                    <input class="form-control me-2" name="search" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light"  type="submit">Search</button>
                </form>
            </ul>




        </div>
        <?php
        require_once'User.php';

        if (!isLoggedIn()) {
            echo'<a class="btn btn-outline-light justify-content-end m-2" href="display10most.php">display questions has most answers</a>
                <a class="btn btn-outline-light justify-content-end m-2" aria-current="page" href="signin.php">sign in</a>
            <a class="btn btn-outline-light justify-content-end m-2" aria-current="page" href="signup.php">sign up</a>';
        }
        if (isLoggedIn()) {
            $home = $active === 'home' ? 'active' : '';
            $my_questions = $active === 'my_questions' ? 'active' : '';
            $my_answers = $active === 'my_answers' ? 'active' : '';
            echo '
                <ul class="navbar-nav">
                    <li class="nav-item ' . $home . '">
                        <a class="btn btn-outline-light m-2" href="index.php">Home</a>
                    </li>
                    <li class="nav-item ' . $my_questions . '">
                        <a class="btn btn-outline-light m-2" href="userquestions.php">My Questions</a>
                    </li>
                    <li class="nav-item ' . $my_answers . '">
                        <a class="btn btn-outline-light m-2" href="useranswers.php">My Answers</a>
                    </li>
        </ul>
        
        
                    <a class="btn btn-outline-light justify-content-end m-2" href="signout.php">sign out</a>';
        }
        ?>
    </div>



</nav>
