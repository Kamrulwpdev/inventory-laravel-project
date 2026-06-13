<div class="card shadow border-0" wire:ignore>
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">{{ $chartData['title'] }}</h5>
        <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" wire:click.prevent="getMonthlySales">Monthly</a></li>
                <li><a class="dropdown-item" href="#" wire:click.prevent="getYearlySales">Yearly</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div style="height: 300px; width: 100%;">
            <canvas id="monthlySalesChart"></canvas>
        </div>
    </div>

    <script>
    document.addEventListener('livewire:initialized', () => {
        // Use the NEW unique ID
        const canvas = document.getElementById('monthlySalesChart');
        const ctx = canvas.getContext('2d');
        
        // 1. Create the Gradient
        const gradientFill = ctx.createLinearGradient(0, 0, canvas.offsetWidth, 0);
        gradientFill.addColorStop(0, '#36d1dc'); 
        gradientFill.addColorStop(1, '#ff7af5'); 

        const chartConfig = {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Sales',
                    data: @json($chartData['data']),
                    borderColor: gradientFill,
                    borderWidth: 4,
                    fill: false, 
                    tension: 0.5, 
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(200, 200, 200, 0.2)',
                            dash: [5, 5] 
                        },
                        border: { display: false }
                    }
                }
            },
            plugins: [{
                beforeDraw: (chart) => {
                    const { ctx } = chart;
                    ctx.save();
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.1)';
                    ctx.shadowBlur = 10;
                    ctx.shadowOffsetY = 10;
                },
                afterDraw: (chart) => {
                    chart.ctx.restore();
                }
            }]
        };

        // Initialize the instance
        window.monthlySalesInstance = new Chart(ctx, chartConfig);

        // LIVEWIRE V3: Listen for the dispatched event
        Livewire.on('update-chart-data', (event) => {
            if (window.monthlySalesInstance) {
                // In Livewire v3, event data is accessed directly from the object
                window.monthlySalesInstance.data.labels = event.labels;
                window.monthlySalesInstance.data.datasets[0].data = event.data;
                
                // Update the title in the UI
                document.querySelector('.text-primary').innerText = event.title;
                
                window.monthlySalesInstance.update();
            }
        });
    });
</script>
</div>