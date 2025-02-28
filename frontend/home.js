// Open Modal to Add Transaction
function openModal() {
  document.getElementById('transactionModal').style.display = 'block';
}

// Close Modal
function closeModal() {
  document.getElementById('transactionModal').style.display = 'none';
}

async function submitTransaction() {
  try {
    // Get form data
    const form = document.getElementById('transactionForm');
    const formData = new FormData(form);
    
    // Create transaction object
    const transaction = {
      type: formData.get('type'),
      category: formData.get('category'),
      amount: parseFloat(formData.get('amount')),
      description: formData.get('description') || '',
      date: new Date().toISOString().split('T')[0]
    };

    console.log('Submitting transaction:', transaction);

    // Determine which endpoint to use based on transaction type
    const endpoint = transaction.type === 'income' ? 
      '../backend/income_insert.php' : 
      '../backend/expense_insert.php';

    // Make the API call
    const response = await fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(transaction)
    });

 

    // Check if response is JSON
    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      // If not JSON, get the text response for debugging
      const textResponse = await response.text();
      console.error('Non-JSON response:', textResponse);
      throw new Error('Server returned non-JSON response. Check server logs.');
    }

    // Parse the JSON response
    const result = await response.json();
    

    if (result.success) {
      alert('Transaction added successfully!');
      closeModal();
      form.reset();
      // Refresh the relevant data
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
    if (!categorySelect) {
      throw new Error('Category select element not found');
    }

    categorySelect.innerHTML = '';
    
    // Get the transaction type from the select element
    const transactionType = document.getElementById('type').value;
    
    // Choose which categories to display based on transaction type
    const categories = transactionType === 'income' ? 
      data.income_categories : 
      data.expense_categories;

    // Add a default option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = '-- Select Category --';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    categorySelect.appendChild(defaultOption);

    // Add the categories
    if (Array.isArray(categories)) {
      categories.forEach(category => {
        const option = document.createElement('option');
        // Check if category is an object and extract the name/value
        if (typeof category === 'object' && category !== null) {
          // Look for common category name properties
          const categoryName = category.name || category.category_name || category.value || JSON.stringify(category);
          option.value = categoryName;
          option.textContent = categoryName;
        } else {
          option.value = category;
          option.textContent = category;
        }
        categorySelect.appendChild(option);
      });
    }
    
   
  } catch (error) {
    console.error('Error in fetchCategories:', error);
    alert('Error fetching categories. Check the console for details.');
  }
}

// Add an event listener to update categories when transaction type changes
document.getElementById('type').addEventListener('change', fetchCategories);

// Initial fetch when the page loads
document.addEventListener('DOMContentLoaded', fetchCategories);

// Fetch budget data from the backend
fetch('../backend/get_budgets.php')
  .then(response => response.json())
  .then(data => {
    if (data.success === false) {
      alert('User not logged in');
      return;
    }

    const budgetList = document.querySelector('.budget-list');
    budgetList.innerHTML = ''; // Clear existing budget list

    data.forEach(budget => {
      const budgetItem = document.createElement('div');
      budgetItem.className = 'budget-item';
      const progress = (budget.spent / budget.amount) * 100;

      budgetItem.innerHTML = `
        <div class="budget-info">
          <span>${budget.category_name}</span>
          <span>$${budget.spent} / $${budget.amount}</span>
        </div>
        <div class="budget-progress">
          <div class="progress-bar" style="width: ${progress}%;"></div>
        </div>
      `;
      budgetList.appendChild(budgetItem);
    });
  })
  .catch(error => console.error('Error fetching budget data:', error));

// Fetch the 4 most recent transactions
function fetchRecentTransactions() {
  const url = new URL('http://localhost/project1/backend/history_fetch.php');
  url.searchParams.append('page', 1); // Always fetch the first page

  fetch(url)
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

// Display the 4 most recent transactions
function displayRecentTransactions(transactions) {
  const transactionsContainer = document.querySelector('.activity-list');
  transactionsContainer.innerHTML = ''; // Clear existing content

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
      <i class="${transaction.type === 'income' ? 'icon-wallet' : 'icon-credit-card'}"></i>
      <span>${transaction.category}: ${transaction.notes}</span>
      <span class="amount ${amountClass}">${amountSign}$${Math.abs(transaction.amount)}</span>
      <span class="date">${transaction.date}</span>
    `;
    transactionsContainer.appendChild(transactionItem);
  });
}

// Initial fetch for categories and recent transactions
fetchCategories();
fetchRecentTransactions();

// Fetch the total balance, income, and expense data from the backend
function fetchBalance() {
  fetch('../backend/fetch.php')
    .then(response => response.json())
    .then(data => {
      if (data === 0) {
        document.querySelector(".balance-amount").innerText = "Not logged in";
      } else {
        document.querySelector(".balance-amount").innerText = `Rs${data.balance}`;
      }
    })
    .catch(error => console.error('Error fetching balance:', error));
}

// Call the function when the page loads
fetchBalance();

let balanceChart;

async function fetchBalanceTrend() {
  try {
    const response = await fetch('/project1/backend/balance_trend.php');
    const data = await response.json();

    if (!data || data.length === 0) {
      console.error("No balance trend data found");
      return;
    }

    const labels = data.map(entry => entry.date);
    const balances = data.map(entry => entry.balance);

    updateBalanceChart(labels, balances);
  } catch (error) {
    console.error('Error fetching balance trend data:', error);
  }
}

function updateBalanceChart(labels, data) {
  const ctx = document.getElementById('balanceChart').getContext('2d');

  if (balanceChart) {
    balanceChart.destroy();
  }

  balanceChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Balance Trend',
        data: data,
        borderColor: '#000000',
        backgroundColor: 'rgb(0, 0, 0)',
        borderWidth: 2,
        pointRadius: 3,
        pointBackgroundColor: '#4CAF50',
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: { display: false },
        y: { display: false }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
}

document.addEventListener("DOMContentLoaded", fetchBalanceTrend);