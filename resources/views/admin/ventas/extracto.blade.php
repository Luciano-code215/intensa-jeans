<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extracto de Pedidos - Intensa Jeans</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            font-size: 12px;
            color: #222;
            padding: 20px;
            background: #fff;
        }
        h1 {
            font-size: 20px;
            margin-bottom: 4px;
        }
        .sub {
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #1a3352;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) td {
            background: #f9f9f9;
        }
        .num { text-align: center; }
        .der { text-align: right; }
        .total-general {
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            padding: 12px 6px;
            border-top: 2px solid #1a3352;
        }
        .productos {
            font-size: 11px;
            color: #555;
            max-width: 250px;
        }
        .estado-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .estado-creada { background: #fff3cd; color: #856404; }
        .estado-pagada { background: #d4edda; color: #155724; }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
        <div>
            <h1>Extracto de Pedidos</h1>
            <div class="sub">Intensa Jeans — {{ now()->format('d/m/Y H:i') }}</div>
        </div>
        <div class="no-print">
            <button onclick="window.print()" style="padding: 8px 20px; border: none; border-radius: 4px; background: #1a3352; color: #fff; font-weight: bold; cursor: pointer;">Imprimir / PDF</button>
        </div>
    </div>

    @if (request()->hasAny(['estado', 'fecha_inicio', 'fecha_fin', 'buscar']))
        <p style="color: #856404; background: #fff3cd; padding: 6px 12px; border-radius: 4px; font-size: 11px;">
            <strong>Filtros aplicados:</strong>
            @if (request('estado')) Estado: {{ request('estado') }} @endif
            @if (request('fecha_inicio')) Desde: {{ request('fecha_inicio') }} @endif
            @if (request('fecha_fin')) Hasta: {{ request('fecha_fin') }} @endif
            @if (request('buscar')) Búsqueda: {{ request('buscar') }} @endif
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width:50px;">N°</th>
                <th style="width:140px;">Cliente</th>
                <th>Teléfono</th>
                <th>Productos (SKU)</th>
                <th style="width:60px;">Estado</th>
                <th style="width:80px;" class="der">Subtotal</th>
                <th style="width:80px;" class="der">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $granTotal = 0; @endphp
            @forelse ($ordenes as $orden)
                @php
                    $productosStr = $orden->items->map(fn($i) => ($i->producto->sku ?? '—') . ' ' . $i->producto->nombre . ($i->talle ? ' (' . $i->talle . ')' : '') . ' x' . $i->cantidad)->implode('; ');
                @endphp
                <tr>
                    <td class="num">#{{ $orden->id }}</td>
                    <td>{{ $orden->nombre_contacto ?? optional($orden->user)->name ?? 'N/A' }}</td>
                    <td>{{ $orden->telefono_contacto ?? optional($orden->user)->telefono ?? '—' }}</td>
                    <td class="productos">{{ $productosStr }}</td>
                    <td><span class="estado-badge estado-{{ $orden->estado }}">{{ ucfirst($orden->estado) }}</span></td>
                    <td class="der">${{ number_format($orden->total, 0, ',', '.') }}</td>
                    <td class="der fw-bold">${{ number_format($orden->total, 0, ',', '.') }}</td>
                </tr>
                @php $granTotal += $orden->total; @endphp
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:30px;color:#999;">No hay órdenes activas.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="total-general">TOTAL GENERAL</td>
                <td class="total-general">${{ number_format($granTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Intensa Jeans — Extracto generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>