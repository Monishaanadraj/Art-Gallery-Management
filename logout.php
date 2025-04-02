<?php
// Log out code (clear session and localStorage)
session_start();
session_unset();
session_destroy();
echo "<script>
        localStorage.removeItem('user_id');
        window.location.href = 'login.php';
      </script>";

?>