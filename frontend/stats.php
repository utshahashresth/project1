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
    <style>
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
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
        <?php include "header.php" ?>
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
                <div id="chart_div" style="width: 100%; height: 400px;"></div>
                <div class="loading-spinner" id="loading_indicator" style="display: none;"></div>
            </div>
            
            <div class="chart-container">
                <div id="monthly_chart_div" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
<script src="navigation.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load Google Charts with error handling
        try {
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(initializeCharts);
        } catch (error) {
            console.error('Failed to load Google Charts:', error);
            showError('Failed to initialize charts. Please refresh the page.');
        }

        let currentPeriod = 'month';
        let chartsInitialized = false;

        // Enhanced error handling
        function showError(message) {
            const errorDisplay = document.getElementById('error_display');
            errorDisplay.textContent = message;
            errorDisplay.style.display = 'block';
            setTimeout(() => {
                errorDisplay.style.display = 'none';
            }, 5000);
        }

        function showLoading() {
            document.getElementById('loading_indicator').style.display = 'block';
            document.getElementById('chart_div').style.opacity = '0.5';
            document.getElementById('monthly_chart_div').style.opacity = '0.5';
        }

        function hideLoading() {
            document.getElementById('loading_indicator').style.display = 'none';
            document.getElementById('chart_div').style.opacity = '1';
            document.getElementById('monthly_chart_div').style.opacity = '1';
        }

        async function fetchChartData(period) {
            try {
                const response = await fetch(`../backend/chart.php?period=${period}`);
                if (!response.ok) {
                    throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                }
                const data = await response.json();
                if (!data || !data.currentMonth || !data.monthly) {
                    throw new Error('Invalid data format received from server');
                }
                return data;
            } catch (error) {
                throw new Error(`Failed to fetch chart data: ${error.message}`);
            }
        }

        async function initializeCharts() {
            if (!chartsInitialized) {
                chartsInitialized = true;
                document.getElementById('refreshData').addEventListener('click', () => {
                    initializeCharts();
                });
                
                document.getElementById('timePeriod').addEventListener('change', function(e) {
                    currentPeriod = e.target.value;
                    initializeCharts();
                });
            }

            showLoading();
            try {
                const data = await fetchChartData(currentPeriod);
                drawPieChart(data.currentMonth, currentPeriod);
                drawComboChart(data.monthly, currentPeriod);
                hideLoading();
            } catch (error) {
                hideLoading();
                showError(error.message);
                console.error('Chart initialization error:', error);
            }
        }

        function drawPieChart(currentData, period) {
            try {
                const chartData = [
                    ['Category', 'Amount'],
                    ['Income', parseFloat(currentData.income) || 0],
                    ['Expense', parseFloat(currentData.expense) || 0]
                ];

                const data = google.visualization.arrayToDataTable(chartData);
                const options = {
                    title: `Current ${period.charAt(0).toUpperCase() + period.slice(1)} Income vs Expense`,
                    pieHole: 0.4,
                    colors: ['#4CAF50', '#F44336'],
                    legend: { position: 'bottom' },
                    chartArea: { width: '90%', height: '80%' },
                    tooltip: { format: 'currency' },
                    animation: {
                        startup: true,
                        duration: 1000,
                        easing: 'out'
                    }
                };

                const chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            } catch (error) {
                console.error('Pie chart error:', error);
                showError('Failed to draw pie chart');
            }
        }

        function drawComboChart(trendData, period) {
            try {
                const chartData = [[period.charAt(0).toUpperCase() + period.slice(1), 'Income', 'Expense', 'Net Balance']];
                
                trendData.forEach(data => {
                    const dateObj = new Date(data.month + '-01');
                    let label = formatDateLabel(dateObj, period);
                    
                    chartData.push([
                        label,
                        parseFloat(data.income) || 0,
                        parseFloat(data.expense) || 0,
                        parseFloat(data.balance) || 0
                    ]);
                });

                const data = google.visualization.arrayToDataTable(chartData);
                const options = {
                    title: `${period.charAt(0).toUpperCase() + period.slice(1)}ly Financial Overview`,
                    colors: ['#4CAF50', '#F44336', '#2196F3'],
                    chartArea: { width: '80%', height: '70%' },
                    legend: { position: 'top' },
                    seriesType: 'bars',
                    series: {
                        2: { type: 'line', targetAxisIndex: 1 }
                    },
                    vAxes: {
                        0: { title: 'Amount (₹)' },
                        1: { title: 'Net Balance (₹)' }
                    },
                    hAxis: {
                        title: period.charAt(0).toUpperCase() + period.slice(1),
                        slantedText: true,
                        slantedTextAngle: 45
                    },
                    animation: {
                        startup: true,
                        duration: 1000,
                        easing: 'out'
                    }
                };

                const chart = new google.visualization.ComboChart(document.getElementById('monthly_chart_div'));
                chart.draw(data, options);
            } catch (error) {
                console.error('Combo chart error:', error);
                showError('Failed to draw trend chart');
            }
        }

        function formatDateLabel(dateObj, period) {
            switch(period) {
                case 'week':
                    return `Week ${getWeekNumber(dateObj)}`;
                case 'month':
                    return dateObj.toLocaleDateString('en-US', { 
                        month: 'short', 
                        year: 'numeric' 
                    });
                case 'year':
                    return dateObj.getFullYear().toString();
                default:
                    return '';
            }
        }

        function getWeekNumber(date) {
            const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
            const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
            return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
        }
    </script>
    <script src="navigation.js"></script>
</body>
</html>