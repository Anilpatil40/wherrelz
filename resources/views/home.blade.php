@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card p-3 bg-light">
            <div class="d-flex align-items-center">
                <h4>Dashboard</h4>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6 h-100">
                <div class="card p-3">
                    <canvas id="bar" style="width:100%;max-width:700px"></canvas>
                </div>
            </div>
            <div class="col-md-6 h-100">
                <div class="card p-3">
                    <canvas id="line" style="width:100%;max-width:700px"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function setBarChart(data) {
            new Chart(
                document.getElementById('bar'), {
                    type: 'bar',
                    data: {
                        labels: data.map(row => row.currency),
                        datasets: [{
                            label: 'Acquisitions by currency',
                            backgroundColor: data.map(() => getRandomColor()),
                            data: data.map(row => row.count)
                        }]
                    }
                }
            );
        }

        function setLineChart(lineData) {
            var myLineChart = new Chart(document.getElementById('line'), {
                type: 'doughnut',
                data: lineData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        $.ajax({
            url: '{{ route('dashboard.data') }}',
            type: 'GET',
            success: function(response = []) {
                console.log(response);
                setBarChart(response);
                currencies = [];
                counts = [];
                response.map((data) => {
                    currencies.push(data.currency);
                    counts.push(data.count);
                });
                setLineChart({
                    labels: currencies,
                    datasets: [{
                        label: 'Currencies',
                        data: counts,
                        backgroundColor: currencies.map(() => getRandomColor()),
                        hoverOffset: 4
                    }]
                })
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
            }
        });

        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
@endpush
