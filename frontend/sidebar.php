<div class="dashboard">
    <!-- Top Navigation Bar -->
    <nav class="navbar">
      <div class="container">
        <div class="navbar-left">
          <i class="icon-menu"></i>
          <h1 class="logo">Finance</h1>
          <div class="nav-links">
            <a href="home.php" class="nav-link active">
              <i class="icon-home"></i>
              <span>Home</span>
            </a>
            <a href="stats.php" class="nav-link">
              <i class="icon-bar-chart"></i>
              <span>Statistics</span>
            </a>
            <a href="history.php" class="nav-link">
              <i class="icon-clock"></i>
              <span>History</span>
            </a>
            <a href="budget.php" class="nav-link">
              <i class="icon-wallet"></i>
              <span>Budgets</span>
            </a>
          </div>
        </div>
        <div class="navbar-right">
          <button class="notification-btn">
            <i class="icon-bell"></i>
            <span class="notification-dot"></span>
          </button>
          
          <div class="profile">
            <img src="./img/profile.jpg" alt="Profile" id="profile-btn">
            <span class="profile-name"></span>
            
            <!-- Dropdown Menu -->
            <div class="profile-dropdown" id="profile-dropdown">
              <a href="logout.php" class="dropdown-item">Logout</a>
            </div>
          </div>
          
          <i class="icon-settings"></i>
        </div>
      </div>
    </nav>
</div>

<!-- JavaScript for Dropdown -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const profileBtn = document.getElementById("profile-btn");
    const dropdownMenu = document.getElementById("profile-dropdown");

    profileBtn.addEventListener("click", function (event) {
      event.stopPropagation();
      dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", function (event) {
      if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.remove("show");
      }
    });
  });
</script>

<!-- CSS for Dropdown -->
<style>
  .profile {
    position: relative;
    display: inline-block;
    cursor: pointer;
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
</style>
