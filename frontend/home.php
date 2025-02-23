<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Amiko', sans-serif;
        }

        .main {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            margin-bottom: 2rem;
        }

        .balance-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .total-balance {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .summary-row {
            display: flex;
            gap: 2rem;
        }

        .summary-item {
            flex: 1;
        }

        .summary-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .income-amount {
            color: #2ecc71;
            font-weight: bold;
        }

        .expense-amount {
            color: #e74c3c;
            font-weight: bold;
        }

        .transaction-form {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .transaction-type {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .type-radio {
            display: none;
        }

        .type-label {
            padding: 0.5rem 1rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
        }

        .type-radio:checked + .type-label {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .submit-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
        }

        .submit-btn:hover {
            background: #0056b3;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<link rel="stylesheet" href="./css/home.css">
<body>
    <div class="main">
        <?php include "header.php"; ?>
        <?php include "sidebar.php"; ?>
<div class="mid-bar">
        <div class="balance-card">
            <div class="total-balance">Total Balance</div>
            <div class="balance-amount">₨<span class="balance-amt">0</span></div>
            <div class="summary-row">
                <div class="summary-item">
                    <div class="summary-label">Income</div>
                    <div class="income-amount">₨<span class="income-amt">0</span></div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Expenses</div>
                    <div class="expense-amount">₨<span class="expense-amt">0</span></div>
                </div>
            </div>
        </div>

        <div class="transaction-form">
            <h2 class="form-title">Add Transaction</h2>
            <form id="transactionForm">
                <div class="transaction-type">
                    <input type="radio" id="incomeType" name="type" value="income" class="type-radio" checked>
                    <label for="incomeType" class="type-label">Income</label>
                    
                    <input type="radio" id="expenseType" name="type" value="expense" class="type-radio">
                    <label for="expenseType" class="type-label">Expense</label>
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <input list="categories" id="category" name="category" placeholder="Select category" required>
                    <datalist id="categories"></datalist>
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="note">Description</label>
                    <input type="text" id="note" name="note" placeholder="Enter description">
                </div>

                <button type="submit" class="submit-btn">Add Transaction</button>
            </form>
        </div>
    </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('transactionForm');
        const typeRadios = form.querySelectorAll('input[name="type"]');
        const categoriesDatalist = document.getElementById('categories');

        // Initial load of account summary
        updateAccountSummary();

        // Load appropriate categories when transaction type changes
        typeRadios.forEach(radio => {
            radio.addEventListener('change', updateCategories);
        });

        // Initial load of categories
        updateCategories();

        function updateCategories() {
            const type = form.querySelector('input[name="type"]:checked').value;
            const endpoint = type === 'income' ? 'income_categories' : 'expense_categories';
            
            // Clear existing options
            categoriesDatalist.innerHTML = '';
            
            // Fetch categories from PHP
            fetch(`../backend/get_categories.php?type=${endpoint}`)
                .then(response => response.json())
                .then(categories => {
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category;
                        categoriesDatalist.appendChild(option);
                    });
                });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const type = formData.get('type');
            const endpoint = type === 'income' ? '../backend/income_insert.php' : '../backend/expense_insert.php';

            fetch(endpoint, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: `${type.charAt(0).toUpperCase() + type.slice(1)} added successfully`,
                        icon: 'success'
                    }).then(() => {
                        form.reset();
                        updateAccountSummary();
                    });
                } else {
                    Swal.fire('Error', data.error || `Failed to add ${type}`, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to process request', 'error');
            });
        });

        function updateAccountSummary() {
            fetch('../backend/fetch.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        document.querySelector('.income-amt').textContent = data.total_income;
                        document.querySelector('.expense-amt').textContent = data.total_expense;
                        document.querySelector('.balance-amt').textContent = data.balance;
                    }
                })
                .catch(error => console.error('Error updating summary:', error));
        }
    });
    </script>
 <script src="navigation.js"></script>
</body>
</html>