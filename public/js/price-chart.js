window.addEventListener('load', function () {
    if (typeof window.ApexCharts === 'undefined') {
        console.error("ApexCharts not loaded");
        return;
    }

    // Get price history data from the data attribute
    const chartContainer = document.querySelector("#price-chart-container");
    if (!chartContainer) {
        console.error("Chart container not found");
        return;
    }

    const priceHistoryString = chartContainer.dataset.priceHistory || '[]';
    console.log('Price history string from data attribute:', priceHistoryString);
    
    let rawData;
    try {
        rawData = JSON.parse(priceHistoryString);
        console.log('Parsed raw data:', rawData);
        console.log('Raw data type:', typeof rawData);
        console.log('Raw data length:', Array.isArray(rawData) ? rawData.length : 'Not an array');
        
        if (Array.isArray(rawData) && rawData.length > 0) {
            console.log('First data item:', rawData[0]);
            console.log('First data item keys:', Object.keys(rawData[0]));
        }
    } catch (e) {
        console.error('Error parsing price history JSON:', e);
        return;
    }

    const options = {
        chart: {
            type: 'line',
            height: 350,
            toolbar: {
                show: true
            }
        },

        series: [],

        xaxis: {
            type: 'datetime',
            labels: {
                datetimeUTC: false,
                format: 'MMM dd',
                rotate: -45,
                style: {
                    fontSize: '12px',
                    fontFamily: 'inherit'
                }
            },
            title: {
                text: 'Date',
                style: {
                    fontSize: '14px',
                    fontFamily: 'inherit',
                    fontWeight: 'bold'
                }
            }
        },

        yaxis: {
            title: {
                text: 'Price',
                style: {
                    fontSize: '14px',
                    fontFamily: 'inherit',
                    fontWeight: 'bold'
                }
            },
            labels: {
                formatter: function (val) {
                    return val.toFixed(2);
                }
            }
        },

        colors: ['#2563eb', '#f59e0b'], // Retail = blue, Wholesale = orange

        stroke: {
            width: 3,
            curve: 'smooth'
        },

        markers: {
            size: 4
        },

        tooltip: {
            x: {
                format: 'MMM dd, yyyy'
            },
            y: {
                formatter: function (val) {
                    return val.toFixed(2);
                }
            }
        },

        noData: {
            text: 'No price history available'
        },

        grid: {
            borderColor: '#e0e0e0',
            strokeDashArray: 4
        },

        legend: {
            position: 'top',
            horizontalAlign: 'left'
        }
    };

    const chart = new ApexCharts(chartContainer, options);
    chart.render();

    function loadChart() {
        if (!rawData.length) {
            chart.updateSeries([]);
            return;
        }

        console.log('Raw price history data:', rawData);

        // Function to safely parse date
        function parseDate(dateString) {
            if (!dateString) return null;
            
            // Handle different date formats
            let date;
            
            // If it's already a timestamp (number)
            if (typeof dateString === 'number') {
                date = new Date(dateString);
            } 
            // If it's a string, try different formats
            else if (typeof dateString === 'string') {
                // Try ISO format first
                if (dateString.includes('T')) {
                    date = new Date(dateString);
                } else {
                    // Try standard date format
                    date = new Date(dateString.replace(/-/g, '/'));
                }
            } else {
                // Fallback
                date = new Date(dateString);
            }
            
            const timestamp = date.getTime();
            
            if (isNaN(timestamp)) {
                console.warn('Invalid date string:', dateString);
                return null;
            }
            
            return timestamp;
        }

        const retail = rawData
            .filter(item => item.type === 'retail')
            .map(i => {
                const timestamp = parseDate(i.validFrom);
                const price = parseFloat(i.price);
                
                console.log('Retail item:', i, 'Parsed timestamp:', timestamp, 'Parsed price:', price);
                
                if (timestamp === null || isNaN(price)) {
                    console.warn('Invalid retail data point:', i);
                    return null;
                }
                
                return {
                    x: timestamp,
                    y: price
                };
            })
            .filter(item => item !== null);

        const wholesale = rawData
            .filter(item => item.type === 'wholesale')
            .map(i => {
                const timestamp = parseDate(i.validFrom);
                const price = parseFloat(i.price);
                
                console.log('Wholesale item:', i, 'Parsed timestamp:', timestamp, 'Parsed price:', price);
                
                if (timestamp === null || isNaN(price)) {
                    console.warn('Invalid wholesale data point:', i);
                    return null;
                }
                
                return {
                    x: timestamp,
                    y: price
                };
            })
            .filter(item => item !== null);

        console.log('Final retail data:', retail);
        console.log('Final wholesale data:', wholesale);

        if (retail.length > 0 || wholesale.length > 0) {
            chart.updateSeries([
                {
                    name: 'Retail',
                    data: retail
                },
                {
                    name: 'Wholesale',
                    data: wholesale
                }
            ]);
        } else {
            console.warn('No valid data points to display');
            chart.updateSeries([]);
        }
    }

    loadChart();

    // ✅ Optional: Dropdown toggle
    const toggle = document.getElementById('typeToggle');

    if (toggle) {
        toggle.addEventListener('change', function () {
            const type = this.value;

            if (type === 'retail') {
                chart.hideSeries('Wholesale');
                chart.showSeries('Retail');
            } else if (type === 'wholesale') {
                chart.hideSeries('Retail');
                chart.showSeries('Wholesale');
            }
        });
    }
});