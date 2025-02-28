<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Financial Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
  <link rel="stylesheet" href="./css/home.css">
  <link rel="stylesheet" href="./css/summary.css">
</head>
<style>
  .progress-bar {
  height: 10px;
  background-color: #e0e0e0;
  width: 100%;
}

#financialHealthProgress {
  background-color: #7e57c2; /* Purple */
}

#budgetStatusProgress {
  background-color: #4db6ac; /* Teal */
}

</style>
<body>
  <?php include("sidebar.php"); ?>
  <div class="mid-bar">
    <div class="dashboard">
      <!-- Header and Date Range -->
      <div class="header">
        <h1>Financial Summary</h1>
        <div class="date-range">
          <span>View:</span>
          <select id="dateRange">
            <option value="week">This Week</option>
            <option value="month" selected>This Month</option>
            <option value="quarter">This Quarter</option>
            <option value="year">This Year</option>
          </select>
          <button class="export-btn">Export</button>
        </div>
      </div>
      
      <!-- Main Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-header">
            <h3>Total Expenses</h3>
            <i class="fas fa-wallet" style="color:rgb(0, 0, 0);"></i>
          </div>
          <div class="stat-value">रू<span id="totalExpenses">0</span></div>
          <div class="stat-context text-green">
            <i class="fas fa-arrow-up"></i> +12% from last month
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-header">
            <h3>Monthly Savings</h3>
            <i class="fas fa-piggy-bank" style="color:rgb(0, 0, 0);"></i>
          </div>
          <div class="stat-value">रू<span id="monthlySavings">0</span></div>
          <div class="stat-context">30% of income</div>
        </div>
        
        <div class="stat-card">
          <div class="stat-header">
            <h3>Financial Health</h3>
            <i class="fas fa-heartbeat" style="color:rgb(0, 0, 0);"></i>
          </div>
          <div class="stat-value" id="financialHealth">78/100</div>
          <div class="progress-bar">
            <div class="progress-value bg-purple" id="financialHealthProgress style="width: 78%;"></div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-header">
            <h3>Budget Status</h3>
            <i class="fas fa-calendar" style="color:rgb(0, 0, 0);"></i>
          </div>
          <div class="stat-value text-teal" id="overallBudgetStatus">On Track</div>
          <!-- This will now contain a single progress bar instead of multiple budget cards -->
          <div id="budgetCards" class="budget-cards-container"></div>
        </div>
      </div>
      
      <!-- Charts -->
      <div class="charts-grid">
        <div class="chart-card">
          <h3>Monthly Trend</h3>
          <div class="chart-container">
            <canvas id="monthlyTrendChart"></canvas>
          </div>
        </div>
        
        <div class="chart-card">
          <h3>Expenses by Category</h3>
          <div class="chart-container">
            <canvas id="categoryChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Include updated summary.js -->
  <script src="summary.js"></script>
  <script>
    // When the document loads, set up the date range change listener
    document.addEventListener('DOMContentLoaded', function () {
      const dateRangeSelect = document.getElementById('dateRange');
      // Whenever the date range is changed, update the financial stats accordingly.
      dateRangeSelect.addEventListener('change', function () {
        updateFinancialStats();
      });
    });
  </script>
</body>
</html>