<?php

//logout.php

//Destroy entire session data.
session_destroy();

//redirect page to signin.php
header('location:signin.php');

?>