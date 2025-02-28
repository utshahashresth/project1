<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finance Dashboard</title>
  <!-- FontAwesome CDN for icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/home.css">
</head>
<style>
  #balanceChart {
    height: 100px !important;
    width: 200px !important;
  }

  .chart-container {
    width: 100%;
    max-width: 500px;
    margin: auto;
  }

  .balance-card {
    display: flex;
    flex-direction: column;
  }

  .balance-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }

  .balance-info {
    flex: 1;
  }

  .chart-wrapper {
    flex: 1;
    display: flex;
    justify-content: flex-end;
  }

  .delete-btn {
    background-color: transparent;
    color: red;
    border: none;
    padding: 5px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: color 0.3s;
  }

  .delete-btn:hover {
    color: #c0392b;
  }

  .transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }

  .transaction-info {
    display: flex;
    flex-direction: column;
  }

  .transaction-description {
    font-weight: bold;
  }

  .amount {
    font-size: 16px;
    font-weight: bold;
  }

  .text-green-600 {
    color: green;
  }

  .text-red-600 {
    color: red;
  }

  .transaction-actions {
    display: flex;
    align-items: center;
  }
</style>
<body>
  <?php include "sidebar.php"; ?>

  <div class="main-content">
    <button class="add-transaction-btn" onclick="openModal()">
      <i class="fas fa-plus"></i> Add Income/Expense
    </button>

    <div class="balance-card">
      <div class="balance-header">
        <i class="fas fa-wallet"></i>
        <h2>Total Balance</h2>
      </div>
      
      <div class="balance-info-row">
        <div class="balance-info">
          <p class="balance-amount">Rs5,000</p>
          <div class="balance-trend">
            <i class="fas fa-arrow-up"></i>
            <span>+8% from last month</span>
          </div>
        </div>
        
        <div class="chart-wrapper">
          <canvas id="balanceChart"></canvas>
        </div>
      </div>
    </div>

    <div class="grid-layout">
      <div class="budget-section">
        <div class="section-header">
          <i class="fas fa-chart-pie"></i>
          <h2>Budgets</h2>
        </div>
        <div class="budget-list"></div>
      </div>

      <div class="recent-activity">
        <div class="section-header">
          <i class="fas fa-calendar-alt"></i>
          <h2>Recent Activity</h2>
        </div>
        <div class="activity-list"></div>
      </div>
    </div>

    <div class="pagination-container"></div>
  </div>

  <div class="modal" id="transactionModal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <h2>Add Income/Expense</h2>
      <form id="transactionForm" onsubmit="event.preventDefault(); submitTransaction();">
        <div class="form-group">
          <label for="type">Type</label>
          <select id="type" name="type" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
          </select>
        </div>
        <div class="form-group">
          <label for="amount">Amount</label>
          <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
        </div>
        <div class="form-group">
          <label for="category">Category</label>
          <select id="category" name="category" required></select>
        </div>
        <div class="form-group">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" required>
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" placeholder="Enter description"></textarea>
        </div>
        <button type="submit" class="submit-btn">Add Transaction</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="home.js"></script>

  <script>
    // Open Modal to Add Transaction
    function openModal() {
      document.getElementById('transactionModal').style.display = 'block';
    }

    // Close Modal
    function closeModal() {
      document.getElementById('transactionModal').style.display = 'none';
    }
    // Function to calculate and display the current balance and trend
async function fetchBalance() {
  try {
    // Fetch current month's balance data
    const currentResponse = await fetch('../backend/get_balance.php');
    if (!currentResponse.ok) throw new Error('Failed to fetch current balance');
    const currentData = await currentResponse.json();
    
    // Fetch previous month's balance data
    const prevResponse = await fetch('../backend/get_balance.php?period=previous_month');
    if (!prevResponse.ok) throw new Error('Failed to fetch previous balance');
    const previousData = await prevResponse.json();
    
    // Get the balance elements
    const balanceAmount = document.querySelector('.balance-amount');
    const balanceTrend = document.querySelector('.balance-trend');
    
    // Update current balance
    const currentBalance = currentData.balance || 0;
    balanceAmount.textContent = `Rs${parseFloat(currentBalance).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })}`;
    
    // Calculate percentage change
    const previousBalance = previousData.balance || 0;
    let percentChange = 0;
    let trendIcon = 'fa-minus';
    
    if (previousBalance > 0) {
      percentChange = ((currentBalance - previousBalance) / previousBalance) * 100;
      trendIcon = percentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
    } else if (currentBalance > 0) {
      // If previous balance was 0, but current is positive, show 100% increase
      percentChange = 100;
      trendIcon = 'fa-arrow-up';
    }
    
    // Format and display the trend
    const absPercentChange = Math.abs(percentChange).toFixed(1);
    const sign = percentChange >= 0 ? '+' : '-';
    const trendColor = percentChange >= 0 ? 'green' : 'red';
    
    balanceTrend.innerHTML = `
      <i class="fas ${trendIcon}" style="color: ${trendColor};"></i>
      <span style="color: ${trendColor};">${sign}${absPercentChange}% from last month</span>
    `;
    
    // Update the chart with the new data
    updateBalanceChart(currentData.monthlyData || []);
    
  } catch (error) {
    console.error('Error fetching balance:', error);
    document.querySelector('.balance-amount').textContent = '$0.00';
    document.querySelector('.balance-trend').innerHTML = '<span>No data available</span>';
  }
}

// Function to update the balance chart with new data
function updateBalanceChart(monthlyData) {
  const ctx = document.getElementById('balanceChart').getContext('2d');
  
  // If chart already exists, destroy it before creating a new one
  if (window.balanceChart instanceof Chart) {
    window.balanceChart.destroy();
  }
  
  // Prepare data for chart
  const labels = monthlyData.map(item => item.month || '');
  const values = monthlyData.map(item => item.balance || 0);
  
  // Create new chart
  window.balanceChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Balance',
        data: values,
        borderColor: '#4CAF50',
        backgroundColor: 'rgba(76, 175, 80, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        x: {
          display: false
        },
        y: {
          display: false
        }
      }
    }
  });
}

// Call fetchBalance when page loads
document.addEventListener('DOMContentLoaded', fetchBalance);

// Also call fetchBalance after adding a new transaction
function submitTransaction() {
  // ... existing code ...
  
  // After successful transaction:
  if (result.success) {
    alert('Transaction added successfully!');
    closeModal();
    form.reset();
    fetchBalance(); // Re-fetch balance to update trend
    fetchRecentTransactions();
  }
}

    async function submitTransaction() {
      try {
        const form = document.getElementById('transactionForm');
        const formData = new FormData(form);
        
        const transaction = {
          type: formData.get('type'),
          category: formData.get('category'),
          amount: parseFloat(formData.get('amount')),
          description: formData.get('description') || '',
          date: formData.get('date')

        };

        console.log('Submitting transaction:', transaction);

        const endpoint = transaction.type === 'income' ? 
          '../backend/income_insert.php' : 
          '../backend/expense_insert.php';

        const response = await fetch(endpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(transaction)
        });

        const result = await response.json();

        if (result.success) {
          alert('Transaction added successfully!');
          closeModal();
          form.reset();
          fetchBalance();
          fetchRecentTransactions();
        } else {
          throw new Error(result.error || 'Unknown error occurred');
        }
      } catch (error) {
        console.error('Error submitting transaction:', error);
        alert('Error: ' + error.message);
      }
    }

    async function fetchCategories() {
      try {
        const response = await fetch('../backend/fetch_categories.php');
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        const categorySelect = document.getElementById('category');
        
        categorySelect.innerHTML = '';
        
        const transactionType = document.getElementById('type').value;
        const categories = transactionType === 'income' ? 
          data.income_categories : 
          data.expense_categories;

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '-- Select Category --';
        defaultOption.disabled = true;
        defaultOption.selected = true;
        categorySelect.appendChild(defaultOption);

        if (Array.isArray(categories)) {
          categories.forEach(category => {
            const option = document.createElement('option');
            const categoryName = category.name || category.category_name || category.value || JSON.stringify(category);
            option.value = categoryName;
            option.textContent = categoryName;
            categorySelect.appendChild(option);
          });
        }
      } catch (error) {
        console.error('Error in fetchCategories:', error);
        alert('Error fetching categories. Check the console for details.');
      }
    }

    document.getElementById('type').addEventListener('change', fetchCategories);
    document.addEventListener('DOMContentLoaded', fetchCategories);

    fetch('../backend/get_budgets.php')
      .then(response => response.json())
      .then(data => {
        if (data.success === false) {
          alert('User not logged in');
          return;
        }

        const budgetList = document.querySelector('.budget-list');
        budgetList.innerHTML = '';

        data.forEach(budget => {
          const budgetItem = document.createElement('div');
          budgetItem.className = 'budget-item';
          const progress = (budget.spent / budget.amount) * 100;

          budgetItem.innerHTML = `
            <div class="budget-info">
              <span>${budget.category_name}</span>
              <span>Rs${budget.spent} / Rs${budget.amount}</span>
            </div>
            <div class="budget-progress">
              <div class="progress-bar" style="width: ${progress}%;"></div>
            </div>
          `;
          budgetList.appendChild(budgetItem);
        });
      })
      .catch(error => console.error('Error fetching budget data:', error));

    function fetchRecentTransactions() {
      fetch('http://localhost/project1/backend/history_fetch.php')
        .then(response => response.json())
        .then(data => {
          if (data.status === 'error' || !data.data.transactions.length) {
            console.error("No transactions found");
            return;
          }
          displayRecentTransactions(data.data.transactions.slice(0, 4));
        })
        .catch(error => console.error('Error fetching transactions:', error));
    }

    function displayRecentTransactions(transactions) {
      const transactionsContainer = document.querySelector('.activity-list');
      transactionsContainer.innerHTML = '';

      if (!transactions.length) {
        transactionsContainer.innerHTML = "<p>No recent transactions available.</p>";
        return;
      }

      transactions.forEach(transaction => {
        const transactionItem = document.createElement('div');
        transactionItem.classList.add('activity-item');

        const amountClass = transaction.type === 'income' ? 'positive' : 'negative';
        const amountSign = transaction.type === 'income' ? '+' : '-';

        transactionItem.innerHTML = `
          <i class="${transaction.type === 'income' ? 'fas fa-wallet' : 'fas fa-credit-card'}"></i>
          <span>${transaction.category}: ${transaction.notes}</span>
          <span class="amount ${amountClass}">${amountSign}Rs${Math.abs(transaction.amount)}</span>
          <span class="date">${transaction.date}</span>
          <button class="delete-btn" onclick="deleteTransaction(${transaction.id})"><i class="fas fa-trash-alt"></i></button>
        `;
        transactionsContainer.appendChild(transactionItem);
      });
    }

    function deleteTransaction(transactionId) {
  if (confirm("Are you sure you want to delete this transaction?")) {
    fetch(`../backend/delete_transaction.php?id=${transactionId}`, {
      method: 'GET'  // Using GET to pass the ID as a query parameter
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Transaction deleted successfully!');
          fetchRecentTransactions(); // Refresh the transaction list
        } else {
          alert('Error deleting transaction: ' + (data.error || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error deleting transaction:', error);
        alert('Error deleting transaction');
      });
  }
}


    fetchBalance();
    fetchRecentTransactions();
  </script>
</body>
</html>
