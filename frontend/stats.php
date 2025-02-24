<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Statistics</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiko:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            background: white;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 4px;
            margin: 10px 0;
            display: none;
        }
    </style>
</head>
<body>
    <div class="main">
        <?php include 'sidebar.php'; ?> 
        <div class="mid-bar">
            <div class="dash">Statistics</div>
            <div class="chart-controls" style="margin-bottom: 20px;">
                <select id="timePeriod" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <option value="week">Weekly</option>
                    <option value="month" selected>Monthly</option>
                    <option value="year">Yearly</option>
                </select>
                <button id="refreshData" style="margin-left: 10px; padding: 8px 16px; border-radius: 4px; background: #4CAF50; color: white; border: none; cursor: pointer;">
                    Refresh Data
                </button>
            </div>
            <div class="error-message" id="error_display"></div>
            <div class="chart-container">
                <canvas id="donutChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
    <script>
        let currentPeriod = 'month';
        let donutChart, lineChart;
        
        document.getElementById('refreshData').addEventListener('click', fetchChartData);
        document.getElementById('timePeriod').addEventListener('change', function(e) {
            currentPeriod = e.target.value;
            fetchChartData();
        });

        async function fetchChartData() {
            try {
                const response = await fetch(`../backend/chart.php?period=${currentPeriod}`);
                if (!response.ok) throw new Error(`Server error: ${response.statusText}`);
                const data = await response.json();
                updateCharts(data);
            } catch (error) {
                document.getElementById('error_display').textContent = error.message;
                document.getElementById('error_display').style.display = 'block';
            }
        }

        function updateCharts(data) {
            if (donutChart) donutChart.destroy();
            if (lineChart) lineChart.destroy();

            const ctxDonut = document.getElementById('donutChart').getContext('2d');
            donutChart = new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Income', 'Expense'],
                    datasets: [{
                        data: [data.currentMonth.income, data.currentMonth.expense],
                        backgroundColor: ['#3E95CD', '#FF5733']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            const labels = data.monthly.map(d => d.month);
            const incomeData = data.monthly.map(d => d.income);
            const expenseData = data.monthly.map(d => d.expense);
            const balanceData = data.monthly.map(d => d.balance);
            
            const ctxLine = document.getElementById('lineChart').getContext('2d');
            lineChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Income', data: incomeData, borderColor: '#3E95CD', fill: false },
                        { label: 'Expense', data: expenseData, borderColor: '#FF5733', fill: false },
                        { label: 'Net Balance', data: balanceData, borderColor: '#8E5EA2', fill: false }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
        
        fetchChartData();
    </script>
</body>
</html>
