<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    /**
     * Título que aparece encima de la gráfica en el dashboard.
     */
    protected ?string $heading = 'Ventas por mes';

    /**
     * Tamaño del widget en el dashboard.
     * full = ocupa todo el ancho de la página.
     */
    protected ?string $maxHeight = '300px';

    /**
     * Tipo de gráfica — bar = barras verticales.
     */
    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * Genera los datos para la gráfica.
     * Obtiene el total de ventas por mes de los últimos 12 meses.
     */
    protected function getData(): array
    {
        // Genera los últimos 12 meses
        $meses = collect(range(11, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i);
        });

        // Etiquetas del eje X — nombres de los meses
        $labels = $meses->map(fn ($mes) => $mes->translatedFormat('M Y'))->toArray();

        // Datos del eje Y — total de ventas por mes
        $data = $meses->map(function ($mes) {
            return Order::whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month)
                ->sum('total');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Ventas (S/.)',
                    'data'            => $data,
                    // Color de las barras — naranja como el tema
                    'backgroundColor' => 'rgba(251, 146, 60, 0.8)',
                    'borderColor'     => 'rgba(251, 146, 60, 1)',
                    'borderWidth'     => 2,
                    'borderRadius'    => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }
}