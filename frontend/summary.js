let monthlyTrendChart;
let categoryChart;

async function fetchFinancialData() {
    try {
        console.log("Attempting to fetch financial data...");
        const response = await fetch('../backend/chart_data.php?period=month');
        if (!response.ok) {
            console.error(`Server responded with status: ${response.status}`);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        console.log("Financial data received:", data);
        
        // Validate data structure
        if (!data.monthly || !Array.isArray(data.monthly) || !data.categories) {
            console.error("Invalid data format received:", data);
            throw new Error("Invalid data format");
        }
        
        return data;
    } catch (error) {
        console.error("Error fetching financial data:", error);
        return { monthly: [], currentMonth: {}, categories: [] };
    }
}

// Helper function to create a vertical gradient
function createGradient(ctx, colorStart, colorEnd) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, colorStart);
    gradient.addColorStop(1, colorEnd);
    return gradient;
}

async function updateFinancialStats() {
    try {
        // Fetch chart data and sum data concurrently
        const [financialData, sumData] = await Promise.all([
            fetchFinancialData(),
            fetch('../backend/sum_fetch.php').then(response => response.json())
        ]);

        // Extract sum values from the fetched data
        const totalIncome = sumData.total_income || 0;
        const totalExpenses = sumData.total_expense || 0;
        const monthlySavings = sumData.total_savings || 0;

        // Update the Total Expenses and Monthly Savings displays
        document.getElementById('totalExpenses').innerText = totalExpenses.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        document.getElementById('monthlySavings').innerText = monthlySavings.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Calculate Financial Health based on income and expenses
        let healthScore = 50;  // Start with a default base score
        if (totalIncome > 0) {
            const expenseRatio = (totalExpenses / totalIncome) * 100;
            healthScore += (100 - expenseRatio) / 2;
            healthScore = Math.max(0, Math.min(100, healthScore));
        }
        
        // Log the calculated health score
        console.log(`Financial Health Score: ${healthScore}%, Total Income: ${totalIncome}, Total Expenses: ${totalExpenses}`);
        
        // Update the financial health text display
        const financialHealthElement = document.getElementById('financialHealth');
        if (financialHealthElement) {
            financialHealthElement.innerText = Math.round(healthScore) + "/100";
            
            // Get the specific progress bar that follows the financialHealth element
            const financialHealthBar = financialHealthElement.nextElementSibling;
            if (financialHealthBar && financialHealthBar.classList.contains('progress-bar')) {
                const progressValue = financialHealthBar.querySelector('.progress-value');
                if (progressValue) {
                    progressValue.style.width = Math.round(healthScore) + "%";
                    console.log("Updated financial health progress bar width to:", Math.round(healthScore) + "%");
                } else {
                    console.error("Progress value element not found within financial health bar");
                }
            } else {
                console.error("Progress bar element not found after financial health element");
            }
        } else {
            console.error("Financial health element not found");
        }

        // Destroy existing charts if they already exist
        if (monthlyTrendChart) monthlyTrendChart.destroy();
        if (categoryChart) categoryChart.destroy();

        // Check if chart elements exist in the DOM
        const monthlyTrendElement = document.getElementById('monthlyTrendChart');
        const categoryChartElement = document.getElementById('categoryChart');
        
        if (!monthlyTrendElement || !categoryChartElement) {
            console.error("Chart canvas elements not found in DOM");
            return;
        }

        // Set global chart defaults for a minimal aesthetic
        Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
        Chart.defaults.font.size = 11;
        Chart.defaults.color = '#6b7280';
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;

        // Create gradients for the line chart
        const monthlyTrendCtx = monthlyTrendElement.getContext('2d');
        const incomeGradient = createGradient(monthlyTrendCtx, 'rgba(16, 185, 129, 0.4)', 'rgba(16, 185, 129, 0.0)');
        const expenseGradient = createGradient(monthlyTrendCtx, 'rgba(239, 68, 68, 0.4)', 'rgba(239, 68, 68, 0.0)');

        // Create the Monthly Trend Chart (using a line chart for a modern look)
        monthlyTrendChart = new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: financialData.monthly.map(item => item.month),
                datasets: [
                    {
                        label: 'Income',
                        data: financialData.monthly.map(item => item.income),
                        borderColor: '#10b981',
                        backgroundColor: incomeGradient,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Expenses',
                        data: financialData.monthly.map(item => item.expense),
                        borderColor: '#ef4444',
                        backgroundColor: expenseGradient,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#ef4444',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: {
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'white',
                        titleColor: '#111827',
                        bodyColor: '#374151',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': रू';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-IN').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.03)',
                            drawBorder: false
                        },
                        ticks: {
                            padding: 10,
                            callback: function(value) {
                                if (value >= 1000) {
                                    return 'रू' + (value / 1000) + 'k';
                                }
                                return 'रू' + value;
                            }
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { padding: 10 },
                        border: { display: false }
                    }
                }
            }
        });

        // Create the Category Chart (using a doughnut chart for a modern look)
        const categoryCtx = categoryChartElement.getContext('2d');
        categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: financialData.categories.map(item => item.category),
                datasets: [{
                    data: financialData.categories.map(item => item.amount),
                    backgroundColor: [
                        '#3b82f6cc', // Blue
                        '#10b981cc', // Green
                        '#8b5cf6cc', // Purple
                        '#f59e0bcc', // Orange
                        '#ef4444cc', // Red
                        '#6b7280cc'  // Gray
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverBorderColor: [
                        '#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#6b7280'
                    ],
                    hoverBorderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'white',
                        titleColor: '#111827',
                        bodyColor: '#374151',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, cur) => acc + cur, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: रू${new Intl.NumberFormat('en-IN').format(value)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error("Error updating financial stats:", error);
    }
}

async function updateBudgetStatus() {
    try {
        console.log("Fetching budget status...");
        const response = await fetch('../backend/budget_status.php');
        
        // Check response status
        if (!response.ok) {
            throw new Error(`Budget status API returned ${response.status}`);
        }
        
        const data = await response.json();
        console.log("Budget Status Data:", data);

        const overallBudgetStatus = document.getElementById('overallBudgetStatus');
        const budgetCards = document.getElementById('budgetCards');
        
        // Check if DOM elements exist
        if (!overallBudgetStatus || !budgetCards) {
            console.error("Budget DOM elements not found");
            return;
        }
        
        budgetCards.innerHTML = ''; // Clear previous content

        if (data.success) {
            let totalBudget = 0;
            let totalSpent = 0;

            if (Array.isArray(data.budgets) && data.budgets.length > 0) {
                // Calculate total budget and spending
                data.budgets.forEach(budget => {
                    totalBudget += parseFloat(budget.amount);
                    totalSpent += parseFloat(budget.spent_amount);
                });

                // Determine overall budget status
                const budgetStatus = totalSpent <= totalBudget ? 'On Track' : 'Over Budget';
                overallBudgetStatus.innerText = budgetStatus;

                // Update text color based on status
                overallBudgetStatus.classList.remove('text-red', 'text-teal');
                overallBudgetStatus.classList.add(budgetStatus === 'On Track' ? 'text-teal' : 'text-red');

                // Calculate percentage spent
                const percentSpent = totalBudget > 0 ? Math.min((totalSpent / totalBudget) * 100, 100) : 0;

                // Create a single progress bar
                const overallProgressBar = document.createElement('div');
                overallProgressBar.className = 'progress-bar';
                overallProgressBar.innerHTML = `
                    <div class="progress-value ${budgetStatus === 'On Track' ? 'bg-teal' : 'bg-red'}" style="width: ${percentSpent}%"></div>
                `;
                
                console.log(`Budget status updated: ${budgetStatus}, progress width: ${percentSpent}%`);

                // Add the progress bar to the budgetCards container
                budgetCards.appendChild(overallProgressBar);
            } else {
                console.error("No budgets found in the response.");
                overallBudgetStatus.innerText = "No Budget Data";
                overallBudgetStatus.classList.remove('text-red', 'text-teal');

                // Add empty progress bar if no data
                const emptyProgressBar = document.createElement('div');
                emptyProgressBar.className = 'progress-bar';
                emptyProgressBar.innerHTML = `
                    <div class="progress-value" style="width: 0%"></div>
                `;
                budgetCards.appendChild(emptyProgressBar);
            }
        } else {
            console.error(data.message || 'Error fetching budget data');
        }
    } catch (error) {
        console.error("Error fetching budget status:", error, error.stack);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the dashboard
    updateFinancialStats();
    updateBudgetStatus();
    
    // Set up the date range listener
    const dateRangeSelect = document.getElementById('dateRange');
    if (dateRangeSelect) {
        dateRangeSelect.addEventListener('change', function() {
            console.log("Date range changed to:", this.value);
            updateFinancialStats();
        });
    }
    
    // Automatically refresh data every 30 seconds
    setInterval(updateFinancialStats, 30000);
    setInterval(updateBudgetStatus, 30000);
});