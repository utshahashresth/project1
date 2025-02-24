// Open Modal to Add Transaction
function openModal() {
    document.getElementById('transactionModal').style.display = 'block';
  }

  // Close Modal
  function closeModal() {
    document.getElementById('transactionModal').style.display = 'none';
  }

  // Fetch budget data from the backend
  fetch('../backend/get_budgets.php')
    .then(response => response.json())
    .then(data => {
      if (data.success === false) {
        alert('User not logged in');
        return;
      }

      // Loop through the budgets and update the UI
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
      console.log("Fetched Data:", data); // Debugging

      if (data.status === 'error' || !data.data.transactions.length) {
        console.error("No transactions found");
        return;
      }

      // Display only the 4 most recent transactions
      displayRecentTransactions(data.data.transactions.slice(0, 4));
    })
    .catch(error => console.error('Error fetching transactions:', error));
}

// Display the 4 most recent transactions
function displayRecentTransactions(transactions) {
  const transactionsContainer = document.querySelector('.activity-list');
  transactionsContainer.innerHTML = ''; // Clear existing content

  console.log("Recent Transactions:", transactions); // Debugging

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

// Initial fetch for the 4 most recent transactions
fetchRecentTransactions();
// Fetch the total balance, income, and expense data from the backend
function fetchBalance() {
  fetch('../backend/fetch.php') // Adjust the path if needed
      .then(response => response.json())
      .then(data => {
          if (data === 0) {
              document.querySelector(".balance-amount").innerText = "Not logged in";
          } else {
              document.querySelector(".balance-amount").innerText = `$${data.balance}`;
          }
      })
      .catch(error => console.error('Error fetching balance:', error));
}

// Call the function when the page loads
fetchBalance();