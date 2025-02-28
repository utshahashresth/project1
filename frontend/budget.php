<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Management</title>
    <link rel="stylesheet" href="./css/home.css">
    <style>
        h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 1rem;
            color: #0f172a;
        }

        .section {
            background: white;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 600px) {
            form {
                grid-template-columns: 1fr;
            }
        }

        label {
            display: block;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        input, select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        input:focus, select:focus {
            outline: none;
            border-color:rgb(0, 0, 0);
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.2);
        }

        button {
            background-color:rgb(0, 0, 0);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        button:hover {
            background-color:rgb(85, 85, 85);
        }

        .progress-container {
            background-color: #f1f5f9;
            border-radius: 4px;
            height: 0.5rem;
            overflow: hidden;
            margin: 0.25rem 0 0.75rem;
        }

        .progress-bar {
            height: 100%;
            background-color:rgb(0, 0, 0);
            transition: width 0.3s;
        }

        .progress-bar.warning {
            background-color: #f59e0b;
        }

        .progress-bar.danger {
            background-color: #ef4444;
        }

        .budget-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .budget-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .budget-item:last-child {
            border-bottom: none;
        }

        .budget-item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }

        .budget-category {
            font-weight: 500;
        }

        .budget-dates {
            color: #64748b;
            font-size: 0.75rem;
        }

        .budget-amount {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .overall-progress {
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-align: right;
        }

        /* Additional styles for the period selector */
        .period-fields {
            grid-column: span 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Hide date fields initially */
        .date-fields {
            display: none;
            grid-column: span 2;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .budget-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center; /* Ensures vertical centering */
    margin-bottom: 0.25rem;
}

.delete-icon {
    cursor: pointer;
    color:rgb(0, 0, 0); /* Red color for the delete icon */
    font-size: 1.25rem;
    margin-left: 10px; /* Space between the budget info and the delete icon */
}

.delete-icon:hover {
    color:rgb(151, 151, 151); /* Darker red on hover */
}

        
    </style>
</head>
<body>
    <?php
    include("sidebar.php")
    ?>
    <div class="mid-bar">
        <div class="section">
            <h2>New Budget</h2>
            <form id="budgetForm">
                <div>
                    <label for="category">Category</label>
                    <select id="category" name="category" required></select>
                </div>
                <div>
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" required>
                </div>
                
                <div class="period-fields">
                    <div>
                        <label for="period_type">Budget Period</label>
                        <select id="period_type" name="period_type" required>
                            <option value="week">Weekly</option>
                            <option value="month" selected>Monthly</option>
                            <option value="year">Yearly</option>
                            <option value="custom">Custom Dates</option>
                        </select>
                    </div>
                    
                    <div id="period_selector">
                        <label for="period_value">Select Period</label>
                        <input type="month" id="period_value" name="period_value" required>
                    </div>
                </div>
                
                <div class="date-fields" id="date_fields">
                    <div>
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date">
                    </div>
                    <div>
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date">
                    </div>
                </div>
                
                <div style="grid-column: span 2;">
                    <button type="submit">Add Budget</button>
                </div>
            </form>
        </div>

        <div class="section">
            <h2>Current Budgets</h2>
            <ul id="budgetList" class="budget-list"></ul>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const periodTypeSelect = document.getElementById("period_type");
            const periodValueField = document.getElementById("period_value");
            const dateFields = document.getElementById("date_fields");
            const startDateInput = document.getElementById("start_date");
            const endDateInput = document.getElementById("end_date");
            
            // Set default date to current month
            const currentDate = new Date();
            const currentMonth = currentDate.getFullYear() + "-" + String(currentDate.getMonth() + 1).padStart(2, '0');
            periodValueField.value = currentMonth;
            
            // Initialize with dates based on current month
            updateDatesFromPeriod("month", currentMonth);
            
            // Load categories
            fetch("get_categories.php")
                .then(response => response.json())
                .then(categories => {
                    let categorySelect = document.getElementById("category");
                    categories.forEach(category => {
                        let option = document.createElement("option");
                        option.value = category.id;
                        option.textContent = category.category_name;
                        categorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error("Error fetching categories:", error));
            
            // Handle period type changes
            periodTypeSelect.addEventListener("change", function() {
                const selectedPeriodType = this.value;
                updatePeriodSelector(selectedPeriodType);
            });
            
            // Handle period value changes
            periodValueField.addEventListener("change", function() {
                const selectedPeriodType = periodTypeSelect.value;
                const selectedValue = this.value;
                updateDatesFromPeriod(selectedPeriodType, selectedValue);
            });
            
            // Initialize with monthly period type
            updatePeriodSelector("month");
        });
        
        function updatePeriodSelector(periodType) {
            const periodValueField = document.getElementById("period_value");
            const dateFields = document.getElementById("date_fields");
            const periodLabel = document.querySelector('label[for="period_value"]');
            
            switch(periodType) {
                case "week":
                    periodLabel.textContent = "Select Week";
                    periodValueField.type = "week";
                    dateFields.style.display = "none";
                    break;
                    
                case "month":
                    periodLabel.textContent = "Select Month";
                    periodValueField.type = "month";
                    dateFields.style.display = "none";
                    break;
                    
                case "year":
                    periodLabel.textContent = "Select Year";
                    periodValueField.type = "number";
                    periodValueField.min = "2000";
                    periodValueField.max = "2100";
                    periodValueField.value = new Date().getFullYear();
                    dateFields.style.display = "none";
                    break;
                    
                case "custom":
                    periodLabel.textContent = "Custom Range";
                    periodValueField.type = "hidden";
                    dateFields.style.display = "grid";
                    break;
            }
            
            // Update dates based on the new period type
            updateDatesFromPeriod(periodType, periodValueField.value);
        }
        
        function updateDatesFromPeriod(periodType, value) {
            const startDateInput = document.getElementById("start_date");
            const endDateInput = document.getElementById("end_date");
            
            let startDate, endDate;
            
            switch(periodType) {
                case "week":
                    // Week format is YYYY-Www (e.g., 2025-W09)
                    if (value) {
                        const parts = value.split('-W');
                        const year = parseInt(parts[0]);
                        const week = parseInt(parts[1]);
                        
                        // Calculate first day of the week (Monday)
                        startDate = new Date(year, 0, 1 + (week - 1) * 7);
                        while (startDate.getDay() !== 1) {
                            startDate.setDate(startDate.getDate() + 1);
                        }
                        
                        // End date is 6 days later (Sunday)
                        endDate = new Date(startDate);
                        endDate.setDate(startDate.getDate() + 6);
                    }
                    break;
                    
                case "month":
                    // Month format is YYYY-MM
                    if (value) {
                        const parts = value.split('-');
                        const year = parseInt(parts[0]);
                        const month = parseInt(parts[1]) - 1; // JS months are 0-based
                        
                        startDate = new Date(year, month, 1);
                        endDate = new Date(year, month + 1, 0); // Last day of month
                    }
                    break;
                    
                case "year":
                    // Year is a simple number
                    if (value) {
                        const year = parseInt(value);
                        startDate = new Date(year, 0, 1);
                        endDate = new Date(year, 11, 31);
                    }
                    break;
                    
                case "custom":
                    // Don't update the date fields for custom
                    return;
            }
            
            if (startDate && endDate) {
                startDateInput.value = formatDate(startDate);
                endDateInput.value = formatDate(endDate);
            }
        }
        
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        document.getElementById('budgetForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("../backend/budget_manager.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadBudgets();
                    this.reset();
                    
                    // Reset to default month
                    const currentDate = new Date();
                    const currentMonth = currentDate.getFullYear() + "-" + String(currentDate.getMonth() + 1).padStart(2, '0');
                    document.getElementById("period_type").value = "month";
                    document.getElementById("period_value").value = currentMonth;
                    updatePeriodSelector("month");
                }
            })
            .catch(error => console.error("Error submitting budget:", error));
        });

        function loadBudgets() {
            fetch("../backend/get_budgets.php")
                .then(response => response.json())
                .then(budgets => {
                    const budgetList = document.getElementById("budgetList");
                    budgetList.innerHTML = "";

                    budgets.forEach(budget => {
                        const percentage = budget.amount > 0 ? (budget.spent / budget.amount) * 100 : 0;
                        const progressClass = percentage >= 90 ? 'danger' : 
                                           percentage >= 75 ? 'warning' : 'success';

                        const listItem = document.createElement("li");
                        listItem.className = "budget-item";
                        listItem.innerHTML = `
                            <div class="budget-item-header">
                                <span class="budget-category">${budget.category_name}</span>
                                <span class="budget-dates">${budget.start_date} - ${budget.end_date}</span>
                                         <i class="fas fa-trash delete-icon" onclick="deleteBudget(${budget.id})"></i>
                            </div>
                            <div class="budget-amount">
                                <span>Rs${budget.spent.toLocaleString()} / Rs${budget.amount.toLocaleString()}</span>
                                <span>${percentage.toFixed(1)}%</span>
                         
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar ${progressClass}" 
                                     style="width: ${Math.min(percentage, 100)}%;"></div>
                            </div>

                        `;
                        budgetList.appendChild(listItem);
                    });
                })
                .catch(error => console.error("Error loading budgets:", error));
        }
        function deleteBudget(budgetId) {
    if (!confirm("Are you sure you want to delete this budget?")) return;

    fetch("../backend/delete_budget.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: budgetId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadBudgets();
        } else {
            alert(data.error || "Failed to delete budget");
        }
    })
    .catch(error => console.error("Error deleting budget:", error));
}

        loadBudgets();
    </script>
</body>
</html>