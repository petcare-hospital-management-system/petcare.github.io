<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('vaccinationChart').getContext('2d');
    const vaccinationChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr'], // you can fetch this dynamically too
            datasets: [{
                label: 'Vaccinations',
                data: [10, 20, 15, 25], // replace with dynamic PHP values
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        }
    });
</script>
