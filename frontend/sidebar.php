<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Dashboard</title>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-left">
                <i class="icon-menu"></i>
                <h1 class="logo">Finance</h1>
                <?php
// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="nav-links">
    <a href="home.php" class="nav-link <?php echo ($current_page == 'home.php') ? 'active' : ''; ?>">
        <i class="icon-home"></i>
        <span>Home</span>
    </a>
    <a href="stats.php" class="nav-link <?php echo ($current_page == 'stats.php') ? 'active' : ''; ?>">
        <i class="icon-bar-chart"></i>
        <span>Trends & Insight</span>
    </a>
    <a href="Summary.php" class="nav-link <?php echo ($current_page == 'Summary.php') ? 'active' : ''; ?>">
        <i class="icon-clock"></i>
        <span>Summary</span>
    </a>
    <a href="budget.php" class="nav-link <?php echo ($current_page == 'budget.php') ? 'active' : ''; ?>">
        <i class="icon-wallet"></i>
        <span>Budgets</span>
    </a>
</div>
</div>
            <div class="navbar-right">
                <button class="notification-btn">
                    <i class="icon-bell"></i>
                    <span class="notification-dot" id="notification-dot" style="display: none;"></span>
                </button>
                
                <div class="profile">
                    <img src="./img/profile.jpg" alt="Profile" id="profile-btn">
                    <span class="profile-name" id="profile-name">Loading...</span>
                    
                    <!-- Dropdown Menu -->
                    <div class="profile-dropdown" id="profile-dropdown">
                        <a href="logout.php" class="dropdown-item">Logout</a>
                    </div>
                </div>
                
                <i class="icon-settings"></i>
            </div>
        </div>
    </nav>

    <!-- JavaScript -->
    <script>
 document.addEventListener("DOMContentLoaded", function () {
    const profileBtn = document.getElementById("profile-btn");
    const dropdownMenu = document.getElementById("profile-dropdown");
    const profileNameElement = document.getElementById("profile-name");
    const notificationDot = document.getElementById("notification-dot");

    // Fetch username dynamically from the backend
    fetch('../backend/fetch_name.php') // Replace with the actual path to your PHP file
        .then(response => response.json())  // Handle the response as JSON
        .then(data => {
            if (data.username) {
                profileNameElement.textContent = data.username;  // Set the username from the JSON response
            } else {
                profileNameElement.textContent = 'Error loading username'; // Handle the case where no username is found
            }
        })
        .catch(error => {
            console.error('Error fetching username:', error);
            profileNameElement.textContent = 'Error loading username';
        });

    // Dropdown toggle
    profileBtn.addEventListener("click", function (event) {
        event.stopPropagation();
        dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", function (event) {
        if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove("show");
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            dropdownMenu.classList.remove("show");
        }
    });
});

    </script>

    <!-- CSS -->
    <style>
      .profile {
    position: relative;
    display: inline-flex; /* Change to flex layout */
    align-items: center; /* Vertically align the items */
    cursor: pointer;
}

.profile img {
    width: 40px;  /* Adjust the profile image size */
    height: 40px; /* Adjust the profile image size */
    border-radius: 50%; /* Make the image round */
    margin-right: 10px; /* Add space between the image and the username */
}

.profile-name {
    font-size: 16px; /* Adjust font size for the username */
    font-weight: 600; /* Make the username bold */
    color: #333; /* Set a color for the username */
}

.profile-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 50px;
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    min-width: 120px;
    z-index: 100;
}


.profile-dropdown.show {
    display: block;
}

.profile-dropdown .dropdown-item {
    padding: 10px;
    display: block;
    color: black;
    text-decoration: none;
    transition: background 0.3s;
}

.profile-dropdown .dropdown-item:hover {
    background: #f5f5f5;
}

.notification-btn {
    position: relative;
    background: none;
    border: none;
    cursor: pointer;
}

.notification-dot {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 8px;
    height: 8px;
    background-color: red;
    border-radius: 50%;
}

    </style>

</body>
</html>
