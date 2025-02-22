<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Management</title>
    <link rel="stylesheet" href="./css/home.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .progress-container {
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            overflow: hidden;
            height: 20px;
            margin-top: 5px;
        }
        .progress-bar {
            height: 100%;
            background-color: #4caf50;
            text-align: center;
            line-height: 20px;
            color: white;
            font-weight: bold;
            transition: width 0.5s ease-in-out;
        }
        .overall-progress {
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>
    <?php include "sidebar.php"; ?>

    <div class="mid-bar">
        <h2>Add Budget</h2>
        <form id="budgetForm">
            <label for="category">Category:</label>
            <select id="category" name="category" required></select>
            <br>
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" required>
            <br>
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            <br>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
            <br>
            <button type="submit">Add Budget</button>
        </form>

        <h2>Overall Progress</h2>
        <div class="progress-container">
            <div class="progress-bar" id="overallProgressBar" style="width: 0%;">0%</div>
        </div>

        <h2>Budgets</h2>
        <ul id="budgetList"></ul>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
                });

            document.getElementById("budgetForm").addEventListener("submit", function(event) {
                event.preventDefault();
                
                const jsonData = {
                    user_id: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null' ?>,
                    category_id: document.getElementById("category").value,
                    amount: document.getElementById("amount").value,
                    start_date: document.getElementById("start_date").value,
                    end_date: document.getElementById("end_date").value
                };
                
                fetch("../backend/budget_manager.php", {
                    method: "POST",
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(jsonData)
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadBudgets(); // Reload budget list
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while saving the budget.");
                });
            });

            function loadBudgets() {
                fetch("../backend/get_budgets.php")
                    .then(response => response.json())
                    .then(budgets => {
                        let budgetList = document.getElementById("budgetList");
                        let overallProgressBar = document.getElementById("overallProgressBar");
                        budgetList.innerHTML = ""; // Clear existing list

                        let totalBudget = 0;
                        let totalSpent = 0;

                        budgets.forEach(budget => {
                            totalBudget += parseFloat(budget.amount);
                            totalSpent += parseFloat(budget.spent_amount);

                            let percentage = (budget.spent_amount / budget.amount) * 100;
                            percentage = Math.min(percentage, 100); // Cap at 100%
                            
                            let listItem = document.createElement("li");
                            listItem.innerHTML = `
                                <p>${budget.category_name}: $${budget.spent_amount} / $${budget.amount} (From ${budget.start_date} to ${budget.end_date})</p>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: ${percentage}%;">${percentage.toFixed(1)}%</div>
                                </div>
                            `;
                            budgetList.appendChild(listItem);
                        });

                        // Update overall progress
                        let overallPercentage = totalBudget > 0 ? (totalSpent / totalBudget) * 100 : 0;
                        overallPercentage = Math.min(overallPercentage, 100);
                        overallProgressBar.style.width = overallPercentage + "%";
                        overallProgressBar.textContent = overallPercentage.toFixed(1) + "%";
                    })
                    .catch(error => console.error("Error loading budgets:", error));
            }

            loadBudgets(); // Load budgets on page load
        });
    </script>
    <script src="navigation.js"></script>
</body>
</html>