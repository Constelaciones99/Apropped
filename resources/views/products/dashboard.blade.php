@extends('layouts.app')

@section('content')
<div class="container pt-4">
    <span>
    <h1 class="text-2xl font-bold mb-6"><i class="fa-solid fa-chart-column"></i> Dashboard de Admin</h1>
    <a class="btn btn-dark my-2" href="{{ route('admin.index') }}"><i class="fa-solid fa-arrow-left"></i> Regresar</a>
    </span>
    {{-- Ventas del día --}}

    <div class="mb-6 p-4 bg-green-100 rounded shadow">
       <p> <h3 class="text-lg font-semibold">Ventas del día: <span class="text-3xl font-bold text-success">{{ $ventasHoy }}</span></h3>

       </p>
    </div>

    {{-- Selector de producto --}}
<div class="mb-4 my-3">
    <label for="productoSelect" class="block text-sm font-medium text-gray-700">Seleccionar producto:</label>
    <select id="productoSelect" class="mt-1 mb-3 block w-full p-2 border border-gray-300 rounded shadow">
        <option value="">-- Selecciona un producto --</option>
        @foreach ($productosStock as $producto)
            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
        @endforeach
    </select>

    <div id="productoDetalles" class="mb-6 p-4 bg-gray-100 rounded shadow hidden">
    <h3 class="font-semibold mb-2"><i class="fa-solid fa-thumbtack text-danger"></i> Detalles del producto</h3>
    <p id="detalleNombre" class="text-lg"></p>
    <p id="detalleStock"></p>
    <p id="detalleVendidos"></p>
    <p id="detalleUltimaVenta"></p>
</div>

</div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- 📊 Productos más vendidos --}}
        <div class="bg-white rounded-xl shadow p-4 h-64">
            <h2 class="text-md font-semibold mb-2"><i class="fa-solid fa-cart-shopping text-success"></i> Más vendidos</h2>
            <canvas id="topProductosChart" class="w-full h-full"></canvas>
        </div>

            {{-- 📦 Stock de productos --}}
        <div class="bg-white rounded-xl shadow p-4 h-64">
            <h2 class="text-md font-semibold mb-2"><i class="fa-solid fa-people-carry-box text-warning"></i> Stock</h2>
            <canvas id="stockChart" class="w-full h-full"></canvas>
        </div>

            {{-- 🎯 Participación de ventas por producto --}}
        <div class="bg-white rounded-xl shadow p-4 h-64">
            <h2 class="text-md font-semibold mb-2"><i class="fa-solid fa-scale-balanced text-primary"></i> Participación</h2>
            <canvas id="ventasPieChart" class="w-full h-full"></canvas>
        </div>

            {{-- 📈 Ventas por día ventas de este--}}
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white rounded-xl shadow p-4 h-96">
            <h2 class="text-md font-semibold mb-2"><i class="fa-solid fa-hand-holding-dollar"></i> Evolución de ventas</h2>
            <canvas id="ventasLineaChart" class="w-full h-full"></canvas>
        </div>
        {{-- Gráfico de subtotales por día --}}
        <div class="mb-5 bg-white rounded-xl shadow p-4 h-64">
            <h2 class="text-lg font-semibold mb-2"><i class="fa-solid fa-circle-dollar-to-slot text-warning"></i> Subtotal de ventas por día</h2>
            <canvas id="subtotalChart"></canvas>
        </div>

    </div>
</div>



@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Datos de Productos más vendidos return
        const topProductos = @json($topProductos);
        const nombresProductos = topProductos.map(p => p.nombre);
        const cantidadesVendidas = topProductos.map(p => parseInt(p.total_vendido));

        const ctxTop = document.getElementById('topProductosChart')?.getContext('2d');
        const ctxPie = document.getElementById('ventasPieChart')?.getContext('2d');
        if (ctxTop) {
            new Chart(ctxTop, {
                type: 'bar',
                data: {
                    labels: nombresProductos,
                    datasets: [{
                        label: 'Cantidad Vendida',
                        data: cantidadesVendidas,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        if (ctxPie) {
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: nombresProductos,
            datasets: [{
                data: cantidadesVendidas,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                ],
            }]
        },
        options: {
            responsive: true,
        }
    });
}

        // Stock Chart
        const stockProductos = @json($productosStock);
        const nombresStock = stockProductos.map(p => p.nombre);
        const nivelesStock = stockProductos.map(p => parseInt(p.stock));

        const ctxStock = document.getElementById('stockChart')?.getContext('2d');
        if (ctxStock) {
            new Chart(ctxStock, {
                type: 'bar',
                data: {
                    labels: nombresStock,
                    datasets: [{
                        label: 'Stock disponible',
                        data: nivelesStock,
                        backgroundColor: 'rgba(255, 206, 86, 0.6)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

    const ventasPorDia = @json($ventasPorDia);
    const fechas = ventasPorDia.map(v => v.fecha);
    const cantidades = ventasPorDia.map(v => v.total);

    const ctxLinea = document.getElementById('ventasLineaChart')?.getContext('2d');
let chartLinea = null;

function renderLineaChart(fechas, cantidades, label = 'Ventas por día', color = 'rgba(75, 192, 192, 1)') {
    if (chartLinea) {
        chartLinea.destroy();
    }

    chartLinea = new Chart(ctxLinea, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: label,
                data: cantidades,
                fill: false,
                borderColor: color,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

// Cargar gráfico inicial con todas las ventas

renderLineaChart(fechas, cantidades);

// Selector de producto
 document.getElementById('productoSelect').addEventListener('change', function (event) {
    sheng=this.options[this.selectedIndex].textContent
    productoId=this.value
    //productoId = this.options[this.selectedIndex].textContent;
    if (!productoId) {
        // Si se deselecciona el producto, vuelve a mostrar todas las ventas
        renderLineaChart(fechas, cantidades);
        return;
    }


    fetch(`/dashboard/ventas-producto/${productoId}`).then(res => res.json()).then(data => {
    const fechasFiltradas = data.ventas.map(v => v.fecha);
    const cantidadesFiltradas = data.ventas.map(v => v.total);
    renderLineaChart(fechasFiltradas, cantidadesFiltradas, 'Ventas de '+sheng, 'rgba(255, 99, 132, 1)');
        if (!productoId) {
    renderLineaChart(fechas, cantidades);
    document.getElementById('productoDetalles').classList.add('hidden');
    return;
}
    // Mostrar detalles del producto
    document.getElementById('productoDetalles').classList.remove('hidden');
    document.getElementById('detalleNombre').textContent = `🛒 Producto: ${data.producto.nombre}`;
    document.getElementById('detalleStock').textContent = `📦 Stock actual: ${data.producto.stock}`;
    document.getElementById('detalleVendidos').textContent = `✅ Total vendido: ${data.producto.total_vendido}`;
    document.getElementById('detalleUltimaVenta').textContent = `📅 Última venta: ${data.producto.ultima_venta ?? 'Sin registros'}`;
});




});

const subtotalesData = @json($ventasPorFecha);
        const fechasSubtotal = subtotalesData.map(d => d.fecha);
        const montosSubtotal = subtotalesData.map(d => parseFloat(d.subtotal));

        const ctxSubtotal = document.getElementById('subtotalChart')?.getContext('2d');
        if (ctxSubtotal) {
            new Chart(ctxSubtotal, {
                type: 'bar',
                data: {
                    labels: fechasSubtotal,
                    datasets: [{
                        label: 'Subtotal diario (S/.)',
                        data: montosSubtotal,
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'S/.' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Subtotal: S/.' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }


    });
</script>
@endsection
